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

namespace Code4Nix\ContaoCsvImporter\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaggedImporterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('code4nix.contao_csv_importer.importer.collection')) {
            return;
        }

        $definition = $container->findDefinition('code4nix.contao_csv_importer.importer.collection');
        $services = [];

        foreach ($container->findTaggedServiceIds('code4nix_contao_csv_importer.importer', true) as $serviceId => $attributes) {
            $priority = $attributes[0]['priority'] ?? 0;
            $class = $container->getDefinition($serviceId)->getClass();
            $services[$priority][$class] = new Reference($serviceId);
        }

        foreach (array_keys($services) as $priority) {
            ksort($services[$priority], SORT_NATURAL); // Order by class name ascending
        }

        if ($services) {
            krsort($services); // Order by priority descending
            $services = array_merge(...$services);
        }

        $definition->setArgument(0, $services);
    }
}
