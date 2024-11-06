<?php

namespace docono\Bundle\DccBundle;


use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;

class DccBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public function getJsPaths(): array
    {
        return [

        ];
    }

    public function getCssPaths(): array
    {
        return [

        ];
    }
}
