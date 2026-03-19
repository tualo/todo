# 03 - Datenmodell und MariaDB DDL

## Ziel dieses Schritts

Definition eines robusten, aber einfachen Schemas fuer Todo-Listen mit Erweiterungspfad.

## Tabellen

1. `todo_list`: Kopfobjekt fuer Listen.
2. `todo_item`: Einzelne Aufgaben, inkl. optionaler Subtasks.
3. `todo_tag`: Wiederverwendbare Tags.
4. `todo_item_tag`: N:M-Verknuepfung Aufgaben <-> Tags.
5. `todo_activity`: Audit/Events fuer Nachvollziehbarkeit.

## Entscheidungen

- Primaerschluessel als `VARCHAR(36) NOT NULL DEFAULT (UUID())` — keine AUTO_INCREMENT-Sequenzen, damit Galera-Cluster konfliktfrei schreiben koennen.
- Alle Fremdschluessel ebenfalls `VARCHAR(36)`.
- Kein `ENGINE`, kein `CHARSET`, kein `COLLATE` in den DDL-Dateien — Betreiber legen die Datenbankdefaults selbst fest.
- Statusfeld per ENUM fuer klare Grundzustandsmaschine.
- Soft-Delete (`deleted_at`) fuer sichere Loeschvorgaenge.

## Ergebnis

Die initialen DDL-Skripte wurden unter `sql/` angelegt:

- `001_create_todo_list.sql`
- `002_create_todo_item.sql`
- `003_create_todo_tag.sql`
- `004_create_todo_item_tag.sql`
- `005_create_todo_activity.sql`