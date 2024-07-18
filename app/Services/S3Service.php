<?php
namespace App\Services;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class S3Service
{
    protected $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function getObject($bucket, $key)
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);

            return (string) $result['@metadata']['effectiveUri'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadObject($bucket, $key, $content)
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'Body'   => $content,
                'ContentType' => 'text/html',
                'ACL'    => 'public-read',
            ]);

            return $result['ObjectURL'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteObject($bucket, $key)
    {
        try {
            $result = $this->client->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);

            return response()->json(['message' => 'Object deleted successfully'], 200);
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}