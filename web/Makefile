ADDR=root@mr-calen.eu:/root/DevLumHue/web

deploy:
	composer install
	chmod -R 777 storage
	cp /tmp/env .env

update:
	rsync -av --exclude 'vendor' --exclude '.git' --exclude '.env' --exclude 'composer.lock' . ${ADDR}

.PHONY: deploy
