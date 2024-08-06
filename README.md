
<p  align="center"><a  href="https://laravel.com"  target="_blank"><img  src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg"  width="400"  alt="Laravel Logo"></a></p>

<p align="center">CINAME API</p>

  
  

##  Getting started

  Check the `Makefile` for helpful commands.

1. Clone the repository

2. Create a `.env` file from `.env.example` and adjust the database params 
	-  `DB_CONNECTION=mysql`
	-  `DB_HOST=mysql`
	-  `DB_PORT=3306`
	-  `DB_DATABASE=cinema`
	-  `DB_USERNAME=test`
	-  `DB_PASSWORD=test`
	-  `FORWARD_DB_PORT=33060`
3. Run `composer install`
4. Run `make start` to start the docker container 
5. Run `make migrate` to create the database migrations
6. Run `make link-storage` to create the symlink to the storage folder
7. Run `make schedule-work` to to start the schedule worker 

If you are using any external database management tool make sure that the `allowPublicKeyRetrieval` set to `true`

## Testing
There are some feature and unit tests added for the controllers, run `make test` command to execute the tests

