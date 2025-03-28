#!/bin/bash

./update_local_db.sh
rsync -avz --progress arash@arashexpress.ru:/var/www/webapp/public/upload ./public