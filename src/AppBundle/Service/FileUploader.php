<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 4/11/2016
 * Time: 8:46 AM
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        // Generate a name for the file uploaded
        $fileName = md5(uniqid('', true)).'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }
}