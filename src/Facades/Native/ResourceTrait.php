<?php
namespace Lambq\Ws\Facades\Native;

use Lambq\Ws\Sessions\SessionInterface;
use Lambq\Ws\Contracts\ListenerInterface;

trait ResourceTrait
{
    /**
     * SessionInterface implementation
     *
     * @var Lambq\Ws\Sessions\SessionInterface
     */
	protected static $session;

	/**
     * ListenerInterface implementation
     *
     * @var \Lambq\Ws\Contracts\ListenerInterface
     */
	protected static $listener;

	/**
	 * Config values
	 *
	 * @var array
	 */
	protected static $config;

    /**
     * Sets the session manager
     *
     * @param \Lambq\Ws\Sessions\SessionInterface $session
     */
    public static function setSessionManager(SessionInterface $session)
    {
        static::$session = $session;
    }

    /**
     * Sets the event listener
     *
     * @param Lambq\Ws\Contracts\ListenerInterface $listener
     */
    public static function setEventListener(ListenerInterface $listener)
    {
    	static::$listener = $listener;
    }

    /**
     * Sets the config to use. See the example config file in Config/config.php
     *
     * @param array $config
     */
    public static function setConfig(array $config)
    {
    	static::$config = $config;
    }
}