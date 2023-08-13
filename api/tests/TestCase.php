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

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\Factory\UserFactory;
use Carbon\CarbonImmutable;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class TestCase extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    private JWTTokenManagerInterface $jwtManager;

    public function __construct(
        ?string                  $name = null,
        array                    $data = [],
                                 $dataName = '',
    )
    {
        parent::__construct($name, $data, $dataName);

        $this->jwtManager = self::getContainer()->get(JWTTokenManagerInterface::class);
        $this->setupTests();
    }

    protected function generateToken(): string
    {
        $user = UserFactory::createOne();

        return $this->jwtManager->create($user->object());
    }

    private function setupTests(): void
    {
        $datetime = '2023-07-01 12:34:56';
        CarbonImmutable::setTestNow(new CarbonImmutable($datetime));
    }
}
