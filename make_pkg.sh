#!/bin/bash

find . -name '*.php' -exec dos2unix {} \;
find . -name '*.xml' -exec dos2unix {} \;

version=$(grep '<version>' com_bis.xml | sed -e 's/<[a-z\/]*>//g' | sed -e 's/ *//g')
rm com_bis*.zip
zip -r com_bis-${version}-j3X.zip site admin languages com_bis.xml
