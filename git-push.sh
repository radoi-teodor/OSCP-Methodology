#!/bin/bash
php8.0 $(pwd)/cherrytreetomarkdown/cherrytomd.php -a -t $(pwd)/OSCP-Methodology.ctd $(pwd)
git add *
git commit -m ${1:-"Default Message"}
read -s git_access_token
echo $git_access_token
