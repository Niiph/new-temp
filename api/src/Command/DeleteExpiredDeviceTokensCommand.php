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

namespace App\Command;

use App\Repository\DeviceTokenRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:cancel_expired_device_tokens')]
class DeleteExpiredDeviceTokensCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly DeviceTokenRepositoryInterface $deviceTokenRepository,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln('Command is already running in another process.');

            return self::FAILURE;
        }

        $this->deviceTokenRepository->getExpiredDeviceTokens()->delete()->getQuery()->execute();

        return self::SUCCESS;
    }
}
