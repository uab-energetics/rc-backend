#!/bin/sh

COMPOSE_FILE=docker-compose.dev.yml

# handle --down switch
if [ "$1" = "--down" ]; then {
  shift 1
  docker-compose -f $COMPOSE_FILE down $@
  exit 0
}; fi

### Declare functions for later use

run() {
   docker-compose -f $COMPOSE_FILE up $@
}

migrate() {
  docker exec rc-api-dev php artisan migrate -n --seed
}

initVendor() {
  docker exec rc-api-dev composer install --no-dev && \
  docker exec rc-api-dev chown -R --reference=. vendor
}

initialize() {
  # handle the vendor folder not being present
  ls -d vendor > /dev/null 2> /dev/null
  if [ $? = 2 ]; then {
    initVendor && \
    echo initialized vendor folder
  }; fi

  # ensure proper permissions
  chmod -R g+r . && \
  chmod -R g+w storage/ bootstrap/cache/ && \
  echo set permissions

  # handle the --migrate switch
  if [ "$1" = "--migrate" ]; then {
    shift 1 #not currently used but safe to have
    migrate && \
    echo migrated and seeded database
  }; fi
}

# ensure that pwd is the root of our project
cd "${0%/*}"

# Set the USER_{G,U}ID variables based on the current
# directory's owners (not the current user so that
# this script can be run with sudo)
export USER_UID=$( ls -dn . | awk -F " " '{print $3}' )
export USER_GID=$( ls -dn . | awk -F " " '{print $4}' )
echo using user id $USER_UID and group id $USER_GID

# Ensure that the .env exists
ls .env > /dev/null 2> /dev/null
if [ $? = 2 ]; then {
  cp .env.example .env && \
  echo created environment file
}; fi


run --build -d $(echo $@ | sed s/-d// | sed s/--migrate// )
initialize $@
run $( echo $@ | sed s/--migrate// )
