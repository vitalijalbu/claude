<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case ADVERTISER = 'advertiser';
    case SUPER_ADMIN = 'super_admin';
}
