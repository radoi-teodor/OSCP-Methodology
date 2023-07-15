<?php
require_once __DIR__."/vendor/autoload.php";

use cherrytomd\logic\CherryToMD;
use cherrytomd\config\RenderConfig;
use Garden\Cli\Cli;

ini_set('memory_limit', '-1');
ini_set('default_charset', 'UTF-8');
mb_internal_encoding("UTF-8");
mb_regex_encoding('UTF-8');

$cli=new CLI();
$cli->description("Convert unencrypted cherrytree xml file to markdown")
    ->arg("file", 'CherryTree file (.ctd)', true)
    ->arg("outputDir", 'Output directory', true)
    ->opt("anchors:a", "Print anchors and anchor links to markdown as html tags", false, 'bool')
    ->opt("title:t", "print node titles", false, "bool")
    ->opt("hmtmlformat:f", "format text using html tags", false, "bool");

$args=$cli->parse($argv, true);
$file=$args->getArg("file");
$xml = simplexml_load_file($file);

$outputDir=$args->getArg("outputDir");
$converter = new CherryToMD(new RenderConfig(
    $outputDir,
    $args->getOpt('anchors')??false,
    $args->getOpt('title')??false,
    $args->getOpt('htmlformat')??false
));

if ($outputDir===false){
    echo "Could not read input file\n";
    die();
}

$md = $converter->convertToMarkdown($xml);
$report = fopen($outputDir . "/index.md", "w") or die("Unable to open file!");
fwrite($report, $md);
fclose($report);
