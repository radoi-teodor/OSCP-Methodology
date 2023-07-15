<?php

namespace cherrytomd\element;

use cherrytomd\element\style\TextStyle;
use cherrytomd\util\MarkDownHelper;

/**
 *
 */
class RichText implements Renderable, Length {
    /**
     * @var string|null
     */
    private ?string $text;
    /**
     * @var TextStyle
     */
    private TextStyle $textStyle;
    /**
     * @var int|false
     */
    private int $len;

    /**
     * @param string $text
     * @param TextStyle|null $textStyle
     */
    public function __construct(string $text, ?TextStyle $textStyle) {
        $this->text = $text;
        $this->textStyle=$textStyle;
        $this->len=mb_strlen($text);
        $this->textStyle=$textStyle!==null ? $textStyle : new TextStyle();
    }

    /**
     * @param string $color
     * @return string
     */
    public function get12BitColor(string $color) {
        $r=$this->downscale(substr($color, 1, 4));
        $g=$this->downscale(substr($color, 5, 4));
        $b=$this->downscale(substr($color, 9, 4));

        return "#".$r.$g.$b;
    }

    /**
     * @param string $color
     * @return string
     */
    private function downscale(string $color):string {
        $decimal=hexdec($color);
        return dechex(($decimal/ 65535) * 255);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function renderStyles(string $text) {
        $prefix="";
        $suffix="";


        if ($this->textStyle->isBold()) {
            $prefix="**".$prefix;
            $suffix=$suffix."**";
        }

        if ($this->textStyle->isItalic()) {
            $prefix="<i>".$prefix;
            $suffix=$suffix."</i>";
        }

        if ($this->textStyle->isStrikeThrough()) {
            $prefix="<del>".$prefix;
            $suffix=$suffix."</del>";
        }

        if ($this->textStyle->isUnderline()) {
            $prefix="<ins> ".$prefix;
            $suffix=$suffix." </ins>";
        }

        if ($this->textStyle->getColor()!==null) {
            $color=$this->get12BitColor($this->textStyle->getColor());
            $prefix="<span style='color:$color'>".$prefix;
            $suffix=$suffix."</span>";
        }
        if ($this->textStyle->getBgColor()!==null) {
            $color=$this->get12BitColor($this->textStyle->getBgColor());
            $prefix="<span style='background-color:$color'>".$prefix;
            $suffix=$suffix."</span>";
        }

        return $prefix.$text.$suffix;
    }


    /**
     * @return string
     */
    public function render(): string {
        $text=$this->getText();
        //$text = preg_replace( '/\s+/', ' ', $text );
        $text= str_replace("~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~", "\n --- \n", $text);
        $rows=explode("\n", $text);
        $rows=array_map(fn (string $row) =>ltrim($row), $rows);
        $text=implode("\n", $rows);

        return $this->renderStyles(MarkDownHelper::escapeMarkdown($text));
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text??"";
    }

    /**
     * @return int
     */
    public function getLen(): int {
        return $this->len;
    }
}
