<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\AWSService;
use Illuminate\Http\Request;

class AWSController extends Controller
{
    protected $awsService;

    public function __construct(AWSService $awsService)
    {
        $this->awsService = $awsService;
    }

    public function getS3Object($bucket, $key)
    {
        $result = $this->awsService->getS3Object($bucket, $key);
        return response()->json(['data' => $result]);
    }

    public function uploadS3Object(Request $request, $bucket, $key)
    {
        $content = $request->input('content');
        $result = $this->awsService->uploadS3Object($bucket, $key, $content);
        return response()->json(['data' => $result]);
    }

    public function deleteS3Object($bucket, $key)
    {
        $result = $this->awsService->deleteS3Object($bucket, $key);
        return response()->json(['data' => $result]);
    }

    public function getApiKeyFromSSM()
    {
        $apiKey = $this->awsService->getSSMParameter('THE_API_KEY');
        return response()->json(['the_api_key' => $apiKey]);
    }

    public function sendSESTestEmail(Request $request)
    {
        $to = $request->input('to');
        $subject = $request->input('subject');
        $body = $request->input('body');

        $messageId = $this->awsService->sendEmail($to, $subject, $body);

        if ($messageId) {
            return response()->json(['message' => 'Email sent successfully', 'message_id' => $messageId], 200);
        } else {
            return response()->json(['error' => 'Failed to send email'], 500);
        }
    }

    public function sendSQSMessage(Request $request)
    {
        $queueUrl = $request->input('queue_url');
        $messageBody = $request->input('message_body');

        $messageId = $this->awsService->sendMessageToSQSQueue($queueUrl, $messageBody);

        if ($messageId) {
            return response()->json(['message' => 'Message sent successfully', 'message_id' => $messageId], 200);
        } else {
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    public function receiveSQSMessages(Request $request)
    {
        $queueUrl = $request->input('queue_url');
        $maxMessages = $request->input('max_messages', 1);
        $waitTimeSeconds = $request->input('wait_time_seconds', 0);

        $messages = $this->awsService->receiveMessagesFromSQSQueue($queueUrl, $maxMessages, $waitTimeSeconds);

        return response()->json(['messages' => $messages], 200);
    }
}
