# 07 - Tests und Qualitaet

## Teststrategie

1. Unit-Tests fuer Use-Cases.
2. Integrations-Tests fuer SQL-Zugriffe.
3. API-Tests fuer Endpunkte und Fehlerfaelle.

## Qualitaetskriterien

- Klare Fehlermeldungen.
- Reproduzierbare lokale Testausfuehrung.
- Rueckwaertskompatible API-Aenderungen.

## Entscheidungen

- Testdaten ueber SQL-Fixtures.
- CI soll Migration + Tests in jedem Lauf ausfuehren.
- Mindestabdeckung fuer Kernlogik definieren.
