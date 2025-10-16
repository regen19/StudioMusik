<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Notification;

class Notify
{
    public static function roles(array $roles, BaseNotification $notification, $exceptUserId = null): void
    {
        $q = User::whereIn('user_role', $roles);
        if ($exceptUserId) $q->where('id_user', '!=', $exceptUserId);
        $targets = $q->get();
        if ($targets->isNotEmpty()) {
            Notification::send($targets, $notification);
        }
    }
}