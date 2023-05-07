<?php

declare(strict_types=1);

namespace App\DTO;

use App\Util\StringLengthUtil;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Type;

class RegisterUserInput
{
    #[Type('string')]
    #[Email]
    #[Length(max: StringLengthUtil::MAX)]
    #[NotBlank]
    public mixed $username;

    #[Type('string')]
    #[Length(min: StringLengthUtil::PASSWORD_MIN, max: StringLengthUtil::PASSWORD_MAX)]
    #[NotCompromisedPassword]
    public mixed $password;
}
