#!/bin/sh

docker run --interactive --tty \
   --volume $PWD:/app \
   --user $(id -u):$(id -g) \
   composer install --ignore-platform-reqs --no-scripts