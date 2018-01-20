#!/bin/sh

# ensure that pwd is the root of our project
cd "${0%/*}"

export USER_GID=$( id -g $USER )
echo using group id $USER_GID

chmod -R g+r .
chmod -R g+w storage/ bootstrap/cache/
echo permissions set

docker-compose -f docker-compose.dev.yml up --build $@