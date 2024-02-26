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

namespace Code4Nix\ContaoCsvImporter\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormatPriceExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('format_price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($num, int $decimals = 2, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        $num = str_replace(',', '.', (string) $num);

        $num = (float) $num;

        return number_format($num, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
