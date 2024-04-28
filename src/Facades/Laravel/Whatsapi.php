<?php
namespace Lambq\Ws\Facades\Laravel;

use Illuminate\Support\Facades\Facade;

class Ws extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Lambq\Ws\Contracts\WsInterface';
    }
}