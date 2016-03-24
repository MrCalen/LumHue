ADDR=hugh@mr-calen.eu:/home/hugh/LumHue/web

deploy:
	composer install
	chmod -R 777 storage
	cp /tmp/env .env

update:
	rsync -av --exclude 'vendor' --exclude '.git' --exclude '.env' --exclude 'composer.lock' . ${ADDR}

.PHONY: deploy
