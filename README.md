# my-notes-php-app

Eine Notizen-Web-App mit eigener kleiner MVC-/OOP-Architektur in purem PHP. Notizen können erstellt, bearbeitet, gelöscht, angepinnt und nach verschiedenen Kriterien sortiert werden.

Gebaut im Rahmen der Ausbildung – der Fokus lag auf sauberer Objektorientierung (Klassen, Getter/Setter, Repository-Pattern) und wiederverwendbaren Komponenten.

## Funktionen

- CRUD für Notizen (anlegen, lesen, bearbeiten, löschen)
- Notizen anpinnen (Pinned-Funktion)
- Sortierung nach Änderungsdatum, Titel oder Farbe
- Farbcodierung der Notizen (lila, grün, rot, grau)
- Übersicht mit Pagination (10 Notizen pro Seite) und Vorschau des Inhalts
- Responsive Oberfläche

## Technologien

- PHP (objektorientiert, ohne Framework)
- MySQL / PDO
- HTML5 & CSS (Bootstrap-Klassen, eigenes Styling)
- Optional: Docker (`compose.yml` + `Dockerfile`)

## Projektstruktur

```
index.php       – Einstiegspunkt (Front-Controller)
Controller.php  – Steuerung der App-Logik
Note.php        – Notizen-Modell
NoteRepo.php    – Datenbankzugriffe (Repository-Pattern)
NoteView.php    – Darstellung der Ansichten
Pagination.php  – Seitenumbruch der Übersicht
Filters.php     – Filter-/Sortierlogik
Config.php      – zentrale Konfiguration (DB, Limits, Farben)
css/            – Styles
img/            – Grafiken
```

## Lokal starten (XAMPP)

Voraussetzungen: laufender Apache mit PHP und ein MySQL-Server.

1. Den Ordner in ein Webverzeichnis (z. B. `C:\xampp\htdocs`) legen.
2. Datenbank anlegen:

```sql
CREATE DATABASE `my-note-oop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE TABLE `my-note-oop`.`notes` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  bg_color VARCHAR(50) NOT NULL DEFAULT 'secondary',
  pinned TINYINT(1) NOT NULL DEFAULT 0,
  update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

3. Zugangsdaten anpassen: `Config.example.php` nach `Config.php` kopieren und
   in `setDb()` eintragen (`Config.php` ist von Git ausgeschlossen, damit
   keine lokalen Zugangsdaten eingecheckt werden):

```php
'dbn'      => 'mysql:host=127.0.0.1;dbname=DB_NAME',
'user'     => 'DB_USER',
'password' => 'DB_PASSWORD',
```

Hinweis: Die Konfig `Config.php` ist von Git ausgeschlossen – sie wird aus der
Vorlage `Config.example.php` kopiert und lokal befüllt.

**Lokal erreichbar unter:** `http://localhost/pu-my-notes-php-app/`

## Alternativ mit Docker

```bash
docker compose up -d
```

Dazu muss in `Config.php` der Datenbank-Host wieder auf den Dienstnamen `mariadb` gesetzt werden.