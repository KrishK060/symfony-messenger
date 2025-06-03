<?php

namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use App\Message\DeletePhotoFile;
use App\Message\Event\ImagePostDeletedEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DelteImagePostHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    public function __construct(
        MessageBusInterface    $eventBus,
        EntityManagerInterface $entityManager
    )
    {

        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    public function __invoke(DeleteImagePost $message)
    {
        $imagePost = $message->getImagePost();
        $filename = $imagePost->getFilename();


        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $this->eventBus->dispatch(new DeletePhotoFile($filename));

    }
}