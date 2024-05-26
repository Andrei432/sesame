<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Register;

use App\Contexts\WorkEntry\Application\Command\Register\RegisterCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


use App\Contexts\WorkEntry\Domain\Service;
use App\Contexts\WorkEntry\Domain\WorkEntry;


#[AsMessageHandler]
class RegisterHandler
{
    public function __construct(private Service $service)
    {
    }

    public function __invoke(RegisterCommand $command): void
    {
        
        $this->service->save(WorkEntry::create(
            user_id: $command->getUserId(),
            start_date: $command->getStartDate(),
            end_date: $command->getEndDate()
        ));

    }
}
