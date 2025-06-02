<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use App\Repository\ImagePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddPonkaToImageHandler implements MessageHandlerInterface
{
    use LoggerAwareTrait;

    /**
     * @var PhotoFileManager
     */
    private $photoManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PhotoPonkaficator
     */
    private $ponkaficator;
    /**
     * @var ImagePostRepository
     */
    private $imagePostRepository;


    public function __construct(
        PhotoPonkaficator      $ponkaficator,
        PhotoFileManager       $photoManager,
        EntityManagerInterface $entityManager,
        ImagePostRepository    $imagePostRepository
    )
    {
        $this->photoManager = $photoManager;
        $this->entityManager = $entityManager;
        $this->ponkaficator = $ponkaficator;
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(
        AddPonkaToImage $message
    )
    {
        $imagePostId = $message->getImagePostId();
        $imagePost = $this->imagePostRepository->find($imagePostId);

        if (!$imagePost) {
            if ($this->logger) {
                $this->logger->error('Image post not found');
            }
            return;
        }

        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($imagePost->getFilename())
        );
        $this->photoManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();
        $this->entityManager->flush();
    }
}
