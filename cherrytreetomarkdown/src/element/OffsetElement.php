<?php

namespace cherrytomd\element;

use cherrytomd\util\XmlHelper;
use SimpleXMLElement;

/**
 *
 */
class OffsetElement implements Renderable, Length {
    /**
     * @var SimpleXMLElement
     */
    private SimpleXMLElement $content;

    /**
     * @param SimpleXMLElement $content
     */
    public function __construct(SimpleXMLElement $content) {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getCharOffset(): int {
        return XmlHelper::parseXmlArray($this->content->attributes())['char_offset'];
    }

    /**
     * @return SimpleXMLElement
     */
    public function getContent(): SimpleXMLElement {
        return $this->content;
    }


    /**
     * @return string
     */
    public function render(): string {
        throw new RuntimeException("Render method must be implemented on element");
    }

    /**
     * @return int
     */
    public function getLen(): int {
        return 1;
    }
}
