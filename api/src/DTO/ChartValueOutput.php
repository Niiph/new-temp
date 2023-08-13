<?php
/**
 * This file is part of the Diagla package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\DTO;

use Carbon\CarbonInterface;

class ChartValueOutput implements OutputInterface
{
    public function __construct(
        public float $y,
        public CarbonInterface $x,
    ) {
    }

    public static function createOutput(mixed $data): self
    {
        return new self(
            $data['value'],
            $data['date'],
        );
    }
}
