<?php
namespace App\MessageHandler\command;

use App\Message\command\DeletePhotoFile;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeletePhotoFileHandler implements MessageHandlerInterface{

    private $photoManager;
    public function __construct(PhotoFileManager $photoManager){
        $this->photoManager = $photoManager;
    }
    public function __invoke(DeletePhotoFile $deletePhotoFile){
        $this->photoManager->deleteImage($deletePhotoFile->getFilename());
    }
}