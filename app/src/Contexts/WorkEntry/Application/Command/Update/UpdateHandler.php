<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Update;

use App\Contexts\WorkEntry\Domain\Service;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateHandler
{   
    public function __construct(private Service $service)
    {}
    public function __invoke(UpdateCommand $command): void
    {
        
        $this->service->update(
            id: $command->getEntryId(),
            start_date: $command->getStartDate(),
            end_date: $command->getEndDate()
        ); 
    }
}