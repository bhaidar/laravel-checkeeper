<?php

namespace Bhaidar\Checkeeper\Enums;

enum SignerType: string
{
    case Text = 'text';
    case Png = 'png';
    case Gif = 'gif';
    case Jpg = 'jpg';
}
