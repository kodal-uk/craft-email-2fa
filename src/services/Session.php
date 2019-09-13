<?php

namespace Kodal\Email2FA\services;

use Craft;
use Kodal\Email2FA\Plugin;

/**
 * Class Session
 * @package Kodal\Email2FA\services
 */
class Session
{

    // Protected Properties
    // =========================================================================

    /**
     * @var \craft\web\Session|void
     */
    protected $session;

    /**
     * @var string
     */
    protected $sessionHandle;

    /**
     * Session constructor.
     * @throws \craft\errors\MissingComponentException
     */
    public function __construct()
    {
        $this->session = Craft::$app->getSession();
        $this->sessionHandle = Plugin::$plugin->getSettings()->sessionHandle;
    }

    // Public Methods
    // =========================================================================

    /**
     * @return bool
     */
    public function has($key)
    {
        return $this->session->has($this->getHandle($key));
    }

    /**
     * @return mixed
     */
    public function get($key)
    {
        return $this->session->get($this->getHandle($key));
    }

    /**
     * @param $hash
     */
    public function set($key, $value)
    {
        $this->session->set($this->getHandle($key), $value);
    }

    /**
     *
     */
    public function remove($key)
    {
        $this->session->remove($this->getHandle($key));
    }

    // Protected Methods
    // =========================================================================

    /**
     * @param $key
     *
     * @return string
     */
    protected function getHandle($key)
    {
        return "{$this->sessionHandle}_{$key}";
    }
}