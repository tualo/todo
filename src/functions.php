<?php

require_once "Middlewares/Middleware.php";
require_once "Routes/Route.php";
require_once "Routes/ListRoute.php";
require_once "Routes/ItemRoute.php";
require_once "Routes/TagRoute.php";

use Tualo\Office\ToDo\Middlewares\Middleware;
use Tualo\Office\ToDo\Routes\ListRoute;
use Tualo\Office\ToDo\Routes\ItemRoute;
use Tualo\Office\ToDo\Routes\TagRoute;

Middleware::register();
ListRoute::register();
ItemRoute::register();
TagRoute::register();
