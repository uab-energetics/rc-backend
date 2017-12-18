#!/bin/bash
chmod -R 755 .
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
composer dumpautoload -o