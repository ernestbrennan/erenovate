<?php

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Chat.Presence.{id}', function ($user, $id) {
    return ['id' => $user->userId, 'name' => $user->firstname];
});