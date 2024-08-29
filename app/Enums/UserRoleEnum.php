<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case SUBSCRIBER = 'subscriber';
    case ADMIN = 'admin';
}
