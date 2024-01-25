# Simple RESTful API simulating shopping carts

## Setup

Requires Docker and docker-compose to be installed

```
1. git clone git@github.com:Calenaria/agan_cc_api.git OR git clone https://github.com/Calenaria/agan_cc_api.git
2. cd agan_cc_api
3. docker-compose up -d
4. docker exec -it agan_cc_app bash
5. composer install
6. bash ./scripts/setup.sh
```

This will clone and setup the project. The setup script will create the database and execute migrations.

## Info

Base URL is 'http://localhost:12001/api/v1'
Collections (GET, POST) are plural 
Singular resources (GET, PUT, DELETE) in singular

## Features

- full CRUD API
- uses annotations on Doctrine entities to define endpoints
- use 'resolveReferences' to resolve entity associations
- supports basic filtering via query parameters (example: http://localhost:12001/api/v1/articles?basePrice-gt=2300)
- more operators: 'eq', 'gt', 'lt', 'neq', 'gte', 'lte'
