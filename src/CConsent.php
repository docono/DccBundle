<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle;

use docono\Bundle\DccBundle\CConsent\AbstractConsentHandlerDependency;
use docono\Bundle\DccBundle\CConsent\ConsentHandler;
use docono\Bundle\DccBundle\CConsent\JavaScript;
use docono\Bundle\DccBundle\CConsent\Slot;
use docono\Bundle\DccBundle\CConsent\Youtube;

class CConsent extends AbstractConsentHandlerDependency
{
    /**
     * @var
     */
    private $dialogSlot;

    /**
     * @param string $name
     * @param array $args
     * @return bool|void
     */
    public function __call(string $name, array $args)
    {
        if (\str_ends_with($name, 'Permission')) {
            return $this->consentHandler()->$name();
        }
    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->dialogSlot = new Slot();
        $this->dialogSlot->start();
    }

    /**
     * @return mixed
     */
    public function endDialog()
    {
        $this->dialogSlot->end();

        if ($this->handler()->hasAccepted()) {
            return '';
        } else {
            return $this->dialogSlot->content();
        }
    }

    public function slot(string $name): Slot
    {
        return Slot::handler($name);
    }

    /**
     * @return ConsentHandler
     */
    public function handler(): ConsentHandler
    {
        return $this->consentHandler();
    }

    /**
     * @return array
     */
    public function permissionList(): array
    {
        return $this->consentHandler()->permissionList();
    }

    /**
     * @return string
     */
    public function jsFile(): string
    {
        return '/bundles/dcc/js/dcc.js';
    }

    public function jsScript(): string
    {
        return '<script type="text/javascript">' . file_get_contents(__DIR__ . '/Resources/public/js/dcc.js') . '</script>';
    }

    /**
     * @return string
     */
    public function cssFile(): string
    {
        return '/bundles/dcc/css/dcc.css';
    }

    /**
     * @return JavaScript
     */
    public function js(): JavaScript
    {
        return JavaScript::handle();
    }

    /**
     * @param string $url
     * @param string $message
     * @return Youtubee
     */
    public function youtube(string $url): Youtube
    {
        return new Youtube($url);
    }
}
