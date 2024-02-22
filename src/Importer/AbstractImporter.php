<?php

declare(strict_types=1);

/*
 * This file is part of Contao CSV Importer.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/code4nix/contao-csv-importer
 */

namespace Code4Nix\ContaoCsvImporter\Importer;

use Code4Nix\ContaoCsvImporter\Event\AfterImportEvent;
use Code4Nix\ContaoCsvImporter\Event\BeforeImportEvent;
use Code4Nix\ContaoCsvImporter\Importer\Config\Config;
use Code4Nix\ContaoCsvImporter\Importer\Exception\ColumnNotFoundInCsvHeaderException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Safe\Exceptions\IconvException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use function Safe\iconv;

abstract class AbstractImporter implements ImporterInterface
{
    private Config|null $config = null;
    private array $records = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly string $projectDir,
    ) {
    }

    public function supports(string $nameImporter): bool
    {
        return GenericImporter::NAME === $nameImporter;
    }

    /**
     * Called from the ImporterFactory upon initialization.
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @throws Exception
     * @throws IconvException
     * @throws InvalidArgument
     * @throws UnavailableStream
     * @throws \League\Csv\Exception
     */
    public function import(): void
    {
        $this->loadRecordsFromFile();

        $this->eventDispatcher->dispatch(new BeforeImportEvent($this), BeforeImportEvent::NAME);

        $this->connection->beginTransaction();
        $insertIds = [];

        try {
            // Truncate table before insert
            if ('truncate' === $this->config->getInsertMode()) {
                /** @noinspection SqlResolve */
                $this->connection->executeStatement('DELETE FROM '.$this->config->getTargetTable().' WHERE id > 0');
            }

            foreach ($this->getRecords() as $row) {
                $this->connection->insert($this->config->getTargetTable(), $row);
                $insertIds[] = $this->connection->lastInsertId();
            }

            $this->connection->commit();
        } catch (\Exception $e) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }

            throw $e;
        }

        $this->eventDispatcher->dispatch(new AfterImportEvent($this, $insertIds), AfterImportEvent::NAME);
    }

    public function getSourceFilePath($blnAbsolute = false): string
    {
        $path = $this->projectDir.'/'.$this->config->getSourceFilePath();

        if (is_file($path)) {
            $strSrc = $path;
        } elseif (is_dir($path)) {
            $strSrc = $this->getSourcePathFromFolderPath($path);
        } else {
            $strSrc = null;
        }

        if (empty($strSrc)) {
            throw new \Exception(sprintf('Could not find import file in "%s".', $path));
        }

        if ($blnAbsolute) {
            return $strSrc;
        }

        return Path::makeRelative($strSrc, $this->projectDir);
    }

    public function setRecords(array $records): void
    {
        $this->records = $records;
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @throws IconvException
     * @throws InvalidArgument
     * @throws UnavailableStream
     * @throws \League\Csv\Exception
     */
    protected function loadRecordsFromFile(): void
    {
        $content = file_get_contents($this->getSourceFilePath(true));

        if ($this->config->getOutputEncoding() !== $this->config->getFileEncoding() && !mb_check_encoding($content, $this->config->getOutputEncoding())) {
            $content = iconv($this->config->getFileEncoding(), $this->config->getOutputEncoding(), $content);
        }

        $csv = Reader::createFromPath($this->createTempFile($content));
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($this->config->getDelimiter());
        $csv->setEnclosure($this->config->getEnclosure());

        $i = 0;
        $records = [];

        foreach ($csv as $record) {
            $set = [];
            $set['tstamp'] = time();

            foreach ($this->config->getColumns() as $key_contao => $key_csv) {
                if (0 === $i) {
                    if (!\in_array($key_csv, $csv->getHeader(), true)) {
                        throw new ColumnNotFoundInCsvHeaderException(sprintf(ColumnNotFoundInCsvHeaderException::ERROR_MESSAGE, $key_csv, $this->getSourceFilePath(), implode('", "', $csv->getHeader())));
                    }
                }
                $set[$key_contao] = $record[$key_csv];
            }

            ++$i;

            $records[] = $set;
        }

        $this->setRecords($records);
    }

    protected function createTempFile(string $content): string
    {
        $tempFile = sys_get_temp_dir().\DIRECTORY_SEPARATOR.uniqid('csv_import_').'.csv';

        $fs = new Filesystem();
        $fs->appendToFile($tempFile, $content);

        return $tempFile;
    }

    protected function getSourcePathFromFolderPath(string $folderPath): string|null
    {
        if (!is_dir($folderPath)) {
            throw new \Exception(sprintf('Path "%s" seems not to be a directory.', $folderPath));
        }

        $finder = new Finder();
        $finder
            ->files()
            ->in($folderPath)
            ->name('*.csv')
            ->ignoreDotFiles(true)
            ->depth('== 0')
            ->sortByName()
            ->reverseSorting()
        ;

        // check if there are any search results
        if (!$finder->hasResults()) {
            return null;
        }

        $src = null;

        foreach ($finder as $file) {
            $src = $file->getRealPath();
            break;
        }

        return $src;
    }
}
