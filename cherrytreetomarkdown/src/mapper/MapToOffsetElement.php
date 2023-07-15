<?php

namespace cherrytomd\mapper;

use cherrytomd\config\RenderConfig;
use cherrytomd\element\Archor;
use cherrytomd\element\CodeBox;
use cherrytomd\element\File;
use cherrytomd\element\Image;
use cherrytomd\element\OffsetElement;
use cherrytomd\element\Table;
use cherrytomd\util\XmlHelper;
use RuntimeException;
use SimpleXMLElement;

/**
 *
 */
class MapToOffsetElement implements MapToOffsetElementInterface {
    /**
     * @param SimpleXMLElement $element
     * @param RenderConfig $config
     * @param string $nodeId
     * @return OffsetElement|null
     */
    public function mapToOffsetElement(SimpleXMLElement $element, RenderConfig $config, string $nodeId): ?OffsetElement {
        $type=$element->getName();

        return match ($type) {
            "table"=>new Table($element),
            "codebox"=>new CodeBox($element),
            "encoded_png"=>$this->mapFileToElement($element, $config, $nodeId),
        };

        throw new RuntimeException("Unsupported element type: $type");
    }

    /**
     * @param SimpleXMLElement $element
     * @param RenderConfig $config
     * @param string $nodeId
     * @return OffsetElement|null
     */
    public function mapFileToElement(SimpleXMLElement $element, RenderConfig $config, string $nodeId):?OffsetElement {
        $attributes = XmlHelper::parseXmlArray($element->attributes());
        $anchorTag=$attributes['anchor']??null;
        $fileName = $attributes['filename'] ?? null;

        if ($anchorTag!==null) {
            $id="node_{$nodeId}_$anchorTag";

            return new Archor($element, $id);
        }

        if ($fileName!==null) {
            return new File($element, $config->getOutputPath());
        }

        return new Image($element, $config->getOutputPath());
    }
}
