# 04 - API und Routing

## API-Grundsaetze

- POST fuer alle schreibenden Operationen (create, update, delete), GET fuer Lesezugriffe.
- Konsistente JSON-Antworten mit `success`, `data`, `msg`.
- Idempotente Updates: nur geaenderte Felder muessen gesendet werden.

## Implementierte Endpunkte

### Listen (`todo.list` Scope)

| Methode | URL                              | Beschreibung           |
|---------|----------------------------------|------------------------|
| GET     | `/todo/lists`                    | Alle aktiven Listen    |
| POST    | `/todo/lists`                    | Neue Liste anlegen     |
| POST    | `/todo/lists/{id}/update`        | Liste aktualisieren    |
| POST    | `/todo/lists/{id}/delete`        | Liste weich loeschen   |

### Aufgaben (`todo.item` Scope)

| Methode | URL                                    | Beschreibung              |
|---------|----------------------------------------|---------------------------|
| GET     | `/todo/lists/{list_id}/items`          | Aufgaben einer Liste      |
| POST    | `/todo/lists/{list_id}/items`          | Neue Aufgabe anlegen      |
| POST    | `/todo/items/{id}/update`              | Aufgabe aktualisieren     |
| POST    | `/todo/items/{id}/delete`              | Aufgabe weich loeschen    |

### Tags (`todo.tag` Scope)

| Methode | URL                       | Beschreibung        |
|---------|---------------------------|---------------------|
| GET     | `/todo/tags`              | Alle Tags           |
| POST    | `/todo/tags`              | Neuen Tag anlegen   |
| POST    | `/todo/tags/{id}/update`  | Tag aktualisieren   |
| POST    | `/todo/tags/{id}/delete`  | Tag loeschen        |

## Entscheidungen

- Aktions-URLs (`/update`, `/delete`) statt HTTP-Verben PATCH/DELETE fuer Konsistenz mit dem bestehenden Framework-Routing-Stil.
- Scope-basierte Zugriffskontrolle pro Ressourcentyp.
- Soft-Delete fuer Listen und Aufgaben (deleted_at), Hard-Delete nur fuer Tags.
- Status update auf `done` setzt `completed_at` automatisch, alle anderen Status resetten es.
- `completed_at` wird serverseitig gesetzt, niemals vom Client.

## Scope-Initialisierung (SQL)

```sql
insert ignore into route_scopes (`scope`) values ('todo.list'), ('todo.item'), ('todo.tag');
insert ignore into route_scopes_permissions (`scope`, `group`, `allowed`)
    values ('todo.list', '_default_', 1),
           ('todo.item', '_default_', 1),
           ('todo.tag',  '_default_', 1);
```

