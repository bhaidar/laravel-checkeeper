<?php

namespace Bhaidar\Checkeeper\Facades;

use Bhaidar\Checkeeper\Resources\CheckResource;
use Bhaidar\Checkeeper\Resources\TeamResource;
use Bhaidar\Checkeeper\Resources\TemplateResource;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CheckResource checks()
 * @method static TeamResource team()
 * @method static TemplateResource templates()
 */
class Checkeeper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'checkeeper';
    }
}
