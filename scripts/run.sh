#!/bin/sh

docker run --interactive --tty \
   --workdir /app \
   --volume $PWD:/app \
   --user $(id -u):$(id -g) \
   vectorapps/php $@
