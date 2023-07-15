<?php

namespace cherrytomd\element;

use cherrytomd\util\MarkDownHelper;
use cherrytomd\util\StringHelper;
use SimpleXMLElement;

class Archor extends OffsetElement {
    private string $anchorTag;

    public function __construct(SimpleXmlElement $content, string $anchorTag) {
        parent::__construct($content);
        $this->anchorTag = $anchorTag;
    }

    public function render(): string {
        $id = StringHelper::mb_replace(" ", "_", $this->anchorTag);
        //https://stackoverflow.com/questions/5319754/cross-reference-named-anchor-in-markdown/7335259#7335259
        $html ="<a name='$id'></a>\n";
        return "!".MarkDownHelper::printLink("", "./images/anchor.png").$html;
    }
}
