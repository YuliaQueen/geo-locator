<?php

namespace Qween\Location\Http;

class Response
{
    const CODE_REDIRECT = 302;
    const CODE_SUCCESS = 200;

    /**
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(
        private string $content = '',
        private int    $statusCode = self::CODE_SUCCESS,
        private array  $headers = []
    )
    {
        http_response_code($this->statusCode);
    }

    /**
     * @return void
     */
    public function send()
    {
        ob_start();

        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        echo $this->content;

        ob_end_flush();
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $string
     * @return string
     */
    public function getHeader(string $string): string
    {
        return $this->headers[$string];
    }

    /**
     * @param string $key
     * @param int $value
     * @return void
     */
    public function setHeader(string $key, int $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param string $content
     * @return Response
     */
    public function setContent(string $content): Response
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}