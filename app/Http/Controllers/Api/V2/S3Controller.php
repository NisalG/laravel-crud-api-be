<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\S3Service;
use Illuminate\Http\Request;

class S3Controller extends Controller
{
    protected $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }

    public function getObject($bucket, $key)
    {
        $result = $this->s3Service->getObject($bucket, $key);
        return response()->json(['data' => $result]);
    }

    public function uploadObject(Request $request, $bucket, $key)
    {
        $content = $request->input('content');
        $result = $this->s3Service->uploadObject($bucket, $key, $content);
        return response()->json(['data' => $result]);
    }

    public function deleteObject($bucket, $key)
    {
        $result = $this->s3Service->deleteObject($bucket, $key);
        return response()->json(['data' => $result]);
    }
}
