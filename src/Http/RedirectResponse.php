<?php

namespace Qween\Location\Http;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', self::CODE_REDIRECT, [
            'location' => $url
        ]);
    }

    /**
     * @return void
     */
    public function send(): void
    {
        header("location: " . $this->getHeader('location'), true, $this->getStatusCode());
        exit;
    }
}