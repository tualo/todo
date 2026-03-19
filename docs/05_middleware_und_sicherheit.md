# 05 - Middleware und Sicherheit

## Fokus

Einfaches, sicheres Basissetup mit Raum fuer spaetere Erweiterungen.

## Middleware-Kette (Plan)

1. Request-Kontext aufbauen (Mandant, Benutzer, Correlation-ID).
2. Authentifizierung pruefen.
3. Autorisierung auf Listenebene pruefen.
4. Payload validieren.

## Entscheidungen

- Security by default: Keine anonymen Schreiboperationen.
- Serverseitige Validierung ist Pflicht.
- Aktivitaeten optional in `todo_activity` protokollieren.
