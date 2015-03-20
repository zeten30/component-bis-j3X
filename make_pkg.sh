#!/bin/bash

find . -name '*.php' -exec dos2unix {} \;
find . -name '*.xml' -exec dos2unix {} \;

if [ ! -d ./dist ]; then mkdir dist; fi;

version=$(grep '<version>' com_bis.xml | sed -e 's/<[a-z\/]*>//g' | sed -e 's/ *//g')
rm dist/com_bis*.zip
zip -r dist/com_bis-${version}-j3X.zip site admin languages com_bis.xml