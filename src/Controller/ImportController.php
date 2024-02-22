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

namespace Code4Nix\ContaoCsvImporter\Controller;

use Code4Nix\ContaoCsvImporter\Importer\ImporterFactory;
use Code4Nix\ContaoCsvImporter\Importer\KdaProductImport;
use Code4Nix\ContaoCsvImporter\Model\KdaProductModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

#[Route('/import', name: 'code4nix_contao_csv_import', defaults: ['_scope' => 'frontend', '_token_check' => true])]
class ImportController extends AbstractController
{
    public function __construct(
        private readonly TwigEnvironment $twig,
        private readonly ImporterFactory $importerFactory,
        private readonly ContaoFramework $framework,
    ) {
        $this->framework->initialize(true);
    }

    public function __invoke(): Response
    {
        if (!$this->importerFactory->hasImporter('kda_product')) {
            $message = 'Importer not configured. Please add a configuration to your config/config.yaml.';
        } else {
            /** @var KdaProductImport $importer */
            $importer = $this->importerFactory->getImporter('kda_product');

            // Launch import process
            $importer->import();

            $message = sprintf(
                'Successfully run the importer and found %d items.',
                KdaProductModel::countAll(),
            );
        }

        return new Response($this->twig->render(
            '@Code4NixContaoCsvImporter/importer/importer.html.twig',
            [
                'message' => $message,
            ]
        ));
    }
}
