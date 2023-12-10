--How to setup the system--
open git bash then type 
    SSH--> git clone git@github.com:Ceasar2001/HatodTaka.git
                or
    HTTPS--> git clone https://github.com/Ceasar2001/HatodTaka.git

after cloning type
    --> cd HatodTaka
then type
    --> code .

then run backend -> cd backend --> php artisan serve
then run websocket -> cd backend --> php artisan websocket:serve
then run fronend -> cd frontend --> npm run dev

----how to setup database---
open pg admin the create database named hatodtakaDB

on vscode type --> php artisan migrate <-- to migrate database

use google map auto complete
https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete

use google vue 
https://vue-map.netlify.app/

npm install --save-dev laravel-echo pusher-js