# Challenge-stack-1-Symfony

Ajouter un .env.local dans la app :

```
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# bdd MAMP
#DATABASE_URL="mysql://root:root@127.0.0.1:3306/[ToChange]"

# bdd Docker
DATABASE_URL="mysql://root:root@localhost:3306/ChallengeStack"

# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
#DATABASE_URL="postgresql://roo:!ChangeMe!@127.0.0.1:3306/app?serverVersion=15&charset=utf8"
###< doctrine/doctrine-bundle ###

MAILER_DSN=smtp://localhost:1025
```

Dans app et faire les commande suivantes :

```
npm install
composer install
```

Windows :

Se rendre dans app et faire les commandes suivantes :

```
symfony server:start
npm run dev-server
```

Mac / Linux :

Rester dans Challenge-stack-1-Symfony et faire la commande suivante :

```
docker compose up
```
