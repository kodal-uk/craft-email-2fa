<?php

namespace Kodal\Email2FA\services;

use Craft;
use craft\web\View;
use Kodal\Email2FA\Plugin;
use craft\mail\Message;

/**
 * Class Email
 * @package Kodal\Email2FA\services
 */
class Email
{
    // Protected Properties
    // =========================================================================

    /**
     * @var bool|\craft\base\Model|null
     */
    protected $settings;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->settings = Plugin::$plugin->getSettings();
    }

    // Public Methods
    // =========================================================================

    /**
     * @param $verifyCode
     */
    public function sendVerifyEmail($verifyCode, $hash)
    {
        $settings = Craft::$app->projectConfig->get('email');
        $identity = Craft::$app->getUser()->getIdentity();

        $verifyLink = $this->generateVerifyLink($verifyCode, $hash);
        $html = $this->generateEmailHtml(['verifyLink' => $verifyLink]);

        $message = new Message();
        $message->setFrom([$settings['fromEmail'] => $settings['fromName']]);
        $message->setSubject(Craft::t('email-2fa', 'Verify login attempt'));
        $message->setHtmlBody($html);
        $message->setTo($identity->email);

        Craft::$app->mailer->send($message);
    }

    /**
     * @param $verifyCode
     * @param $hash
     *
     * @return string
     * @throws \craft\errors\SiteNotFoundException
     */
    public function generateVerifyLink($verifyCode, $hash)
    {
        $url = Craft::$app->sites->getCurrentSite()->getBaseUrl();
        $code = implode('', $verifyCode);

        return "<a href=\"{$url}actions/email-2fa/verify/hash?v={$hash}\">{$code}</a>";
    }

    /**
     * @param array $settings
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function generateEmailHtml($params =[])
    {
        if(!$this->settings->emailTemplate) {
            $templateMode = Craft::$app->view->getTemplateMode();
            Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);
        }

        $html = Craft::$app->view->renderTemplate(
            $this->settings->emailTemplate ? $this->settings->emailTemplate : 'email-2fa/verify-email',
            $params
        );

        if(!$this->settings->emailTemplate) {
            Craft::$app->view->setTemplateMode($templateMode);
        }

        return $html;
    }

    // Protected Methods
    // =========================================================================
}