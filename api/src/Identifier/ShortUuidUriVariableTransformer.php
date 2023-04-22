<?php

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

    public function transform(mixed $value, array $types, array $context = [])
    {
        return ShortUuidGenerator::getFullUuid($value);
    }

    public function supportsTransformation(mixed $value, array $types, array $context = []): bool
    {
        return is_string($value)
            && array_count_values($types) > 0
            && !Uuid::isValid($value)
            && is_a($types[0], UuidInterface::class, true);
    }
}
