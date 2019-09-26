<?php

namespace Kodal\Email2FA\Services;

use Craft;
use Kodal\Email2FA\Plugin;

/**
 * Class Auth
 * @package Kodal\Email2FA\Services
 */
class Auth
{
    // Protected Properties
    // =========================================================================

    /**
     * @var bool|\craft\base\Model|null
     */
    protected $settings;

    /**
     * Auth constructor.
     * @throws \craft\errors\MissingComponentException
     */
    public function __construct()
    {

        $this->settings = Plugin::$plugin->getSettings();
        $this->response = Craft::$app->response;
    }

    // Public Methods
    // =========================================================================

    /**
     * @throws \craft\errors\MissingComponentException
     */
    public function requireLogin()
    {
        if ( ! $this->isLoggedIn()) {
            $this->response->redirect($this->settings->verifyRoute);
        }
    }

    /**
     * @return bool
     * @throws \craft\errors\MissingComponentException
     */
    public function isLoggedIn()
    {
        $auth = Plugin::$plugin->cookie->get('auth');
        $hash = Plugin::$plugin->storage->get('hash');

        return $auth === $hash;
    }

    /**
     * @param $event
     *
     * @return \craft\web\Response|\yii\console\Response
     * @throws \Exception
     */
    public function login($event)
    {
        if (!$this->isLoggedIn()) {
           return $this->triggerAuthChallenge();
        }

        if (!$this->simulateVerification()) {
            return $this->triggerAuthChallenge();
        }
    }

    /**
     * @param $event
     */
    public function logout($event)
    {

    }

    /**
     * @param $hash string
     */
    public function twoFactorLogin(string $hash)
    {
        return Plugin::$plugin->cookie->set('auth', $hash, [
            'expire' => time() + $this->settings->verifyDuration
        ]);
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return \craft\web\Response|\yii\console\Response
     */
    protected function triggerAuthChallenge()
    {
        Plugin::$plugin->verify->triggerAuthChallenge();

        return $this->response->redirect($this->settings->verifyRoute);
    }

    /**
     * @return mixed
     */
    protected function simulateVerification()
    {
        Plugin::$plugin->verify->triggerAuthChallenge(false);

        $verifyCode = Plugin::$plugin->storage->get('verify');

        return Plugin::$plugin->verify->verify($verifyCode);
    }
}