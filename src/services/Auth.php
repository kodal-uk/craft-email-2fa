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
        return Plugin::$plugin->session->has('auth');
    }

    /**
     * @param $event
     *
     * @return \craft\web\Response|\yii\console\Response
     * @throws \Exception
     */
    public function login($event)
    {
        if (!$this->recentlyAuthenticated()) {
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
        Plugin::$plugin->session->remove('auth');
    }

    /**
     * @param $hash
     */
    public function twoFactorLogin($hash)
    {
        if ( ! Plugin::$plugin->session->has('last_auth')) {
            Plugin::$plugin->session->set('last_auth', new \DateTime());
        }

        return Plugin::$plugin->session->set('auth', $hash);
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
     * @return bool
     * @throws \Exception
     */
    protected function recentlyAuthenticated()
    {
        if ( ! Plugin::$plugin->session->has('last_auth')) {
            return false;
        }

        $lastAuthDate = Plugin::$plugin->session->get('last_auth');
        $expiresDate  = (clone $lastAuthDate)->add(new \DateInterval('PT'.$this->settings->verifyDuration.'S'));
        $nowDate      = new \DateTime();

        return $expiresDate > $nowDate;
    }

    /**
     * @return mixed
     */
    protected function simulateVerification()
    {
        Plugin::$plugin->verify->triggerAuthChallenge(false);

        $verifyCode = Plugin::$plugin->session->get('verify');

        return Plugin::$plugin->verify->verify($verifyCode);
    }
}