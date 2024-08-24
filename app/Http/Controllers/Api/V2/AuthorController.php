<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Controller;
use App\Services\EmailProviders\EmailProvider;

class AuthorController extends Controller
{
    public function subscribeToMailList(Request $request, EmailProvider $emailProvider)
    {
        try {
            $author = Author::create($request->all());
            $result = $emailProvider->addSubscriber($author);

            if ($result['status'] !== 'success') {
                throw new \Exception($result['message']);
            }
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to subscribe Author");
        }

        return response()->json($author, 201);
    }
}
