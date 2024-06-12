<?php

namespace Tonitin\PostS3;



trait S3PostSigned
{

    /**
     * Create a signed URL to upload files to the S3 service.
     * @param mixed $disk 
     * @param mixed $path 
     * @param mixed $sizeBytes 
     * @return array 
     */
    public function createPostSigned($disk, $path = null, $sizeBytes = null)
    {

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
