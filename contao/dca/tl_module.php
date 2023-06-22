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

$GLOBALS['TL_DCA']['tl_module']['palettes']['product_listing'] = '
    {title_legend},name,headline,type;
    {config_legend},tableSelect;
    {protected_legend:hide},protected;
    {expert_legend:hide},cssID
';

$GLOBALS['TL_DCA']['tl_module']['fields']['tableSelect'] = [
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql'       => "varchar(64) COLLATE ascii_bin NOT NULL default ''",
];
