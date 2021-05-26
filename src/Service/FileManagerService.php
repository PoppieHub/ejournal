<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private mixed $userImageDirectory;

    public function __construct($userImageDirectory)
    {
        $this->userImageDirectory = $userImageDirectory;
    }

    /**
     * @return mixed
     */
    public function getUserImageDirectory()
    {
        return $this->userImageDirectory;
    }

    public function imageUserUpload(UploadedFile $file): string
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        try{
           $file->move($this->getUserImageDirectory(), $fileName);
        }
        catch (FileException $exception){
            return $exception;
        }

        return $fileName;
    }

    public function removeUserImage(string $filename)
    {
        $fileSystem = new Filesystem();
        $fileImage = $this->getUserImageDirectory().''.$filename;
        try {
            $fileSystem->remove($fileImage);
        }
        catch (IOExceptionInterface $exception){
            echo $exception->getMessage();
        }
    }

}