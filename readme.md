## WhatsThat

Portal and backend for WhatsThat.

### Setup for development

- git clone fachportal
- git clone laradock 
- configure laradock
    - configure a nginx site (todo: more details)
- add to your local hosts file
    - `127.0.0.1 fachportal.dev`
- build and start all needed docker containers
    - `docker-compose up -d nginx php-fpm workspace mysql mailhog`