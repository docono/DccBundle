<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\CConsent;

abstract class AbstractConsentHandlerDependency
{
    private ConsentHandler $consentHandler;

    public function __construct()
    {
        $this->consentHandler = ConsentHandler::instance();
    }

    public function consentHandler(): ConsentHandler
    {
        return $this->consentHandler;
    }
}
