start:
	./vendor/bin/sail up -d

stop:
	./vendor/bin/sail down

migrate:
	./vendor/bin/sail artisan migrate

migrate-refresh:
	./vendor/bin/sail artisan migrate:refresh

seed:
	./vendor/bin/sail artisan db:seed

clear-cache:
	./vendor/bin/sail artisan cache:clear

clear-route-cache:
	./vendor/bin/sail artisan route:clear

list-routes:
	./vendor/bin/sail artisan route:list

schedule-run:
	./vendor/bin/sail artisan schedule:run

schedule-list:
	./vendor/bin/sail artisan schedule:list

test:
	./vendor/bin/sail test
