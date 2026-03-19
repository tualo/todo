# 02 - Architektur und Modulgrenzen

## Schichten

1. Transport: Routes und Controller-Einstieg.
2. Anwendung: Use-Cases wie Aufgabe anlegen oder als erledigt markieren.
3. Persistenz: SQL-Zugriffe und Mapping.

## Modulgrenzen

- `src/Routes`: HTTP-Endpunkte.
- `src/Middlewares`: Auth, Kontext, Validierung.
- `src/sql`: Bestehende SQL-Ressourcen des Pakets.
- `sql`: Neue DDL- und Migrationsdateien fuer die Todo-Funktion.

## Entscheidungen

- SQL-first fuer den initialen Start, damit Datenstruktur frueh stabil ist.
- Tabellenentwurf mit Soft-Delete und Zeitstempeln.
- Erweiterbarkeit durch optionale JSON-Metadaten und Activity-Tabelle.