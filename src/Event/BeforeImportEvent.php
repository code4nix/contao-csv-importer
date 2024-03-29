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

namespace Code4Nix\ContaoCsvImporter\Event;

use Code4Nix\ContaoCsvImporter\Importer\ImporterInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class BeforeImportEvent extends Event
{
    public const NAME = 'before.import';

    public function __construct(
        private readonly ImporterInterface $importer,
    ) {
    }

    public function getImporter(): ImporterInterface
    {
        return $this->importer;
    }
}
