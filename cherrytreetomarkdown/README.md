# CherryTree to Markdown converter

## Background

During my pen-200 course, I realized that after taking 450 pages of notes using CherryTree that the export features sucked. A process of manually converting my notes to markdown or word file would have sucked even more.

As a programmer, I decided to quickly hack an MVP version of markdown exporter utilizing the XML format of CherryTree.

I share the source code of the exporter in hope that it can help fellow OSCP-students.

## Installation (on debian)


### PHP 8.0
```
sudo apt update
sudo apt install -y lsb-release ca-certificates apt-transport-https software-properties-common
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/sury-php.list
wget -qO - https://packages.sury.org/php/apt.gpg | sudo apt-key add -
sudo apt update

sudo apt install php8.0 php8.0-mbstring php8.0-xml php8.0-exif
```

### Libraries
```
    php composer.phar install
```

## Usage 

```
usage: cherrytomd.php [<options>] [<args>]

Convert unencrypted cherrytree xml file to markdown

OPTIONS
--anchors, -a       Print anchors and anchor links to markdown as html tags
--help, -?          Display this help.
--hmtmlformat, -f   format text using html tags
--title, -t         print node titles

ARGUMENTS
file        CherryTree file (.ctd)
outputDir   Output directory
```

