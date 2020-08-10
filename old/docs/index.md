# Projektwoche-Software

Diese Software hilft beim Vorbereiten einer Projektwoche oder ähnlichen Veranstaltungen, bei denen Personen anhand von Wahlen in Projekte zugeordnet werden müssen. Dies passiert hier jedoch komplett automatisch und gerecht.

## Features

* Intuitive Bedienung und Hilfe
* Konfigurierbarer Willkommenstext (TODO)
* Schüler
  * CSV Import möglich
  * von Lehrer als abwesend setzbar
  * einfach anmelden und wählen
  * für Lehrer sichtbar, wer noch nicht gewählt hat
  * ausdruckbare Passwortlisten
  * Passwörter sicher gespeichert (bcrypt)
  * Passwort änderbar
* Projekte
  * vorraussichtliche Größe / Existenz für Lehrer sichtbar (TODO)
  * Betreuer (Lehrer)
  * *Schülerleiter*
  * Kosten
  * ausdruckbar
* Wahl
  * Projekte in Erst- bis Fünftwahl einteilen
  * Jederzeit beendbar und startbar
  * Automatische Berechnung der Projektzuordungen
    * auch manuelle Änderungen möglich
    * Wahlzeitpunkt irrelevant
    * Maximierung der kollektiven Zufriedenheit
    * mathematisch beweisbarer optimaler Algorithmus
    * ausdruckbar
* Lehrer
  * CSV Import möglich
  * kann Projekte erstellen
  * ausdruckbare Passwortlisten
  * Passwörter sicher gespeichert (bcrypt)
  * kann Schülerpasswörter zurücksetzen
  * Passwort änderbar
* Administrator
  * kann Schüler- und Lehrerpasswörter zurücksetzen
  * Passwort änderbar

## Kosten

### Kostenlos

* Quellcode öffentlich
* Selbständige Einrichtung
* Kein Support
* [https://github.com/mohe2015/projektwahl-php](https://github.com/mohe2015/projektwahl-php)

### Standard

* Quellcode öffentlich
* Komplettes Management
* Support
* Persönliche Modifikationen (Extrakosten möglich)
* Kontakt: [Moritz.Hedtke@t-online.de](mailto:Moritz.Hedtke@t-online.de)

## Screenshots

![Image of election](images/election.png)
![Image of invalid election](images/election_error.png)
![Image of projects list](images/projects.png)
![Image of creating project](images/create_project.png)
![Image of students list](images/students.png)
![Image of teachers list](images/teachers.png)
![Image of calculating project assignments](images/calculate.png)
