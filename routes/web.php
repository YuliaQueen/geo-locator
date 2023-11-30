<?php

use Qween\Location\Routing\Route;

return [
    Route::get('/', [\Qween\Location\Controller\IndexController::class, 'index']),
];