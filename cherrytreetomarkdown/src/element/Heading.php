<?php

namespace cherrytomd\element;

use cherrytomd\element\style\TextStyle;
use cherrytomd\util\MarkDownHelper;

/**
 *
 */
class Heading extends RichText {
    /**
     * @var int
     */
    private int $scale;

    /**
     * @param int $scale
     */
    public function __construct(string $text, int $scale) {
        parent::__construct($text, new TextStyle(false, false, false, false, null, null));
        $this->scale = $scale;
    }

    /**
     * @return int
     */
    public function getScale(): int {
        return $this->scale;
    }

    /**
     * @return string
     */
    public function render(): string {
        if (mb_strlen($this->getText())===0) {
            return "";
        }
        return MarkDownHelper::printTitle($this->renderStyles($this->getText()), $this->getScale());
    }
}
