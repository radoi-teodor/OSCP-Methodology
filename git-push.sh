#!/bin/bash

rm index.md
rm README.md
rm -rf images
rm -rf files

php8.0 $(pwd)/cherrytreetomarkdown/cherrytomd.php -a -t $(pwd)/OSCP-Methodology.ctd $(pwd)
git add *
git commit -m "${1:-'Default Message'}"
echo -n "Git access token: "
read -s git_access_token
git push https://$git_access_token@github.com/radoi-teodor/OSCP-Methodology.git
