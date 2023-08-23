<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\CConsent;

final class ConsentHandler
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var bool
     */
    private bool $cookieHasBeenChecked = false;

    /**
     * @var bool
     */
    private bool $hasAccepted = false;
    /**
     * @var array
     */
    private array $permissions = [];

    /**
     * @return static
     */
    public static function instance(): self
    {
        if(!self::$instance) {
            self::$instance = new self();
            // trigger cookie check
            self::$instance->hasAccepted();
        }

        return self::$instance;
    }

    /**
     * @param string $name
     * @param array $args
     * @return void
     */
    public function __call(string $name, array $args=null)
    {
        if(str_ends_with($name, 'Permission'))
        {
            return $this->hasPermission(strtolower(substr($name, 0, -10)));
        }
    }

    /**
     * @return bool
     */
    public function hasAccepted()
    {
        if (!$this->cookieHasBeenChecked) {
            try {
                if (isset($_COOKIE['dcc'])) {
                    $this->hasAccepted = true;

                    $this->permissions = json_decode(base64_decode($_COOKIE['dcc']));
                }

                $this->cookieHasBeenChecked = true;
            } catch (\Throwable $e) {
            }
        }

        return $this->hasAccepted;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPermission(string $name): bool
    {
        return $this->hasAccepted() && in_array($name, $this->permissions);
    }

    /**
     * @return array
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    /**
     * @return array
     */
    public function permissionList(): array
    {
        return \Pimcore::getContainer()->getParameter('dcc.consents');
    }

}
