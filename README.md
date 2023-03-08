# BDD Analyser API

The API that will power the BDD analyser console and receive analysis reports from the CLI tool. This project is built with:

- PHP8.1
- Laravel 10
- Laravel Octane using Swoole

## Running app

Setup .env config

docker-compose up -d

## Generating API docs

To generate docs run the following command

```
make artisan a="docs:generate v1"
```

This will generate documentation for version 1. To view the documentation visit `http://localhost:8000/docs/index.html`.
