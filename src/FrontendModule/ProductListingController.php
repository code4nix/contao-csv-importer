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

namespace Code4Nix\ContaoCsvImporter\FrontendModule;

use Code4Nix\ContaoCsvImporter\Cron\ImportCron;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(category:'miscellaneous', template: 'mod_product_listing')]
class ProductListingController extends AbstractFrontendModuleController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Packages $packages,
    ) {
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if (!$request->cookies->has('display_mode')) {
            $showAll = false;
        } elseif ('minimize_table' === $request->cookies->get('display_mode')) {
            $showAll = false;
        } else {
            $showAll = true;
        }

        $template->display_mode = $showAll ? 'maximize_table' : 'minimize_table';

        $template->lastImportTstamp = $this->connection->fetchOne(
            'SELECT tstamp FROM tl_log WHERE action = :action ORDER BY tstamp DESC',
            ['action' => ImportCron::LOG_TYPE],
            ['action' => Types::STRING],
        );

        $template->products = $this->connection->fetchAllAssociative('SELECT * FROM '.$model->tableSelect.' ORDER BY id');

        return $template->getResponse();
    }
}
