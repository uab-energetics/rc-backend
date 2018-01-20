#!/bin/sh

# ensure that pwd is the root of our project
cd "${0%/*}"

export USER_GID=$( id -g $USER )
echo using group id $USER_GID

chmod -R g+r .
chmod -R g+w storage/ bootstrap/cache/
echo permissions set

run() {
     docker-compose -f docker-compose.dev.yml up $@
}

migrate() {
    docker exec rc-api-dev php artisan migrate -n --seed
}

if [ "$1" = "--migrate" ]; then {
    shift 1
    # filter out any unnecessary -d's
    run --build $(echo $@ | sed s/\s\?-d//)
    migrate && \
    echo Migrated and Seeded database
    run $@
}; else
    run --build $@
fi