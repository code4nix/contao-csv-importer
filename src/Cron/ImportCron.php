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

namespace Code4Nix\ContaoCsvImporter\Cron;

use Code4Nix\ContaoCsvImporter\Importer\GenericImporter;
use Code4Nix\ContaoCsvImporter\Importer\ImporterFactory;
use Code4Nix\ContaoCsvImporter\Model\KdaProductModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Monolog\ContaoContext;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Path;

#[AsCronJob('*/30 * * * *')]
#[AsCronJob('hourly')]
class ImportCron
{
    private const LOG_TYPE = 'IMPORT_PRODUCT_INVENTORY';

    public function __construct(
        private readonly ImporterFactory $importerFactory,
        private readonly ContaoFramework $framework,
        private readonly LoggerInterface|null $contaoGeneralLogger,
    ) {
        $this->framework->initialize(true);
    }

    public function __invoke(): void
    {
        if (!$this->importerFactory->hasImporter('kda_product')) {
            $error = 'Importer not configured. Please add a configuration to your config/config.yaml.';

            throw new \Exception($error);
        }

        /** @var GenericImporter $importer */
        $importer = $this->importerFactory->getImporter('kda_product');

        // Launch import process
        $importer->import();

        $message = sprintf(
            'Successfully imported file "%s". Data records: %d',
            $importer->getSourceFilePath(),
            KdaProductModel::countAll(),
        );

        $this->contaoGeneralLogger?->info(
            $message,
            ['contao' => new ContaoContext(__METHOD__, self::LOG_TYPE)]
        );
    }
}
