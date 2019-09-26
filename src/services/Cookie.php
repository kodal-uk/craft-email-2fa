<?php

namespace Kodal\Email2FA\Services;

use Kodal\Email2FA\Plugin;

class Cookie {

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($this->getStorageKey($key), $_COOKIE);
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->has($key) ? $_COOKIE[$this->getStorageKey($key)] : null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value, $options = [])
    {
        $c = new \yii\web\Cookie($options);
        $c->name = $this->getStorageKey($key);
        $c->value = $value;

        return setcookie($c->name, $c->value, $c->expire, $c->path, $c->domain, $c->secure, $c->httpOnly);
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($_COOKIE[$this->getStorageKey($key)]);
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getStorageKey($key)
    {
        return md5(Plugin::$plugin->getSettings()->sessionHandle).'__'.$key;
    }
}