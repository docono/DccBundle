<?php

/**
 * DOCONO
 *
 * ProffixBundle
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\Templating\Helper;

use docono\Bundle\DccBundle\CConsent;
use Symfony\Component\Templating\Helper\Helper;

class Dcc extends Helper
{
    private CConsent $CConsent;

    public function __construct(CConsent $CConsent)
    {
        $this->CConsent = $CConsent;
    }

    public function getName()
    {
        return 'dcc';
    }
    public function __invoke()
    {
        return  $this->CConsent;
    }
}
