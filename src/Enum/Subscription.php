<?php

namespace App\Enum;

use Symfony\Component\TypeInfo\Type\EnumType;

class Subscription extends EnumType
{
    public const Basic = 'BASIC';
    public const VIP = 'VIP';
    public const Premium = 'PREMIUM';

    protected static string $name = 'subscription';
}