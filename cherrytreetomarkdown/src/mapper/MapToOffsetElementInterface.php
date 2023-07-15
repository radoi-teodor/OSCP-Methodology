<?php

namespace cherrytomd\mapper;

use cherrytomd\config\RenderConfig;
use cherrytomd\element\OffsetElement;
use SimpleXMLElement;

/**
 *
 */
interface MapToOffsetElementInterface {
    /**
     * @param SimpleXMLElement $element
     * @param RenderConfig $config
     * @param string $nodeId
     * @return OffsetElement|null
     */
    public function mapToOffsetElement(SimpleXMLElement $element, RenderConfig $config, string $nodeId): ?OffsetElement;
}
