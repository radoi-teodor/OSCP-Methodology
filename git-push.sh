#!/bin/bash

rm index.md 2>/dev/null
rm README.md 2>/dev/null
rm -rf images 2>/dev/null
rm -rf files 2>/dev/null

php8.0 $(pwd)/cherrytreetomarkdown/cherrytomd.php -a -t $(pwd)/OSCP-Methodology.ctd $(pwd)
git add -A
git commit -m "${1:-'Default Message'}"
git push
