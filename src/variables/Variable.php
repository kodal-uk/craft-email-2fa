<?php

namespace Kodal\Email2FA\variables;

use Kodal\Email2FA\Plugin;
use Craft;


/**
 * Class Variable
 * @package Kodal\Email2FA\variables
 */
class Variable
{
    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function verifyCodeLength()
    {
        return Plugin::$plugin->getSettings()->verifyCodeLength;
    }

    /**
     * @return mixed
     */
    public function isVerified()
    {
        return Plugin::$plugin->auth->isLoggedIn();
    }

    // Protected Methods
    // =========================================================================
}
