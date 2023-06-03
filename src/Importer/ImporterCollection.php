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

class ImporterCollection
{
    /**
     * @param iterable<ImporterInterface> $importers
     */
    public function __construct(
        private readonly iterable $importers,
    ) {
    }

    /**
     * @return iterable<ImporterInterface>
     */
    public function getImporters(): iterable
    {
        return $this->importers;
    }
}
