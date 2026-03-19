# 01 - Scope und Ziele

## Produktziel

Das Paket soll eine einfache Todo-Listen-Anwendung bereitstellen, die sofort nutzbar ist und spaeter ohne Brueche erweitert werden kann.

## Nicht-Ziele in Phase 1

- Kein komplexes Kollaborationsmodell in Echtzeit.
- Keine mobile App.
- Keine KI-Features.

## Kernanforderungen

- Listen erstellen, bearbeiten, archivieren.
- Aufgaben erstellen, priorisieren, erledigen.
- Einfache Filter auf offen, faellig, erledigt.
- Solides Datenmodell als Basis fuer spaetere Features.

## Entscheidungen

- MariaDB als primaere Persistenz.
- Klare Trennung zwischen API, Middleware und Persistenz.
- Schema so gestalten, dass Tags, Subtasks und Audit ohne Rework moeglich sind.