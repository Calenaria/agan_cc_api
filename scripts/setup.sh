#!/bin/bash

# EXECUTE FROM ROOT DIRECTORY

php ./bin/console doctrine:database:create
php ./bin/console doctrine:migrations:migrate
php ./bin/console doctrine:fixtures:load

echo "Setup complete"