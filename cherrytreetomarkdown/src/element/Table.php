<?php

namespace cherrytomd\element;

use cherrytomd\util\MarkDownHelper;
use cherrytomd\util\XmlHelper;
use SimpleXMLElement;

/**
 *
 */
class Table extends OffsetElement {

    /**
     * @param SimpleXMLElement $content
     */
    public function __construct(SimpleXMLElement $content) {
        parent::__construct($content, );
    }

    /**
     * @return string
     */
    public function render(): string {
        $md = "";
        $element=$this->getContent();

        $children = XmlHelper::parseXmlArray($element->children());
        $children = array_reverse($children);
        $cellsInRow = 0;

        $rows = [];
        foreach ($children as $row) {
            $cells = XmlHelper::parseXmlArray($row->children());
            $rows[] = $cells;
            if ($cellsInRow === 0) { // create headers
                $cellsInRow = count($cells);
            }
        }

        $md .= "\n";
        foreach ($rows as $index => $row) {
            $md .= "|";
            if ($index === 1) {
                $md .= str_repeat(" --- |", count($row));
                $md .= "\n|";
            }
            foreach ($row as $cell) {
                $md .= str_replace(["\\", "\n", "|"], ["\\\\", " ", ""], $cell) . "|";
            }
            $md .= "\n";
        }
        return "\n $md \n";
    }
}
