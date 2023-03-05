#!/bin/bash

# RUN THIS FILE FROM THE ROOT DIRECTORY OF THIS PROJECT ONLY.

# This file will
# - copy all files to another appropriate location.
# - migrate database changes.
# - point nginx to new location.

# exit when any command fails
set -e

if [[ -z "$1" ]]; then
    echo 'You must provide the base directory for application to deploy';
    exit 1;
fi

currentPath="$1/current"

[ -z "$ENVIRONMENT" ] && echo "Need to set ENVIRONMENT" && exit 1;
[ -z "$DB_DATABASE" ] && echo "Need to set DB_DATABSE" && exit 1;
[ -z "$DB_USERNAME" ] && echo "Need to set DB_USERNAME" && exit 1;
[ -z "$DB_PASSWORD" ] && echo "Need to set DB_PASSWORD" && exit 1;
[ -z "$AUTH_TOKEN" ] && echo "Need to set AUTH_TOKEN" && exit 1;

# Default values if not provided.
DB_CONNECTION=${DB_CONNECTION:="mysql"}
DB_PORT=${DB_PORT:="3306"}
DB_HOST=${DB_HOST:="database"}

# Create server file.
if [[ ! -f "$1/inactive.txt" ]]; then
    echo 'green' > "$1/inactive.txt";
    mkdir -p "$1/green"
    mkdir -p "$1/blue"
fi

# Get which conceptual server needs to be activated.
toActive=$(cat "$1/inactive.txt")

# Copy to correct place.
newPath="$1/$toActive"

inactive='green'
if [[ $toActive == 'green' ]]; then
    inactive='blue'
fi
oldPath="$1/$inactive"

echo "Deploy to '$toActive' environment, path: '$newPath'"
echo
if [[ $ENVIRONMENT == 'local' ]]; then
    read -p "==> Press enter to continue"
fi

# Issue, this path is relative to where the script is being executed from. Amend where the script is run from.
rm -rf "$newPath"
mkdir -p $newPath
cp -R ./* "$newPath"

# Copy env files.
cp "./.env.example" "$newPath/.env"

# Setup database creds before this.
echo "DB_CONNECTION=$DB_CONNECTION" >> "$newPath/.env"
echo "DB_HOST=$DB_HOST" >> "$newPath/.env"
echo "DB_PORT=$DB_PORT" >> "$newPath/.env"
echo "DB_DATABASE=$DB_DATABASE" >> "$newPath/.env"
echo "DB_USERNAME=$DB_USERNAME" >> "$newPath/.env"
echo "DB_PASSWORD=$DB_PASSWORD" >> "$newPath/.env"
echo "AUTH_TOKEN=$AUTH_TOKEN" >> "$newPath/.env"

# Migrate db changes.
# Swap this for a docker-compose run.
cd $newPath
docker-compose -f docker-compose.yml -f "docker-compose-$ENVIRONMENT.yml" run api php artisan key:generate
docker-compose -f docker-compose.yml -f "docker-compose-$ENVIRONMENT.yml" run api php artisan migrate

# Activate new deployment by symlink.
rm -rf "$1/current"
ln -s "$newPath" "$1/current"

echo $inactive > "$1/inactive.txt"

docker-compose -f "$oldPath/docker-compose.yml" -f "$oldPath/docker-compose-$ENVIRONMENT.yml" down -d api
docker-compose -f "$newPath/docker-compose.yml" -f "$newPath/docker-compose-$ENVIRONMENT.yml" up -d api

echo "Activated deployment server: $toActive"
