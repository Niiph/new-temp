<?php

declare(strict_types=1);

namespace App\Util;

use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\UuidInterface;

final class ShortUuidGenerator
{
    private const ALPHABET = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M',
        'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ];
    private function __construct()
    {
    }

    public static function getShortUuid(UuidInterface $uuid): string
    {
        $shortUuid = new ShortUuid(self::ALPHABET);
        return $shortUuid->encode($uuid);
    }

    public static function getFullUuid(string $uuid): UuidInterface
    {
        $shortUuid = new ShortUuid(self::ALPHABET);
        return $shortUuid->decode($uuid);
    }
}
