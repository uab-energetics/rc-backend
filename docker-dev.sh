#!/bin/sh

# ensure that pwd is the root of our project
cd "${0%/*}"

export USER_GID=$( id -g $USER )
echo Using group id $USER_GID

ls .env > /dev/null 2> /dev/null
if [ $? = 2 ]; then {
    cp .env.example .env && \
    echo Created environment file
}; fi

chmod -R g+r . && \
chmod -R g+w storage/ bootstrap/cache/ && \
echo Set permissions

run() {
     docker-compose -f docker-compose.dev.yml up $@
}

migrate() {
    docker exec rc-api-dev php artisan migrate -n --seed
}

if [ "$1" = "--migrate" ]; then {
    shift 1
    # filter out any unnecessary -d's
    run --build -d $(echo $@ | sed s/-d//)
    migrate && \
    echo Migrated and Seeded database
    run $@
}; else
    run --build $@
fi
