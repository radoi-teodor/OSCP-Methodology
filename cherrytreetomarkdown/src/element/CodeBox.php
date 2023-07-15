<?php

namespace cherrytomd\element;

use cherrytomd\util\StringHelper;
use SimpleXMLElement;

/**
 *
 */
class CodeBox extends OffsetElement {
    /**
     * @param SimpleXMLElement $element
     */
    public function __construct(SimpleXMLElement $element) {
        parent::__construct($element);
    }

    /**
     * @return string
     */
    public function render(): string {
        $codeBox=strval($this->getContent());
        assert(is_string($codeBox));
        if (mb_strlen($codeBox) === 0) {
            return "";
        }

        // Fix nested code box errors
        $codeBox =StringHelper::mb_replace("```", "", $codeBox);
        // fix utf8 errors for pandoc
        $codeBox = mb_convert_encoding($codeBox, 'UTF-8', 'UTF-8');
        $codeBox=StringHelper::mb_replace('�', "", $codeBox);
        // Line length cannot exceed n characters
        $codeBox=StringHelper::mb_wordwrap($codeBox, 70, "\n", true);
        return "\n```\n $codeBox \n ```\n";
    }
}
