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

namespace Code4Nix\ContaoCsvImporter\DependencyInjection;

use Code4Nix\ContaoCsvImporter\Importer\GenericImporter;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'code4nix_contao_csv_importer';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('imports')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('importer')->defaultValue(GenericImporter::NAME)->end()
                            ->scalarNode('source_file_path')->info('Add a relative file or folder path.')->isRequired()->end()
                            ->enumNode('file_encoding')->values(['ASCII', 'UTF-8', 'ISO-8859-1'])->defaultValue('UTF-8')->end()
                            ->enumNode('output_encoding')->values(['ASCII', 'UTF-8', 'ISO-8859-1'])->defaultValue('UTF-8')->end()
                            ->scalarNode('delimiter')->defaultValue(';')->end()
                            ->scalarNode('enclosure')->defaultValue('"')->end()
                            ->scalarNode('target_table')->isRequired()->end()
                            ->enumNode('insert_mode')->values(['append', 'truncate'])->isRequired()->end()
                            ->arrayNode('columns')
                                ->useAttributeAsKey('name')
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
