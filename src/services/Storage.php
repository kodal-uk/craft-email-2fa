<?php

namespace Kodal\Email2FA\Services;

use craft\helpers\FileHelper;

class Storage
{
    /**
     * @var string
     */
    protected $storagePath;

    /**
     * Storage constructor.
     * @throws \yii\base\Exception
     */
    public function __construct()
    {
        $this->storagePath = \Craft::$app->path->getStoragePath() . DIRECTORY_SEPARATOR . 'email-2fa' . DIRECTORY_SEPARATOR;

        FileHelper::createDirectory($this->storagePath);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key) {
        return file_exists($this->getFilePath($key));
    }

    /**
     * @param $key
     *
     * @return false|string
     */
    public function get($key) {
        if($this->has($key)) {
            $data = file_get_contents($this->getFilePath($key));

            return unserialize($data);
        }

        return false;
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws \yii\base\ErrorException
     */
    public function set($key, $value) {
        FileHelper::writeToFile($this->getFilePath($key), serialize($value));
    }

    /**
     * @param $key
     */
    public function remove($key) {
        FileHelper::unlink($this->getFilePath($key));
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getFilePath($key) {
        $user = \Craft::$app->getUser();

        return $this->storagePath.md5($user->getId().$key);
    }
}