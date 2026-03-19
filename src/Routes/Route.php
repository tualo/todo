<?php

namespace Tualo\Office\ToDo\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as R;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS\DSTable;


class Route extends \Tualo\Office\Basic\RouteWrapper
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
        
        insert ignore into route_scopes (`scope`) values ('todobeispiel.file');
        insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`) 
        values ('todobeispiel.file', '_default_', 1);
        */
        return 'todobeispiel.file';
    }
    public static function register()
    {
        // Muster zum Hinzufügen einer neuen Route
        R::add(
            '/todo-beispiel/(?P<muster>[\/.\w\d\-]+)',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {

                    // Datenbankzugriff, zum Beispiel Einfügen eines neuen Eintrags
                    $table = DSTable::instance('todo_beispiel_tabelle');
                    $table->insert([
                        'muster' => $matches['muster'],
                    ], ['update' => true]);

                    // Fehlerbehandlung, zum Beispiel fehlendes Recht oder ungültige Daten
                    if ($table->error()) throw new \Exception($table->errorMessage());

                    TualoApplication::result('success', true);
                } catch (\Exception $e) {
                    TualoApplication::result('msg', $e->getMessage());
                }


                // 
                // [] = keine zusätzlichen Rechte erforderlich, außer gültigem Token
            },
            ['get'], // get erlauben
            true, // true = geschützte Route, nur mit gültigem Token oder aktiver session erreichbar
            [ // erwartete eingaben und verhaten bei unerwarteten oder ungültigen eingaben
                'errorOnUnexpected' => true,
                'errorOnInvalid' => true,
                'fields' => self::DefaultExpectedFields
            ],
            self::scope() // erforderliche Berechtigung für diese Route, siehe scope() Methode
        );
    }
}
