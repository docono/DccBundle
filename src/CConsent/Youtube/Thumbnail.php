<?php

/**
 * DOCONO
 *
 * @author Renzo Mueller <renzo@docono.io>
 * @copyright Copyright (c) DOCONO (https://docono.io)
 */

namespace docono\Bundle\DccBundle\CConsent\Youtube;

use GuzzleHttp\Client;

class Thumbnail
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var
     */
    private $client;

    /**
     * @var string
     */
    private string $youtubeEndpoint = 'https://img.youtube.com/vi/';

    /**
     * @var array|string[]
     */
    private array $qualityImages = [
        'low' => '/sddefault.jpg',
        'medium' => '/mqdefault.jpg',
        'high' => '/hqdefault.jpg',
        'max' => '/maxresdefault.jpg'
    ];

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @param string $youtubeId
     * @param string $quality
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getThumbnailForID(string $youtubeId, string $quality = 'low'): string
    {
        // ensure quality type is valid
        if (empty($this->qualityImages[$quality])) {
            $quality = 'high';
        }

        try {
            $response = $this->getClient()->get($this->youtubeEndpoint . $youtubeId . $this->qualityImages[$quality], ['stream' => true]);

            return $response->getBody()->getContents();
        } catch (\Throwable $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param string $youtubeId
     * @param string $quality
     * @return string
     */
    public function getBase64ThumbnailForId(string $youtubeId, string $quality = 'low'): string
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->getThumbnailForID($youtubeId, $quality));
    }
}
