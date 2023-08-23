<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\CConsent;

use docono\Bundle\DccBundle\CConsent\Youtube\Thumbnail;
use Embera\Embera;
use Kirby\Http\Uri;
use Pimcore\Log\ApplicationLogger;
use Symfony\Contracts\Translation\TranslatorInterface;

class Youtube extends AbstractConsentHandlerDependency
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @param string $url
     * @param string $message
     */
    public function __construct(string $url)
    {
        parent::__construct();

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getVideoId(): string
    {
        $pathSegments = explode('/', parse_url($this->url)['path']);
        return end($pathSegments);
    }

    /**
     * @return string
     */
    public function getBase64Thumbnail(string $thumbnailQuality): string
    {
        return Thumbnail::instance()->getBase64ThumbnailForId($this->getVideoId(), $thumbnailQuality);
    }

    /**
     * @return string
     */
    public function getHtml(string $thumbnailQuality = 'high', array $attr=[]): string
    {
        $classes = 'dcc-youtube';

        if(!empty($attr['class'])) {
            $attr['class'] .=  ' ' . $classes;
        } else {
            $attr['class'] = $classes;
        }

        if ($this->consentHandler()->youtubePermission()) {
            $embera = new Embera();
            return str_replace('https://www.youtube.com', 'https://www.youtube-nocookie.com', $embera->autoEmbed('https://www.youtube.com/watch?v=' . $this->getVideoId()));
        } else {
            try {
                $attributes = join(' ', array_map(function($key) use ($attr){
                    if(is_bool($attr[$key])){
                        return $attr[$key]?$key:'';
                    }
                    return $key.'="'.$attr[$key].'"';
                }, array_keys($attr)));

                $translator = \Pimcore::getContainer()->get(TranslatorInterface::class);

                return '<div data-src="' . $this->url . '" ' . $attributes . '><img class="dcc-youtube__thumbnail" src="' . $this->getBase64Thumbnail($thumbnailQuality) . '" /><div class="dcc-youtube__play"></div><div class="dcc-youtube__consent"><div>' . $translator->trans('dcc.youtube.message') . '</div><div><button class="dcc-button consent-youtube__decline">' . $translator->trans('dcc.youtube.decline'). '</button><button class="dcc-button -selected consent-youtube__accept">' . $translator->trans('dcc.youtube.accept'). '</button></div></div></div>';
            } catch (\Throwable $e) {
                ApplicationLogger::getInstance()->log('dcc failed render youtube placeholder: ' . $e->getMessage());
                return '';
            }
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getHtml();
    }
}
