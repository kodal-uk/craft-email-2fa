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
     * @var
     */
    public $hash;

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
     * @param $hash
     *
     * @throws \Exception
     */
    public function __construct($verified, $code, $hash)
    {
        $this->verified  = $verified;
        $this->code      = $code;
        $this->hash      = $hash;
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