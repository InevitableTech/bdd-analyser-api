#!/bin/bash

# This file will
# - rollback database changes.
# - point nginx to old location.

if [[ -z "$1" ]]; then
    echo 'You must provide the base directory for application to deploy'
    exit(1)
fi

# Get which conceptual server needs to be activated.
toActive=$(cat "$1/inactive.txt")

# Rollback db changes.
docker-compose -f "$newPath/docker-compose.yml" -f "$newPath/docker-compose-$ENVIRONMENT.yml" run api php artisan migrate:rollback

# Switch to old deployment by symlink.
rm -rf "$1/current"
ln -s "$newPath" "$1/current"

# Store the deactivated conceptual server in file for next time.
inactive='green'
if [[ $toActive == 'green' ]]; then
    inactive='blue'
fi

echo $inactive > "$1/inactive.txt"
