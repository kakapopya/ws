<?php
namespace Lambq\Ws\Facades\Native;

use WhatsProt;
use UnexpectedValueException;
use Lambq\Ws\Media\Media;
use Lambq\Ws\Clients\MGP25;
use Lambq\Ws\MessageManager;
use Lambq\Ws\Events\Listener;
use Lambq\Ws\Sessions\Native\Session;

class Ws extends Facade
{
	use ResourceTrait;

    public static function create()
    {
        if(!$session = static::$session)
        {
            $session = new Session;
        }

        if(!$config = static::$config)
        {
            throw new UnexpectedValueException("You must provide config details in order to use Ws");
        }

        // Setup Account details.
        $debug     = $config["debug"];
        $log       = $config["log"];
        $account   = $config["default"];
        $storage   = $config["data-storage"];
        $nickname  = $config["accounts"][$account]["nickname"];
        $number    = $config["accounts"][$account]["number"];
        $nextChallengeFile = $config["challenge-path"] . "/phone-" . $number . "-next-challenge.dat";

        $whatsProt =  new WhatsProt($number, $nickname, $debug, $log, $storage);
        $whatsProt->setChallengeName($nextChallengeFile);

        $media = new Media($config['media-path']);
        $manager = new MessageManager($media);
        $listener = new Listener($session, $config);

        $Ws = new MGP25($whatsProt, $manager, $listener, $session, $config);

        if($eventListener = static::$listener)
        {
            $Ws->setListener($eventListener);
        }

        return $Ws;
    }
}