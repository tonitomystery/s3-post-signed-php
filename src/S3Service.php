<?php

namespace Tonitin\PostS3;

use Aws\Credentials\Credentials;
use Aws\S3\PostObjectV4;
use Aws\S3\S3Client;
use Dotenv\Dotenv;

class S3Service
{
    const REGION = 'us-east-1';
    const VERSION = '2006-03-01';
    const VERSION_POLICY = "2012-10-17";
    const VERSION_STS = "2011-06-15";
    public $bucket;
    private $s3Client;

    public function __construct($bucket, $credentials = null)
    {
        if ($credentials == null) {

            $key = getenv('AWS_ACCESS_KEY_ID');
            $secret = getenv('AWS_SECRET_ACCESS_KEY');
            $endpoint = getenv('AWS_ENDPOINT');
            $region = getenv('AWS_DEFAULT_REGION') ?? 'us-east-1';

            $credentials =  new Credentials($key, $secret);
        }

        $this->s3Client = new S3Client([
            'region' => $region,
            'version' => self::VERSION,
            'credentials' => $credentials,
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => true, // minio
        ]);

        $this->bucket = $bucket;
    }


    /**
     * Generates a set of attributes and form entries required to upload a file directly to Amazon S3 using a pre-signed upload.
     * https://github.com/awsdocs/aws-doc-sdk-examples/blob/main/php/example_code/s3/PresignedPost.php#L57C73-L58C4
     * @param string $bucketName The name of the bucket for the upload.
     * @param string $key The key (filename) for the uploaded object.
     * @param int $size The size of the file being uploaded.
     * @return array An array containing `formAttributes` and `formInputs` needed for the HTML form upload.
 
     */
    public function createPostSigned($key, $size)
    {
        $bucketName = $this->bucket;

        $options = [
            ['bucket' => $bucketName],
            ['starts-with', '$key', $key],
            ["content-length-range", 0, $size]
        ];

        $formInputs = [];

        $expires = '+2 hours';

        $postObject = new PostObjectV4(
            $this->s3Client,
            $bucketName,
            $formInputs,
            $options,
            $expires
        );

        $formAttributes = $postObject->getFormAttributes() ?? [];

        $formInputs = $postObject->getFormInputs() ?? [];

        // you need to tell angular the key of the s3 object
        $formInputs['key'] = $key;

        return [
            'formAttributes' => $formAttributes,
            'formInputs' => $formInputs,
        ];
    }


    public function getTemporaryUrl($key, $expiration = null)
    {
        $expirationDefault = '+1 hour';
        $command = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        $request = $this->s3Client->createPresignedRequest($command, $expiration ?? $expirationDefault);

        return (string) $request->getUri();
    }
}
