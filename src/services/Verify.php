<?php

namespace Kodal\Email2FA\services;

use Craft;
use Kodal\Email2FA\Plugin;
use Kodal\Email2FA\models\VerifyResponse;

/**
 * Class Verify
 * @package Kodal\Email2FA\services
 */
class Verify
{

    // Public Methods
    // =========================================================================

    /**
     * @param $verifyCode
     *
     * @return bool
     * @throws \Exception
     */
    public function verify($verifyCode)
    {
        $verifyResponse = $this->validateAuthResponse($verifyCode);

        if ($verifyResponse->verified) {
            Plugin::$plugin->auth->twoFactorLogin($verifyResponse->hash);

            return true;
        }

        return false;
    }

    /**
     * @param $verifyCodeFromResponse
     *
     * @return VerifyResponse
     * @throws \Exception
     */
    public function validateAuthResponse($verifyCodeFromResponse)
    {
        $hashFromSession = Plugin::$plugin->storage->get('hash');
        $verified        = $this->generateHash($verifyCodeFromResponse) === $hashFromSession;

        return new VerifyResponse($verified, $verifyCodeFromResponse, $hashFromSession);
    }

    /**
     * @return void
     */
    public function triggerAuthChallenge($sendVerifyEmail = true)
    {
        $verifyCode = $this->generateVerifyCode();
        $hash       = $this->generateHash($verifyCode);

        Plugin::$plugin->storage->set('hash', $hash);
        Plugin::$plugin->storage->set('verify', $verifyCode);

        if($sendVerifyEmail) {
            Plugin::$plugin->email->sendVerifyEmail($verifyCode, $hash);
        }
    }

    /**
     *
     */
    public function resendVerifyEmail()
    {
        $verifyCode = Plugin::$plugin->storage->get('verify');
        $hash       = Plugin::$plugin->storage->get('hash');

        if ($verifyCode) {
            Plugin::$plugin->email->sendVerifyEmail($verifyCode, $hash);
        }
    }

    /**
     * @param $array
     *
     * @return string
     */
    public function generateHash($array)
    {
        return md5(json_encode($array));
    }

    /**
     * @return array
     */
    public function generateVerifyCode()
    {
        return array_map(
            function () {
                return (string)random_int(0, 9);
            },
            array_fill(0, Plugin::$plugin->getSettings()->verifyCodeLength, true)
        );
    }

    // Protected Methods
    // =========================================================================
}