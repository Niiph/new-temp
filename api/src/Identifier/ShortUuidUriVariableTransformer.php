<?php
/**
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Identifier;

use ApiPlatform\Api\UriVariableTransformerInterface;
use App\Util\ShortUuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('api_platform.uri_variables.transformer')]
class ShortUuidUriVariableTransformer implements UriVariableTransformerInterface
{
    public function transform(mixed $value, array $types, array $context = []): UuidInterface
    {
        return ShortUuidGenerator::getFullUuid($value);
    }

    public function supportsTransformation(mixed $value, array $types, array $context = []): bool
    {
        return is_string($value)
            && count($types) > 0
            && !Uuid::isValid($value)
            && is_a($types[0], UuidInterface::class, true);
    }
}
