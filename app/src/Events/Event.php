<?php declare (strict_types=1);

namespace App\Events;

use Symfony\Component\Uid\Uuid;
use Psr\Log\LoggerInterface;

# Base class for all events. 
class Event {

    private string $action;

    # set by concrete classes. 
    private function __construct(
        private ?Uuid $user_id = null,
        private ?Uuid $work_entry_id = null,
    )
    {   
        $this->action = static::class; # class name provides a description.
    }

    public static function create(?Uuid $user_id=null, ?Uuid $work_entry_id=null) {
        return new static(user_id:$user_id, work_entry_id:$work_entry_id);
    }

    public function getUserId(): ?Uuid {
        return $this->user_id;
    }   

    public function getWorkEntryId(): ?Uuid {
        return $this->work_entry_id;
    }

    public function getAction(): string {
        return $this->action;
    }

}

            