<?php

namespace Tonitin\PostS3;

use Exception;


trait S3PostSigned
{
    public function createPostSigned($disk, $path = null, $sizeBytes = null)
    {


        // if ($path == null && !function_exists('getStoragePath')) {
        //     throw new Exception("Implements getPath");
        // }



        // if (!function_exists('getSizeFile')) {
        //     abort(500, 'Implements getSizeFile');
        // }


        $path = $path ?? $this->getStoragePath();
        // $sizeBytes = $sizeBytes ?? $this->getSizeFile();

        $s3Service = new S3Service($disk);
        $postSigned = $s3Service->createPostSigned($path, $sizeBytes);
        $this->postSigned = $postSigned;
        return $postSigned;
    }


    protected function getStoragePath()
    {
        return $this->path;
    }
}
