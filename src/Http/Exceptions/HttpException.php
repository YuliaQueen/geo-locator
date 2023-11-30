<?php

namespace Qween\Location\Http\Exceptions;

class HttpException extends \Exception
{
    private int $statusCode = 400;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $status
     */
    public function setStatusCode(int $status): void
    {
        $this->statusCode = $status;
    }
}