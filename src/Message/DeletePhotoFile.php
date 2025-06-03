<?php
namespace App\Message;

class DeletePhotoFile{
    
    private $filename;
    public function __construct(string $filename){
        $this->filename = $filename;
    }

    public function getFilename(){
        return $this->filename;
    }
}