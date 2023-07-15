<?php

namespace cherrytomd\logic;

use cherrytomd\config\RenderConfig;
use cherrytomd\element\Node;
use SimpleXMLElement;

/**
 *
 */
class CherryToMD {
    /**
     * @var RenderConfig
     */
    private RenderConfig $renderConfig;

    /**
     * @param RenderConfig $config
     */
    public function __construct(RenderConfig $config) {
        $this->renderConfig=$config;
        $outputDir=$config->getOutputPath();
        mkdir($outputDir);
        mkdir("$outputDir/images");
        mkdir("$outputDir/files");
        copy(__DIR__."/ct_anchor.png", "$outputDir/images/anchor.png");
    }

    /**
     * @param SimpleXMLElement $element
     * @param int $level
     * @return string
     */
    public function convertToMarkdown(SimpleXMLElement $element, $level = 0): string {
        $node=new Node($element, $level, $this->renderConfig);
        return $node->render();
    }
}
