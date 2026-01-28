<?php

namespace Bhaidar\Checkeeper\Enums;

enum DeliveryMethod: string
{
    case UspsFirstClass = 'first_class';
    case UspsPriority = 'priority';
    case UpsTwoDay = 'two_day';
    case UpsNextDay = 'next_day';
    case FedexTwoDay = 'fedex_two_day';
    case FedexOvernight = 'fedex_overnight';
    case Pdf = 'pdf';
}
