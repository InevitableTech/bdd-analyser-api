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

echo "Deploy to '$toActive' environment, path: '$newPath', oldPath: '$oldPath'"
echo
if [[ $ENVIRONMENT == 'local' ]]; then
    read -p "==> Press enter to continue"
fi

# Issue, this path is relative to where the script is being executed from. Amend where the script is run from.
echo '==> Clean up path to deploy path '$newPath
rm -rf "$newPath" || true
mkdir -p $newPath
cp -R ./* "$newPath"

echo '==> Setup env file'
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

# Activate new deployment by symlink.
echo '==> Link the new path to current API'
rm -rf "$1/current" || true
ln -s "$newPath" "$1/current"

echo $inactive > "$1/inactive.txt"

# Spin down active api if deployed.
echo '==> Spin down old API'
cd $oldPath
if [[ -f docker-compose.yml ]]; then
    docker-compose -f "docker-compose.yml" -f "docker-compose-$ENVIRONMENT.yml" down
fi

echo '==> Activating new API'
cd $newPath
docker-compose -f "docker-compose.yml" -f "docker-compose-$ENVIRONMENT.yml" up -d api
docker-compose -f docker-compose.yml -f "docker-compose-$ENVIRONMENT.yml" run api php artisan key:generate
docker-compose -f docker-compose.yml -f "docker-compose-$ENVIRONMENT.yml" run api php artisan config:cache

echo '==> Db setup'
make migrate
make seed

echo "Activated deployment server: $toActive"
