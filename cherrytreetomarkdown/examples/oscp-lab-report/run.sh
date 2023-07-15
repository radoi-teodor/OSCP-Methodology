#!/bin/bash
php ../../cherrytomd.php -a -t "./lab_report.ctd" "./markdownOutput"
cat documentmeta.yml > markdownOutput/report.md
cat markdownOutput/index.md >> markdownOutput/report.md
cd markdownOutput
pandoc --from markdown --pdf-engine=lualatex --template ../eisvogel.latex --listings report.md --toc --toc-depth=4 --highlight-style=tango -s -o report.pdf
