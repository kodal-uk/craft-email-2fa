<?php
/**
 * email two-factor authentication config.php
 *
 * This file exists only as a template for the email two-factor authentication settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'email-2fa.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [

    /**
     * The route to the page with contains the verify form, see documentation for form markup example.
     */
    "verifyRoute" => '/verify',

    /**
     * Twig template file used for the verify email. Template must include <code>{{ verifyLink | raw }}</code>
     */
    "emailTemplate" => '',

    /**
     * How may characters should the verify code contain?
     */
    'verifyCodeLength' => 6,

    /**
     * In seconds, how long after successfully completing two-factor authentication until the users is required to re-authenticate?
     */
    "verifyDuration" => 604800,

    /**
     * Set the authentication session handle, changing this value will invalidate all current authentication states.
     */
    "sessionHandle" => 'email-2fa'
];
