<?php

namespace Kodal\Email2FA\TwigExtensions;

use Craft;
use Kodal\Email2FA\Services\Auth;


/**
 * Class RequireTwoFactorLogin
 * @package Kodal\Email2FA\TwigExtensions
 */
class RequireTwoFactorLogin extends \Twig\Extension\AbstractExtension
{

    // Private Properties
    // =========================================================================

    /**
     * @var Auth
     */
    private $auth;

    /**
     * RequireTwoFactorLogin constructor.
     */
    public function __construct()
    {
        $this->auth = new Auth();
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'RequireTwoFactorLogin';
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('requireTwoFactorLogin', [$this, 'requireTwoFactorLogin']),
        ];
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return string
     */
    public function requireTwoFactorLogin()
    {
        Craft::$app->controller->requireLogin();
        $this->auth->requireLogin();
    }

    // Protected Methods
    // =========================================================================
}
