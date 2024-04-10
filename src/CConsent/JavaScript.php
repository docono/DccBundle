<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\CConsent;

class JavaScript extends AbstractConsentHandlerDependency
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @return JavaScript
     */
    public static function handle()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    public function start()
    {
        ob_start();
    }

    /**
     * @return string
     */
    public function end(): string
    {
        $content = ob_get_contents();
        ob_clean();
        ob_end_clean();

        $doc = new \DOMDocument();
        $doc->loadHTML($content);

        /** @var \DOMElement $element */
        $element = &$doc->getElementsByTagName('script')[0];

        if (!$this->consentHandler()->hasPermission($element->getAttribute('data-consent'))) {
            if ($element->hasAttribute('src')) {
                $element->setAttribute('data-src', $element->getAttribute('src'));
                $element->removeAttribute('src');
            } else {
                $element->setAttribute('type', 'text/plain');
            }
        }

        return preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(['<html>', '</html>', '<head>', '</head>', '<body>', '</body>'], '', $doc->saveHTML()));
    }
}
