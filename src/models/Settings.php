<?php

namespace Kodal\Email2FA\models;

use craft\base\Model;


class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public $verifyRoute = '/verify';

    /**
     * @var int
     */
    public $verifyCodeLength = 6;

    /**
     * @var string
     */
    public $verifyDuration = 604800;

    /**
     * @var string
     */
    public $emailTemplate;

    /**
     * @var
     */
    public $sessionHandle = 'email-2fa';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['verifyRoute', 'verifyCodeLength', 'verifyDuration', 'sessionHandle'], 'required'],
            ['verifyRoute', 'default', 'value' => '/verify'],
            ['verifyDuration', 'default', 'value' => 604800],
            ['verifyCodeLength', 'default', 'value' => 6],
            ['sessionHandle', 'default', 'value' => 'email-2fa']
        ];
    }
}
