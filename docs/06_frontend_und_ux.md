# 06 - Frontend und UX

## Zielbild

Eine minimalistische, schnelle Oberflaeche fuer taegliche Nutzung.

## Kerninteraktionen

- Aufgabe schnell erfassen.
- Aufgabe mit einem Klick als erledigt markieren.
- Faelligkeiten und Prioritaeten direkt sichtbar machen.

## Entscheidungen

- Mobile-first bei Layout und Bedienelementen.
- Tastatur-Shortcuts fuer Power-User vorbereiten.
- UI-Layer strikt von API-Vertraegen entkoppeln.

## Umsetzung (Stand jetzt)

- UI-Route ist unter `/todo/ui` implementiert.
- Pug-Template liegt unter `src/pug/todo_ui.pug`.
- Rendering erfolgt serverseitig ueber `Tualo\Office\PUG\PUG` in `src/Routes/Ui.php`.
- Registrierung der UI-Route erfolgt in `src/functions.php`.

## Naechste UI-Schritte

- Aufgabeingabe mit POST `/todo/lists/{list_id}/items` verbinden.
- Aufgabenliste aus GET `/todo/lists/{list_id}/items` dynamisch laden.
- Statuswechsel (done/open) direkt aus der Liste triggern.
