#!/bin/bash

# EXECUTE FROM ROOT DIRECTORY

# Drop the current database
php ./bin/console doctrine:database:drop --force

# Create a new database
php ./bin/console doctrine:database:create

# Run migrations
php ./bin/console doctrine:migrations:migrate

# Reload new fixture
php ./bin/console doctrine:fixtures:load

echo "Database reset complete"