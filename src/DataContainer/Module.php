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

namespace Code4Nix\ContaoCsvImporter\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\DataContainer;

class Module
{
    private Adapter $database;

    public function __construct(ContaoFramework $framework)
    {
        $this->database = $framework->getAdapter(Database::class);
    }

    #[AsCallback(table: 'tl_module', target: 'fields.tableSelect.options', priority: 100)]
    public function listTables(DataContainer $dc): array
    {
        return $this->database->getInstance()->listTables();
    }
}
