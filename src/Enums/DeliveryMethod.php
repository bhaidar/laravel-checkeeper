<?php

namespace Bhaidar\Checkeeper\Enums;

enum DeliveryMethod: string
{
    case UspsFirstClass = 'usps.first_class';
    case UspsPriority = 'usps.priority';
    case UpsTwoDay = 'ups.two_day';
    case UpsNextDay = 'ups.next_day';
    case FedexTwoDay = 'fedex.two_day';
    case FedexOvernight = 'fedex.overnight';
    case Pdf = 'pdf';
}
