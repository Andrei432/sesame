<?php declare (strict_types=1);

namespace App\Events;

use Symfony\Component\Uid\Uuid;

# Base class for all events. 
class Event {

    # set by concrete classes. 
    private function __construct(
        private string $action, 
        private ?Uuid $user_id = null,
        private ?Uuid $work_entry_id = null,
    )
    {
        $this->action = get_class($this); # The name of subclasses already provides a description. 
    }

    public static function create(?Uuid $user_id, ?Uuid $work_entry_id, string $action) {
        return new static(action:$action, user_id:$user_id, work_entry_id:$work_entry_id);
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

            