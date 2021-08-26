## Get started
> composer install
> php ./bin/console doctrine:database:create
> php ./bin/console doctrine:migrations:migrate
### dev
> php ./bin/console doctrine:fixtures:load

### Start api serveur
> symfony serve
> php -S 127.0.0.1:8000 -t public

### Mercure serveur
> JWT_KEY='blabla' ADDR='127.0.0.1:3001' ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS=* ./bin/mercure

## TOTO
- Ajouter l'année (exmple : 19-20) pour les classes
- Login trim email


https://codingstories.net/how-to/how-to-install-and-use-mercure/