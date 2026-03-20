<?php

require_once "Middlewares/Middleware.php";
require_once "Routes/Route.php";
require_once "Routes/ListRoute.php";
require_once "Routes/ItemRoute.php";
require_once "Routes/TagRoute.php";
require_once "Routes/Ui.php";

// wird automatisch geladen, siehe 
// bsc/src/Middleware/Router.php Zeile 20

/*
use Tualo\Office\ToDo\Middlewares\Middleware;
use Tualo\Office\ToDo\Routes\ListRoute;
use Tualo\Office\ToDo\Routes\ItemRoute;
use Tualo\Office\ToDo\Routes\TagRoute;
use Tualo\Office\ToDo\Routes\Ui;

Middleware::register();
ListRoute::register();
ItemRoute::register();
TagRoute::register();
Ui::register();
*/