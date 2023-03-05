#!/bin/bash

# This file will
# - rollback database changes.
# - point nginx to old location.

# exit when any command fails
set -e

if [[ -z "$1" ]]; then
    echo 'You must provide the base directory for application to deploy'
    exit 1;
fi

# Get which conceptual server needs to be activated.
toActive=$(cat "$1/inactive.txt")

# Copy to correct place.
oldPath="$1/$toActive"

inactive='green'
if [[ $toActive == 'green' ]]; then
    inactive='blue'
fi
currentPath="$1/$inactive"

echo "Rolling back to '$toActive', path: '$oldPath'"

# Rollback db changes.
cd $currentPath
docker-compose -f "docker-compose.yml" -f "docker-compose-$ENVIRONMENT.yml" run api php artisan migrate:rollback

# Switch to old deployment by symlink.
rm -rf "$1/current"
ln -s "$oldPath" "$1/current"

echo $inactive > "$1/inactive.txt"

# Spin down active api if deployed.
cd $currentPath
if [[ -f docker-compose.yml ]]; then
    docker-compose -f "docker-compose.yml" -f "docker-compose-$ENVIRONMENT.yml" down
fi

cd $oldPath
docker-compose -f "docker-compose.yml" -f "docker-compose-$ENVIRONMENT.yml" up -d api

echo 'Rollback complete, activated: '$inactive
