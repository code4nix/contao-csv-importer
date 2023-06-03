<?php

declare(strict_types=1);

/*
 * This file is part of Contao CSV Importer.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/code4nix/contao-csv-importer
 */

namespace Code4Nix\ContaoCsvImporter\Importer\Config;

final class Config
{
    public const REQUIRED = [
        'name',
        'importer',
        'target_table',
        'source_file_path',
        'enclosure',
        'delimiter',
        'file_encoding',
        'output_encoding',
        'columns',
        'insert_mode',
    ];

    private string $name;
    private string $importer;
    private string $target_table;
    private string $source_file_path;
    private string $enclosure;
    private string $delimiter;
    private string $file_encoding;
    private string $output_encoding;
    private array $columns;
    private string $insert_mode;

    public function __construct(array $configuration)
    {
        foreach (self::REQUIRED as $prop) {
            $this->{$prop} = $configuration[$prop];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getImporter(): string
    {
        return $this->importer;
    }

    public function setImporter(string $value): void
    {
        $this->importer = $value;
    }

    public function getTargetTable(): string
    {
        return $this->target_table;
    }

    public function setTargetTable(string $value): void
    {
        $this->target_table = $value;
    }

    public function getSourceFilePath(): string
    {
        return $this->source_file_path;
    }

    public function setSourceFilePath(string $value): void
    {
        $this->source_file_path = $value;
    }

    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    public function setEnclosure(string $value): void
    {
        $this->enclosure = $value;
    }

    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    public function setDelimiter(string $value): void
    {
        $this->delimiter = $value;
    }

    public function getFileEncoding(): string
    {
        return $this->file_encoding;
    }

    public function setFileEncoding(string $value): void
    {
        $this->file_encoding = $value;
    }

    public function getOutputEncoding(): string
    {
        return $this->output_encoding;
    }

    public function setOutputEncoding(string $value): void
    {
        $this->output_encoding = $value;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $value): void
    {
        $this->columns = $value;
    }

    public function getInsertMode(): string
    {
        return $this->insert_mode;
    }

    public function setInsertMode(string $value): void
    {
        $this->insert_mode = $value;
    }
}
