<?php

namespace Kodal\Email2FA;

use Craft;

use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use Kodal\Email2FA\models\Settings;
use Kodal\Email2FA\services\Auth;
use Kodal\Email2FA\services\Email;
use Kodal\Email2FA\services\Session;
use Kodal\Email2FA\services\Verify;
use Kodal\Email2FA\TwigExtensions\RequireTwoFactorLogin;
use Kodal\Email2FA\variables\Variable;
use yii\base\Event;
use craft\web\User;
use craft\events\PluginEvent;
use craft\services\Plugins;
use yii\web\UserEvent;

/**
 * Class Plugin
 * @package Kodal\Email2FA
 */
class Plugin extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Plugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new RequireTwoFactorLogin());

        $this->setComponents([
            'auth'    => Auth::class,
            'verify'  => Verify::class,
            'email'   => Email::class,
            'session' => Session::class,
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('email2fa', Variable::class);
            }
        );

        Event::on(
            User::class,
            User::EVENT_AFTER_LOGIN,
            function (UserEvent $event) {
                $this->auth->login($event);
            }
        );

        Event::on(
            User::class,
            User::EVENT_BEFORE_LOGOUT,
            function (UserEvent $event) {
                $this->auth->logout($event);
            }
        );

        Craft::info(
            Craft::t(
                'email-2fa',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return \craft\base\Model|Settings|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function settingsHtml()
    {
        return Craft::$app->view->renderTemplate(
            'email-2fa/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
