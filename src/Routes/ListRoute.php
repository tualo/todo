<?php

namespace Tualo\Office\ToDo\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as R;
use Tualo\Office\DS\DSTable;

class ListRoute extends \Tualo\Office\Basic\RouteWrapper
{
    const FieldsCreate = [
        '_dc'         => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 10000],
        'title'       => ['required' => true,  'type' => 'string', 'minlength' => 1,  'maxlength' => 255],
        'description' => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 65535],
        'color'       => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
        'tenant_id'   => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 36],
    ];

    const FieldsUpdate = [
        '_dc'         => ['required' => false, 'type' => 'string',  'minlength' => 0, 'maxlength' => 10000],
        'title'       => ['required' => false, 'type' => 'string',  'minlength' => 1, 'maxlength' => 255],
        'description' => ['required' => false, 'type' => 'string',  'minlength' => 0, 'maxlength' => 65535],
        'color'       => ['required' => false, 'type' => 'string',  'minlength' => 0, 'maxlength' => 32],
        'is_archived' => ['required' => false, 'type' => 'int',     'min' => 0,       'max' => 1],
    ];

    public static function scope(): string
    {
        /*
        insert ignore into route_scopes (`scope`) values ('todo.list');
        insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`)
            values ('todo.list', '_default_', 1);
        */
        return 'todo.list';
        // return 'basic';
    }

    public static function register(): void
    {
        // GET /todo/lists — alle aktiven Listen lesen

        R::add(
            '/todo/lists',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db = TualoApplication::get('session')->getDB();
                    $data = $db->direct(
                        'SELECT * FROM todo_list WHERE deleted_at IS NULL ORDER BY created_at DESC',
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

        // POST /todo/lists — neue Liste anlegen
        R::add(
            '/todo/lists',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (empty($input['title'])) {
                        throw new \Exception('title is required');
                    }

                    $db   = TualoApplication::get('session')->getDB();
                    $id   = $db->singleValue('SELECT UUID() u', [], 'u');
                    $db->direct(
                        'INSERT INTO todo_list (id, tenant_id, title, description, color)
                         VALUES ({id}, {tenant_id}, {title}, {description}, {color})',

                        [
                            'id'          => $id,
                            'tenant_id'   => $input['tenant_id']   ?? null,
                            'title'       => $input['title'],
                            'description' => $input['description'] ?? null,
                            'color'       => $input['color']        ?? null,
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

        // POST /todo/lists/{id}/update — Liste aktualisieren
        R::add(
            '/todo/lists/(?P<id>[^/]+)/update',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true) ?? [];
                    $id    = $matches['id'];

                    $db = TualoApplication::get('session')->getDB();

                    $setClauses = [];
                    $params     = ['id' => $id];

                    $allowed = ['title', 'description', 'color', 'is_archived'];
                    foreach ($allowed as $field) {
                        if (array_key_exists($field, $input)) {
                            $setClauses[] = "`$field` = {" . $field . "}";
                            $params[$field] = $input[$field];
                        }
                    }

                    if (empty($setClauses)) {
                        throw new \Exception('no fields to update');
                    }

                    $db->direct(
                        'UPDATE todo_list SET ' . implode(', ', $setClauses) . ' WHERE id = {id} AND deleted_at IS NULL',
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

        // POST /todo/lists/{id}/delete — Liste weich löschen
        R::add(
            '/todo/lists/(?P<id>[^/]+)/delete',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db = TualoApplication::get('session')->getDB();
                    $db->direct(
                        'UPDATE todo_list SET deleted_at = NOW(3) WHERE id = {id} AND deleted_at IS NULL',
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
