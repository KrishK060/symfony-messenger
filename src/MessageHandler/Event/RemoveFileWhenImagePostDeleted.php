<?php

namespace App\MessageHandler\Event;

use App\Message\Event\ImagePostDeletedEvent;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveFileWhenImagePostDeleted
{
    /**
     * @var PhotoFileManager
     */
    private $photoFileManager;

    public function __construct(PhotoFileManager $photoFileManager)
    {
        $this->photoFileManager = $photoFileManager;
    }
    public function __invoke(ImagePostDeletedEvent $event): void
    {
        $this->photoFileManager->deleteImage($event->getFilename());
    }
}