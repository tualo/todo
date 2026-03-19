<?php

namespace Tualo\Office\ToDo\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route as R;

class ItemRoute
{
    const FieldsCreate = [
        '_dc'            => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 10000],
        'title'          => ['required' => true,  'type' => 'string', 'minlength' => 1,  'maxlength' => 255],
        'notes'          => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 65535],
        'due_at'         => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
        'reminder_at'    => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
        'priority'       => ['required' => false, 'type' => 'int',    'min' => 1,        'max' => 5],
        'sort_order'     => ['required' => false, 'type' => 'int',    'min' => -99999,   'max' => 99999],
        'parent_item_id' => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 36],
    ];

    const FieldsUpdate = [
        '_dc'            => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 10000],
        'title'          => ['required' => false, 'type' => 'string', 'minlength' => 1,  'maxlength' => 255],
        'notes'          => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 65535],
        'due_at'         => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
        'reminder_at'    => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 32],
        'priority'       => ['required' => false, 'type' => 'int',    'min' => 1,        'max' => 5],
        'sort_order'     => ['required' => false, 'type' => 'int',    'min' => -99999,   'max' => 99999],
        'status'         => ['required' => false, 'type' => 'string', 'minlength' => 1,  'maxlength' => 16],
        'parent_item_id' => ['required' => false, 'type' => 'string', 'minlength' => 0,  'maxlength' => 36],
    ];

    public static function scope(): string
    {
        /*
        insert ignore into route_scopes (`scope`) values ('todo.item');
        insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`)
            values ('todo.item', '_default_', 1);
        */
        return 'todo.item';
    }

    public static function register(): void
    {
        // GET /todo/lists/{list_id}/items — alle Aufgaben einer Liste lesen
        R::add(
            '/todo/lists/(?P<list_id>[^/]+)/items',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db   = TualoApplication::get('session')->getDB();
                    $data = $db->direct(
                        'SELECT * FROM todo_item
                         WHERE list_id = {list_id}
                           AND deleted_at IS NULL
                         ORDER BY sort_order ASC, created_at ASC',
                        ['list_id' => $matches['list_id']]
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

        // POST /todo/lists/{list_id}/items — neue Aufgabe anlegen
        R::add(
            '/todo/lists/(?P<list_id>[^/]+)/items',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (empty($input['title'])) {
                        throw new \Exception('title is required');
                    }

                    $db  = TualoApplication::get('session')->getDB();
                    $id  = $db->singleValue('SELECT UUID()', [], 0);
                    $db->direct(
                        'INSERT INTO todo_item
                            (id, list_id, parent_item_id, title, notes, due_at, reminder_at, priority, sort_order)
                         VALUES
                            ({id}, {list_id}, {parent_item_id}, {title}, {notes}, {due_at}, {reminder_at}, {priority}, {sort_order})',
                        [
                            'id'             => $id,
                            'list_id'        => $matches['list_id'],
                            'parent_item_id' => $input['parent_item_id'] ?? null,
                            'title'          => $input['title'],
                            'notes'          => $input['notes']          ?? null,
                            'due_at'         => $input['due_at']         ?? null,
                            'reminder_at'    => $input['reminder_at']    ?? null,
                            'priority'       => $input['priority']       ?? 3,
                            'sort_order'     => $input['sort_order']     ?? 0,
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

        // POST /todo/items/{id}/update — Aufgabe aktualisieren
        R::add(
            '/todo/items/(?P<id>[^/]+)/update',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $input = json_decode(file_get_contents('php://input'), true) ?? [];
                    $id    = $matches['id'];

                    $db         = TualoApplication::get('session')->getDB();
                    $setClauses = [];
                    $params     = ['id' => $id];

                    $allowed = ['title', 'notes', 'due_at', 'reminder_at', 'priority', 'sort_order', 'status', 'parent_item_id'];
                    foreach ($allowed as $field) {
                        if (array_key_exists($field, $input)) {
                            $setClauses[] = "`$field` = {" . $field . "}";
                            $params[$field] = $input[$field];
                        }
                    }

                    // Status auf 'done' gesetzt → completed_at automatisch befüllen
                    if (isset($input['status']) && $input['status'] === 'done') {
                        $setClauses[] = '`completed_at` = NOW(3)';
                    } elseif (isset($input['status']) && $input['status'] !== 'done') {
                        $setClauses[] = '`completed_at` = NULL';
                    }

                    if (empty($setClauses)) {
                        throw new \Exception('no fields to update');
                    }

                    $db->direct(
                        'UPDATE todo_item SET ' . implode(', ', $setClauses) . ' WHERE id = {id} AND deleted_at IS NULL',
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

        // POST /todo/items/{id}/delete — Aufgabe weich löschen
        R::add(
            '/todo/items/(?P<id>[^/]+)/delete',
            function ($matches) {
                TualoApplication::contenttype('application/json');
                TualoApplication::result('success', false);
                try {
                    $db = TualoApplication::get('session')->getDB();
                    $db->direct(
                        'UPDATE todo_item SET deleted_at = NOW(3) WHERE id = {id} AND deleted_at IS NULL',
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
