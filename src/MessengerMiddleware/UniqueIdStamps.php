<?php

namespace App\MessengerMiddleware;

use Symfony\Component\Messenger\Stamp\StampInterface;

class UniqueIdStamps implements StampInterface
{
    private $uniqueId;
    public function __construct()
    {
        $this->uniqueId = uniqId();
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}