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

use Code4Nix\ContaoCsvImporter\Importer\Config\Config;

interface ImporterInterface
{
    public function supports(string $nameImporter): bool;

    public function import(): void;

    public function getConfig(): Config;

    public function setConfig(Config $config): void;

    public function getRecords(): array;

    public function setRecords(array $records): void;

    public function getCurrentRecord(): array;

    public function setCurrentRecord(array $record): void;

    public function getSourceFilePath($blnAbsolute = false): string;
}
