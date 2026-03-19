# 08 - Betrieb, Migration und Release

## Betrieb

- Standardkonfiguration mit sicheren Defaults.
- Logging fuer API-Fehler und wichtige Domain-Events.

## Migration

- DDL-Dateien nummeriert und deterministisch.
- Vorwaerts- und Rueckwaertskompatibilitaet bei Datenmodellaenderungen beachten.

## Release

- Semantische Versionierung.
- Changelog pro Release.
- Kurze Upgrade-Hinweise bei Schemaaenderungen.

## Entscheidungen

- SQL-Migrationen als first-class artefacts im Paket.
- Breaking Changes nur mit Major-Release.
