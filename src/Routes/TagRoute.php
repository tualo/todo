<?php

namespace Tualo\Office\ToDo\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as R;

class TagRoute extends \Tualo\Office\Basic\RouteWrapper
{
    const FieldsCreate = [
        '_dc'   => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 10000],
        'name'  => ['required' => true,  'type' => 'string', 'minlength' => 1,  'maxlength' => 128],
        'color' => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
    ];

    const FieldsUpdate = [
        '_dc'   => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 10000],
        'name'  => ['required' => false, 'type' => 'string', 'minlength' => 1,  'maxlength' => 128],
        'color' => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
    ];

    public static function scope(): string
    {
        /*
        insert ignore into route_scopes (`scope`) values ('todo.tag');
        insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`)
            values ('todo.tag', '_default_', 1);
        */
        return 'todo.tag';
    }

    public static function register(): void
    {
        // GET /todo/tags — alle Tags lesen
        R::add(
            '/todo/tags',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db   = TualoApplication::get('session')->getDB();
                    $data = $db->direct(
                        'SELECT * FROM todo_tag ORDER BY name ASC',
                        []
                    );
                    TualoApplication::result('data', $data);
                    TualoApplication::result('success', true);
                } catch (\Exception $e) {
                    TualoApplication::result('msg', $e->getMessage());
                }
            },
            ['get'],
            true,
            [
                'errorOnUnexpected' => false,
                'errorOnInvalid'    => false,
                'fields'            => ['_dc' => ['required' => false, 'type' => 'string', 'minlength' => 0, 'maxlength' => 10000]],
            ],
            self::scope()
        );

        // POST /todo/tags — neuen Tag anlegen
        R::add(
            '/todo/tags',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (empty($input['name'])) {
                        throw new \Exception('name is required');
                    }

                    $db = TualoApplication::get('session')->getDB();
                    $id = $db->singleValue('SELECT UUID() u', [], 'u');
                    $db->direct(
                        'INSERT INTO todo_tag (id, name, color) VALUES ({id}, {name}, {color})',
                        [
                            'id'    => $id,
                            'name'  => $input['name'],
                            'color' => $input['color'] ?? null,
                        ]
                    );

                    TualoApplication::result('id', $id);
                    TualoApplication::result('success', true);
                } catch (\Exception $e) {
                    TualoApplication::result('msg', $e->getMessage());
                }
            },
            ['post'],
            true,
            [
                'errorOnUnexpected' => true,
                'errorOnInvalid'    => true,
                'fields'            => self::FieldsCreate,
            ],
            self::scope()
        );

        // POST /todo/tags/{id}/update — Tag aktualisieren
        R::add(
            '/todo/tags/(?P<id>[^/]+)/update',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true) ?? [];
                    $id    = $matches['id'];

                    $db         = TualoApplication::get('session')->getDB();
                    $setClauses = [];
                    $params     = ['id' => $id];

                    foreach (['name', 'color'] as $field) {
                        if (array_key_exists($field, $input)) {
                            $setClauses[] = "`$field` = {" . $field . "}";
                            $params[$field] = $input[$field];
                        }
                    }

                    if (empty($setClauses)) {
                        throw new \Exception('no fields to update');
                    }

                    $db->direct(
                        'UPDATE todo_tag SET ' . implode(', ', $setClauses) . ' WHERE id = {id}',
                        $params
                    );

                    TualoApplication::result('success', true);
                } catch (\Exception $e) {
                    TualoApplication::result('msg', $e->getMessage());
                }
            },
            ['post'],
            true,
            [
                'errorOnUnexpected' => true,
                'errorOnInvalid'    => true,
                'fields'            => self::FieldsUpdate,
            ],
            self::scope()
        );

        // POST /todo/tags/{id}/delete — Tag löschen
        R::add(
            '/todo/tags/(?P<id>[^/]+)/delete',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db = TualoApplication::get('session')->getDB();
                    $db->direct(
                        'DELETE FROM todo_tag WHERE id = {id}',
                        ['id' => $matches['id']]
                    );
                    TualoApplication::result('success', true);
                } catch (\Exception $e) {
                    TualoApplication::result('msg', $e->getMessage());
                }
            },
            ['post'],
            true,
            [
                'errorOnUnexpected' => false,
                'errorOnInvalid'    => false,
                'fields'            => ['_dc' => ['required' => false, 'type' => 'string', 'minlength' => 0, 'maxlength' => 10000]],
            ],
            self::scope()
        );
    }
}
