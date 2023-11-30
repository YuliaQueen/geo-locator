<?php

namespace Qween\Location\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Qween\Location\Http\Response;

class IndexController extends AbstractController
{

    /**
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Response
    {
        return $this->render('home', [
            'title' => 'Welcome to HOME'
        ]);
    }
}