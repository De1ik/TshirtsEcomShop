<?php

namespace app\Enums;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case GUEST = 'guest';
}
