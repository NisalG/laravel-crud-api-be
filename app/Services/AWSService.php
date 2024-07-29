<?php
namespace App\Services;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Aws\Ssm\SsmClient;
use Aws\Ses\SesClient;
use Aws\Sqs\SqsClient;

class AWSService
{
    protected $s3Client;
    protected $ssmClient;
    protected $sesClient;
    protected $sqsClient;
    
    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->ssmClient = new SsmClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->sesClient = new SesClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->sqsClient = new SqsClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->setEnvironmentVariables(); // environment variables are set up as soon as the service is loaded

    }

    public function getS3Object($bucket, $key)
    {
        try {
            $result = $this->s3Client->getObject([
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);

            return (string) $result['@metadata']['effectiveUri'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadS3Object($bucket, $key, $content)
    {
        try {
            $result = $this->s3Client->putObject([
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

    public function deleteS3Object($bucket, $key)
    {
        try {
            $result = $this->s3Client->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);

            return response()->json(['message' => 'Object deleted successfully'], 200);
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSSMParameter($name)
    {
        try {
            $result = $this->ssmClient->getParameter([
                'Name' => $name,
                'WithDecryption' => true,
            ]);

            return $result['Parameter']['Value'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function setEnvironmentVariables()
    {
        $parameterNames = [
            '/crud-be/staging/DB_CONNECTION',
            '/crud-be/staging/DB_HOST',
            '/crud-be/staging/DB_PORT',
            '/crud-be/staging/DB_DATABASE',
            '/crud-be/staging/DB_USERNAME',
            '/crud-be/staging/DB_PASSWORD',
            '/crud-be/staging/MAIL_USERNAME',
            '/crud-be/staging/MAIL_PASSWORD',
            '/crud-be/prd/DB_CONNECTION',
            '/crud-be/prd/DB_HOST',
            '/crud-be/prd/DB_PORT',
            '/crud-be/prd/DB_DATABASE',
            '/crud-be/prd/DB_USERNAME',
            '/crud-be/prd/DB_PASSWORD',
            '/crud-be/prd/MAIL_USERNAME',
            '/crud-be/prd/MAIL_PASSWORD'
        ];

        // Fetch the parameters in batches
        $parameters = $this->getSSMParametersInBatches($parameterNames);

        foreach ($parameters as $parameter) {
            $name = $parameter['Name'];
            $value = $parameter['Value'];
            $parts = explode("/", $name);
            $lastPart = end($parts);
            $_ENV[$lastPart] = $value;
        }
    }

    private function getSSMParametersInBatches($parameterNames)
    {
        $results = [];
        $chunks = array_chunk($parameterNames, 10);
        foreach ($chunks as $chunk) {
            try {
                $result = $this->ssmClient->getParameters([
                    'Names' => $chunk,
                    'WithDecryption' => true
                ]);
                $results = array_merge($results, $result['Parameters']);
            } catch (AwsException $e) {
                // Handle the error
                echo $e->getMessage() . PHP_EOL;
            }
        }
        return $results;
    }

    public function sendEmail($to, $subject, $body)
    {
        try {
            $result = $this->sesClient->sendEmail([
                'Source' => env('SES_SOURCE_EMAIL'),
                'Destination' => [
                    'ToAddresses' => [$to],
                ],
                'Message' => [
                    'Subject' => [
                        'Data' => $subject,
                        'Charset' => 'UTF-8',
                    ],
                    'Body' => [
                        'Text' => [
                            'Data' => $body,
                            'Charset' => 'UTF-8',
                        ],
                    ],
                ],
            ]);

            return $result['MessageId'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendMessageToSQSQueue($queueUrl, $messageBody)
    {
        try {
            $result = $this->sqsClient->sendMessage([
                'QueueUrl' => $queueUrl,
                'MessageBody' => $messageBody,
            ]);

            return $result['MessageId'];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receiveMessagesFromSQSQueue($queueUrl, $maxMessages = 1, $waitTimeSeconds = 0)
    {
        try {
            $result = $this->sqsClient->receiveMessage([
                'QueueUrl' => $queueUrl,
                'MaxNumberOfMessages' => $maxMessages,
                'WaitTimeSeconds' => $waitTimeSeconds,
            ]);

            return $result['Messages'] ?? [];
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}