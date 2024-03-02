# Installation

### Requirement
- Install Docker
- Internet
- postgresql client (Dbeaver alternative free)

### Step
- clone this repository
- open cmd, goto that folder was cloned
- type: *docker-compose up -d* / *docker compose up -d*
- make sure container are running :
  - **postgresql**
  - **halo_api**
  - **halo_web**
- wait until download and running docker from my repository
- after running, connect PostgreSQL database with client to create new database
  - host : localhost
  - port : 5435
  - user : postgres
  - pass : postgres
- create database with name : **halotek**
- after created database, open docker/cmd
  - docker :
    - go to activated container (**halo_api**)
    - open shell and type : *php artisan migrate && php artisan db:seed --class=JwtUserSeeder*
  - cmd :
    - type : *docker exec --user 1000 -it halo_api sh -c "php artisan migrate && php artisan db:seed --class=JwtUserSeeder"*

### Access
- url : [Link Swagger]("http://127.0.254.1:8101") / *http://127.0.254.1:8101*