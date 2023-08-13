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

class SensorReadingsOutput implements OutputInterface
{
    public function __construct(
        public string $name,
        public array $data,
    ) {
    }

    /** @param string $data */
    public static function createOutput(mixed $data, array $values = []): ?self
    {
        if (!$data) {
            return null;
        }

        return new self(
            $data,
            $values
        );
    }
}
