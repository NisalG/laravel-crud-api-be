<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Post;
use App\Models\User;

// Here's already an example channel defined for us by default
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// More information: https://laravel.com/docs/master/broadcasting#authorizing-channels
// The user will have already been authenticated by Laravel. In the
// below callback, we can check whether the user is _authorized_ to
// subscribe to the channel.
Broadcast::channel('user.{userId}', function ($user, $userId) {
    info('Log from routes\channels.php >> user auth', ['User\'s user_id' => $user ? $user->id : null, 'userId' => $userId]);
    // return $user->id === $userId;
    return true; // Allow all users for testing purposes
});


Broadcast::channel('PostCreatedPrivateChannel.{postId}', function (User $user, int $postId) {
    info('Log from routes\channels.php >> PostCreated.PrivateChannel', ['User\'s user_id' => $user ? $user->id : null, 'Post\'s user_id' => Post::findOrNew($postId)->user_id]);
    // return $user->id === Post::findOrNew($postId)->user_id;
    return true; // Allow all users for testing purposes
});

