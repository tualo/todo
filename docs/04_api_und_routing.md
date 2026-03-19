# 04 - API und Routing

## API-Grundsaetze

- REST-nahe Endpunkte mit klaren Ressourcen.
- Konsistente Fehlerantworten.
- Idempotente Updates, wo moeglich.

## Initiale Endpunkte

1. `GET /todo/lists`
2. `POST /todo/lists`
3. `PATCH /todo/lists/{id}`
4. `GET /todo/lists/{id}/items`
5. `POST /todo/lists/{id}/items`
6. `PATCH /todo/items/{id}`
7. `DELETE /todo/items/{id}`

## Entscheidungen

- Sortierung und Filter als Query-Parameter.
- Paging von Anfang an fuer Listen und Items.
- API-Versionierung vorbereiten (`/api/v1/...`).
