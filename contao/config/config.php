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

use Code4Nix\ContaoCsvImporter\Model\KdaProductModel;

/*
 * Backend modules
 */
$GLOBALS['BE_MOD']['content']['kda_product'] = [
    'tables' => ['tl_kda_product'],
];

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_kda_product'] = KdaProductModel::class;
