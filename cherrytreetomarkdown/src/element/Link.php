<?php

namespace cherrytomd\element;

use cherrytomd\element\style\TextStyle;
use cherrytomd\util\MarkDownHelper;
use cherrytomd\util\StringHelper;

/**
 *
 */
class Link extends RichText {
    /**
     * @var string
     */
    private string $link;
    /**
     * @var bool
     */
    private bool $isAnchorLink;

    /**
     * @param string $link
     * @param string $text
     * @param TextStyle $textStyle
     * @param bool $isAnchorLink
     */
    public function __construct(string $link, string $text, TextStyle $textStyle, bool $isAnchorLink=false) {
        parent::__construct($text, $textStyle);
        $this->link = $link;
        $this->isAnchorLink=$isAnchorLink;
    }

    /**
     * @return string
     */
    public function getLink(): string {
        return $this->link;
    }

    /**
     * @return string
     */
    public function render(): string {
        return MarkDownHelper::printLink($this->renderStyles($this->getText()), "#".StringHelper::mb_replace(" ", "_", $this->link));
    }

    /**
     * @return bool
     */
    public function isAnchorLink(): bool {
        return $this->isAnchorLink;
    }
}
