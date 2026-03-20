<?php

namespace Tualo\Office\ToDo\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as R;
use Tualo\Office\PUG\PUG;


class Ui extends \Tualo\Office\Basic\RouteWrapper
{

    const DefaultExpectedFields  = [

        '_dc' => [
            'required' => false,
            'type' => 'string',
            'minlength' => 0,
            'maxlength' => 10000
        ],
    ];

    public static function scope(): string
    {
        /*
        
        -- Beispiel scope für die Zugriffskontrolle, muss in der Datenbank angelegt werden:
        
        insert ignore into route_scopes (`scope`) values ('todo.ui');
        insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`) 
        values ('todo.ui', '_default_', 1);
        */
        return 'todo.ui';
    }
    public static function register()
    {
        R::add(
            '/todo/ui',
            function ($matches) {
                TualoApplication::contenttype('text/html');
                try {
                    $cachePath = TualoApplication::get('cachePath') . '/pugcache';
                    if (!file_exists($cachePath)) {
                        mkdir($cachePath, 0777, true);
                    }

                    $pug = PUG::getPug([
                        'pretty' => true,
                        'cache' => $cachePath,
                    ]);

                    $pugfile = dirname(__DIR__) . '/pug/todo_ui.pug';

                    $params = [
                        'title' => 'ToDo',
                        'stylesheets' => [], // TualoApplication::stylesheet(),
                        'javascripts' => [], // TualoApplication::javascript(),
                        'modules' => [], // TualoApplication::module(),
                    ];

                    TualoApplication::body($pug->renderFile($pugfile, $params));
                } catch (\Exception $e) {
                    TualoApplication::body('UI render error: ' . $e->getMessage());
                }
            },
            ['get'],
            true,
            [
                'errorOnUnexpected' => true,
                'errorOnInvalid' => true,
                'fields' => self::DefaultExpectedFields
            ],
            self::scope()
        );
    }
}
