### Esküvőszervező Rendszer - Projekt Dokumentáció
Ez a projekt egy prémium esküvőszervező webalkalmazás, amely Laravel (Backend) és Vue.js (Frontend) keretrendszerekre épül, MySQL adatbázis-kiszolgálóval.

### 1. A fejlesztési környezethez szükséges szoftverek
A kód módosításához és a fejlesztői környezet felépítéséhez telepítened kell:
    - Composer: A Laravel (PHP) függőségek kezeléséhez
    - Node.js & NPM: A Vue.js (Frontend) fordításához és a csomagok kezeléséhez.
    - Git: A verziókövetéshez és a forráskód letöltéséhez.
    - Helyi szerver környezet: Javasolt a XAMPP vagy a WAMP.
    - IDE: Visual Studio Code.

### 2. Forráskód letöltése
A projektet a Git verziókövető segítségével töltheted le:
    - git clone https://github.com/Szidivagyok/RendezvenySzervezo
    
### 3. A program tesztkörnyezetének telepítése és futtatása
Kövesd az alábbi lépéseket a rendszer beüzemeléséhez:
   ### - A, Backend (Laravel) beállítása
        Függőségek telepítése:
        - composer install
    - Környezeti fájl beállítása:
        Másold le az .env.example fájlt .env néven, majd állítsd be benne a MySQL adatbázis adatait (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    - Adatbázis migráció és feltöltés (Seeding):
        - php artisan migrate --seed
   ### - B, Frontend (Vue.js) beállítása
        NPM csomagok telepítése:
        - npm install
    - Assetek fordítása (fejlesztői mód):
        - cd client
        - npm run dev
   ### - C, A rendszer indítása
    - Indítsd el a Laravel beépített szerverét:
        - cd server
        - php artisan serve

    