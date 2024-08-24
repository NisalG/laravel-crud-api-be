<?php

namespace App\Services\EmailProviders;

use App\Services\EmailProviders\EmailProvider;
use App\Models\Author;

class MailChimp extends EmailProvider 
{
    public function addSubscriber(Author $author): array 
    { 
        // Simulate using MailChimp API
        return ['status' => 'success', 'message' => 'Author subscribed via MailChimp'];
    }
}