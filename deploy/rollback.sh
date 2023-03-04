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
php "$1/current/artisan" migrate:rollback

# Switch to old deployment by symlink.
rm -rf "$1/current"
ln -s "$newPath" "$1/current"

# Store the deactivated conceptual server in file for next time.
echo $(if $toActive == 'green'; then echo 'blue'; else echo 'green';) > "$1/inactive.txt"
