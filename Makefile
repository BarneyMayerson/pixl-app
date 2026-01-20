test:
	vendor/bin/phpunit --testdox

test-unit:
	vendor/bin/phpunit --filter=unit

test-feature:
	vendor/bin/phpunit --filter=feature

perm:
	sudo chgrp -R www-data storage bootstrap/cache
	sudo chmod -R ug+rwx storage bootstrap/cache
	sudo chmod -R uga+rw storage bootstrap/cache
