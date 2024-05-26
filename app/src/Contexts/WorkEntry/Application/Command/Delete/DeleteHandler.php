<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Delete;

use App\Contexts\WorkEntry\Domain\Service;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteHandler
{
    public function __construct(private Service $service) {
    }
    public function __invoke(DeleteCommand $command): void
    {
        $this->service->delete(entry_id: $command->getEntryId());
    }
}
