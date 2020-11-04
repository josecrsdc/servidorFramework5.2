#!/bin/bash

nombreProyecto=$(cat package.json | grep "name" | cut -d ":" -f2 | tr -d '"' | tr -d ',' | cut -c2-)

rm -rf C:/xampp/htdocs/$nombreProyecto
rm -rf C:/xampp/php-servidor/$nombreProyecto

mkdir -p C:/xampp/htdocs/$nombreProyecto
cp -r ./dist/web/* C:/xampp/htdocs/$nombreProyecto

mkdir -p C:/xampp/php-servidor/$nombreProyecto
cp -r ./dist/php/* C:/xampp/php-servidor/$nombreProyecto