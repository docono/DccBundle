<?php

namespace docono\Bundle\DccBundle\CConsent;

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

class Slot extends AbstractConsentHandlerDependency
{
    /**
     * @var array
     */
    private static $slots = [];

    /**
     * @var bool
     */
    private bool $isClosed = false;

    /**
     * @var string
     */
    private $permission;

    /**
     * @var string
     */
    private string $content;

    /**
     * @param string $name
     * @return static
     */
    public static function handler(string $name): self
    {
        if (empty(self::$slots[$name])) {
            self::$slots[$name] = new self();
        }

        return self::$slots[$name];
    }
    /**
     * @return void
     */
    public function start(string $permission = null)
    {
        $this->permission = $permission;

        ob_start();
    }

    /**
     * @param bool $show
     * @return false|string|void
     */
    public function end()
    {
        $this->content = ob_get_contents();
        ob_end_clean();
        $this->isClosed = true;

        if($this->permission && $this->consentHandler()->hasPermission($this->permission)) {
            return $this->content;
        }
    }

    /**
     * @return mixed
     */
    public function content()
    {
        if(!$this->isClosed) {
            throw new Exception('slot has no been closed');
        }

        return $this->content;
    }
}
