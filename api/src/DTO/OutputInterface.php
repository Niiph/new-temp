<?php

declare(strict_types=1);

namespace App\DTO;

interface OutputInterface
{
    public static function createOutput(mixed $data): ?self;
}
