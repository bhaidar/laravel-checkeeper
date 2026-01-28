<?php

namespace Bhaidar\Checkeeper\Enums;

enum CheckStatus: string
{
    case Processing = 'processing';
    case Ready = 'ready';
    case Printed = 'printed';
    case Mailed = 'mailed';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Returned = 'returned';
}
