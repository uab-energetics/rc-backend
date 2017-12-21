#!/bin/bash
chmod -R 575 .
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R $1 .
composer dumpautoload -o
