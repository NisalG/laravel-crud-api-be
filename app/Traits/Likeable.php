<?php

namespace App\Traits;

trait Likeable
{
    public function like(): void
    {
        // Sample logic for liking a post or comment
        $this->likes_count = $this->likes_count + 1;
        $this->save();
    }

    public function dislike(): void
    {
        // Sample logic for disliking a post or comment
        $this->dislikes_count = $this->dislikes_count + 1;
        $this->save();
    }
}
