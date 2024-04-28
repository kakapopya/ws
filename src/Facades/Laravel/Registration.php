<?php
namespace Lambq\Ws\Facades\Laravel;

use Illuminate\Support\Facades\Facade;

class Registration extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Lambq\Ws\Contracts\WsToolInterface';
    }
}