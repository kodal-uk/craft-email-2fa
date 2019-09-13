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

    // Protected Methods
    // =========================================================================
}
