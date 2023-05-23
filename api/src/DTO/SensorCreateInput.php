<?php
/*
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\DTO;

use App\Entity\DeviceInterface;
use App\Validator\ObjectOwner;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class SensorCreateInput
{
    #[Type('string')]
    #[NotBlank]
    public mixed $name;

    #[Type('string')]
    #[Sequentially([
        new Uuid(),
        new ObjectOwner(DeviceInterface::class),
    ])]
    #[NotBlank]
    public mixed $deviceId;
}
