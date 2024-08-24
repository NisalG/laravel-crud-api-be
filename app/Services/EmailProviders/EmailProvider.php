<?php
namespace App\Services\EmailProviders;

use App\Models\Author;

abstract class EmailProvider 
{
    abstract public function addSubscriber(Author $author): array;
}
