<?php

namespace cherrytomd\element;

use cherrytomd\util\XmlHelper;
use SimpleXMLElement;

/**
 *
 */
class File extends Image {
    /**
     * @param SimpleXMLElement $element
     * @param string $outputPath
     */
    public function __construct(SimpleXmlElement $element, string $outputPath) {
        parent::__construct($element, $outputPath);
    }

    /**
     * @return mixed|string|null
     */
    protected function getFileName() {
        $attributes = XmlHelper::parseXmlArray($this->getContent()->attributes());
        return $attributes['filename'] ?? null;
    }

    /**
     * @return string
     */
    protected function getPrefix() {
        return "";
    }
}
