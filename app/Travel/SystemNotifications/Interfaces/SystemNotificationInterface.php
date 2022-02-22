<?php

namespace App\Travel\SystemNotifications\Interfaces;


interface SystemNotificationInterface
{
    public static function getType(): string;
}