<?php

namespace Qween\Location\Http\Events;

use Qween\Location\Event\Event;
use Qween\Location\Http\Request;
use Qween\Location\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private Request  $request,
        private Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}