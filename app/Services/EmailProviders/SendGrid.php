<?php

namespace App\Services\EmailProviders;

use App\Services\EmailProviders\EmailProvider;
use App\Models\Author;

class SendGrid extends EmailProvider 
{
    public function addSubscriber(Author $author): array 
    {
        // Simulate using SendGrid API
        return ['status' => 'success', 'message' => 'Author subscribed via SendGrid'];
    }
}