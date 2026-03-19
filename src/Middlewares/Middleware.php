<?php

namespace Tualo\Office\ToDo\Middlewares;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\IMiddleware;

class Middleware implements IMiddleware
{
    public static function register()
    {
        TualoApplication::use('todo-js', function () {
            try {

                TualoApplication::javascript('todo', './todo-js/todo.js', [], -5000000);
                TualoApplication::stylesheet('./todo-js/todo.snow.css', 10001);
            } catch (\Exception $e) {
                TualoApplication::set('maintanceMode', 'on');
                TualoApplication::addError($e->getMessage());
            }
        }, -100); // should be one of the last
    }
}
