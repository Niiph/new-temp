<?php

declare(strict_types=1);

namespace App\DTO;

use App\Util\StringLengthUtil;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class ReadingInput
{
    #[Type('numeric')]
    #[NotNull]
    public mixed $value;

    #[Type('string')]
    #[Length(min: StringLengthUtil::MIN, max: StringLengthUtil::TYPE_NAME)]
    #[NotBlank]
    public mixed $type;
}
