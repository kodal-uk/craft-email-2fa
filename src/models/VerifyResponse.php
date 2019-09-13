<?php

namespace Kodal\Email2FA\models;

class VerifyResponse
{
    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $verified;

    /**
     * @var array
     */
    public $code;

    /**
     * @var \DateTime
     */
    public $timestamp;

    // Public Methods
    // =========================================================================

    /**
     * VerifyResponse constructor.
     *
     * @param $verified
     * @param $code
     *
     * @throws \Exception
     */
    public function __construct($verified, $code)
    {
        $this->verified  = $verified;
        $this->code      = $code;
        $this->timestamp = new \DateTime();
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}