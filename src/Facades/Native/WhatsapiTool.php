<?php
namespace Lambq\Ws\Facades\Native;

use UnexpectedValueException;
use Lambq\Ws\Tools\MGP25;
use Lambq\Ws\Events\Listener;
use Lambq\Ws\Sessions\Native\Session;

class WsTool extends Facade
{
	use ResourceTrait;

    protected static function create()
    {
        if(!$config = static::$config)
        {
            throw new UnexpectedValueException("You must provide config details in order to use Ws Registration Tool");
        }

        $session = new Session;

        $listener = new Listener($session, $config);

        $Ws = new MGP25($listener, $config['debug'], $config["data-storage"]);

        if($eventListener = static::$listener)
        {
            $Ws->setListener($eventListener);
        }

        return $Ws;
    }
}