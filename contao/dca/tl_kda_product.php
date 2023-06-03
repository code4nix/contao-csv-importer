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

use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;

$columns = [];

// Get columns from configuration
if (System::getContainer()->hasParameter('code4nix_contao_csv_importer.imports')) {
    $imports = System::getContainer()->getParameter('code4nix_contao_csv_importer.imports')['kda_product']['columns'];
    if (isset($imports['kda_product']['columns'])) {
        $columns = array_keys($imports['kda_product']['columns']);
    }
}

$GLOBALS['TL_DCA']['tl_kda_product'] = [
    'config'      => [
        'dataContainer'    => DC_Table::class,
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'markAsCopy'       => 'title',
        'sql'              => [
            'keys' => [
                'id'     => 'primary',
                'art_nr' => 'index',
            ],
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'         => DataContainer::MODE_UNSORTED,
            'headerFields' => ['art_nr', 'beschreibung_1'],
            'panelLayout'  => 'filter;sort,search,limit',
        ],
        'label'             => [
            'fields' => ['beschreibung_1'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit'   => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy'   => [
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.svg',
            ],
            'cut'    => [
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.svg',
            ],
            'delete' => [
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\'))return false;Backend.getScrollOffset()"',
            ],
            'show'   => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],
    'palettes'    => [
        '__selector__' => [],
        'default'      => '{title_legend},'.implode(',', $columns),
    ],
    'subpalettes' => [],
    'fields'      => [
        'id'     => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
    ],
];

// Add fields from configuration
foreach ($columns as $column) {
    $GLOBALS['TL_DCA']['tl_kda_product']['fields'][$column] = [
        'exclude'   => true,
        'search'    => true,
        'sorting'   => true,
        'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
        'inputType' => 'text',
        'eval'      => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ];
}
