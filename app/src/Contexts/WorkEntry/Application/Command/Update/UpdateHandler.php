<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Update;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateHandler
{
    public function __invoke(UpdateCommand $command): void
    {
        // TODO: 
    }
}