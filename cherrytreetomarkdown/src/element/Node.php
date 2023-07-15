<?php

namespace cherrytomd\element;

use cherrytomd\config\RenderConfig;
use cherrytomd\mapper\MapToOffsetElement;
use cherrytomd\mapper\MapToRichText;
use cherrytomd\util\MarkDownHelper;
use cherrytomd\util\XmlHelper;
use SimpleXMLElement;

/**
 *
 */
class Node implements Renderable {
    /**
     * @var SimpleXMLElement
     */
    private SimpleXMLElement $content;
    /**
     * @var int
     */
    private int $level;
    /**
     * @var RenderConfig
     */
    private RenderConfig $config;

    /**
     * @var Renderable[]
     */
    private array $elements;

    /**
     * @param SimpleXMLElement $content
     * @param int $level
     */
    public function __construct(SimpleXMLElement $content, int $level, RenderConfig $config) {
        $this->content = $content;
        $this->level = $level;
        $this->config = $config;
        $this->elements=[];

        $attributes = XmlHelper::parseXmlArray($content->attributes());
        $id = $attributes['unique_id'] ?? null;
        $children = $content->children();
        $richTextMapper = new MapToRichText();
        $offsetElementMapper = new MapToOffsetElement();

        /** @var  OffsetElement[] */
        $offsetElements=[];
        /** @var RichText[] */
        $richTexts=[];
        /** @var Renderable[] */
        $nodes=[];
        // Group children by element type
        foreach ($children as $child) {
            $childName = $child->getName();
            if ($childName === "rich_text") {
                $richTexts[] = $richTextMapper->mapToRichText($child, $this->config, $this->level);
            } elseif (in_array($child->getName(), ['table', 'codebox', 'encoded_png'])) {
                $offsetElements[] = $offsetElementMapper->mapToOffsetElement($child, $this->config, $id);
            } elseif ($childName === "node") {
                $nodes[] = new Node($child, $this->level + 1, $this->config);
            }
        }

        // append elements to elements array in correct order
        $offset = 0;
        while (count($offsetElements) != 0 && !count($richTexts) == 0) {
            $currentOffsetElement = array_filter($offsetElements, fn (OffsetElement $e) => $offset === $e->getCharOffset());
            $currentElement = count($currentOffsetElement) > 0 ? array_shift($currentOffsetElement) : array_shift($richTexts);

            assert($currentElement!==null);
            $this->elements[] = $currentElement;
            $len = $currentElement->getLen();
            $offset += $len;

            if ($currentElement instanceof OffsetElement) {
                unset($offsetElements[array_search($currentElement, $offsetElements)]);
            }
        }

        foreach ($offsetElements as $offsetElement){
            if ($offsetElement->getCharOffset()<$offset){
                throw new \RuntimeException("ERROR: Corrupted element offsets on node $id");
            }
        }

        $this->elements = array_merge($this->elements, $richTexts);
        $this->elements = array_merge($this->elements, $offsetElements);
        $this->elements = array_merge($this->elements, $nodes);
    }

    /**
     * @return SimpleXMLElement
     */
    public function getContent(): SimpleXMLElement {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getLevel(): int {
        return $this->level;
    }


    /**
     * @return string
     */
    public function render(): string {
        $md = "";
        $attributes = XmlHelper::parseXmlArray($this->content->attributes());

        if ($this->config->isPrintNodeTitles()) {
            $md = MarkDownHelper::printTitle($attributes['name'] ?? "", $this->level);
        }

        foreach ($this->elements as $element) {
            // if anchor linking is not allowed
            if (!$this->config->isPrintAnchors()) {
                if ($element instanceof Archor) {
                    continue;
                }

                if ($element instanceof Link && $element->isAnchorLink()) {
                    $richText = new RichText($element->getText(), null);
                    $richText->render();
                    continue;
                }
            }
            $md .= $element->render();
        }

        return $md;
    }

    /**
     * @return Renderable[]
     */
    public function getElements(): array {
        return $this->elements;
    }
}
