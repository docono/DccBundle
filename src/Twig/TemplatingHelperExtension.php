<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\Twig;

use docono\Bundle\DccBundle\Templating\Helper\Dcc;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplatingHelperExtension extends AbstractExtension
{
    private Dcc $dcc;

    public function __construct(
        Dcc $dcc
    )
    {
        $this->dcc = $dcc;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('dcc', $this->dcc),
        ];
    }

    public function getFilters()
    {
        return [
        ];
    }
}
