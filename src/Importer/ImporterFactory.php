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

namespace Code4Nix\ContaoCsvImporter\Importer;

use Code4Nix\ContaoCsvImporter\Importer\Config\Config;
use Code4Nix\ContaoCsvImporter\Importer\Exception\NoMatchingImporterException;

class ImporterFactory
{
    public function __construct(
        private readonly ImporterCollection $importerCollection,
        private readonly array $bundleConfig,
    ) {
    }

    public function getImporter(string $name): ImporterInterface
    {
        return $this->getMatching($name);
    }

    public function hasImporter(string $name): bool
    {
        /** @var array<ImporterInterface> $services */
        $services = $this->importerCollection->getImporters();

        foreach ($services as $service) {
            if ($service->supports($this->bundleConfig[$name]['importer'])) {
                $arrConfig = array_merge($this->bundleConfig[$name], ['name' => $name]);
                $service->setConfig(new Config($arrConfig));

                if ($service instanceof ImporterInterface) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getMatching(string $name): ImporterInterface
    {
        /** @var array<ImporterInterface> $services */
        $services = $this->importerCollection->getImporters();

        foreach ($services as $service) {
            if ($service->supports($this->bundleConfig[$name]['importer'])) {
                $arrConfig = array_merge($this->bundleConfig[$name], ['name' => $name]);
                $service->setConfig(new Config($arrConfig));

                return $service;
            }
        }

        throw new NoMatchingImporterException(sprintf(NoMatchingImporterException::ERROR_MESSAGE, $name));
    }
}
