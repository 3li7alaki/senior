<?php

namespace App\Enums;


enum UsersTypes: string
{
    case SUPERADMIN = 'super_admin';
    case ADMIN = 'admin';
    case GUARDIAN = 'guardian';
}
