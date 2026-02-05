<?php

namespace Bhaidar\Checkeeper\Enums;

enum CheckStatus: string
{
    case Processing = 'processing';
    case Ready = 'ready';
    case Printed = 'printed';
    case Mailed = 'mailed';
    case PreTransit = 'pre_transit';
    case Transit = 'transit';
    case Delivery = 'delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Returned = 'returned';
    case PdfReturned = 'pdf returned';
}
