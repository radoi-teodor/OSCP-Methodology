<?php

namespace cherrytomd\mapper;

use cherrytomd\config\RenderConfig;
use cherrytomd\element\RichText;
use SimpleXMLElement;

/**
 *
 */
interface MapToRichTextInterface {
    /**
     * @param SimpleXMLElement $element
     * @param RenderConfig $config
     * @param int $level
     * @return RichText
     */
    public function mapToRichText(SimpleXMLElement $element, RenderConfig $config, int $level):RichText;
}
