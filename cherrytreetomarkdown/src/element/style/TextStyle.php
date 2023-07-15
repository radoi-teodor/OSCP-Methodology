<?php

namespace cherrytomd\element\style;

/**
 *
 */
class TextStyle {
    /**
     * @var bool
     */
    private bool $isBold;
    /**
     * @var bool
     */
    private bool $isItalic;
    /**
     * @var bool
     */
    private bool $isUnderline;
    /**
     * @var bool
     */
    private bool $isStrikeThrough;
    /**
     * @var string|null
     */
    private ?string $color;
    /**
     * @var string|null
     */
    private ?string $bgColor;

    /**
     * @param bool $isBold
     * @param bool $isItalic
     * @param bool $isUnderline
     * @param bool $isStrikeThrough
     * @param string|null $color
     * @param string|null $bgColor
     */
    public function __construct(bool $isBold=false, bool $isItalic=false, bool $isUnderline=false, bool $isStrikeThrough=false, ?string $color=null, ?string $bgColor=null) {
        $this->isBold = $isBold;
        $this->isItalic = $isItalic;
        $this->isUnderline = $isUnderline;
        $this->isStrikeThrough = $isStrikeThrough;
        $this->color = $color;
        $this->bgColor = $bgColor;
    }

    /**
     * @return bool
     */
    public function isBold(): bool {
        return $this->isBold;
    }

    /**
     * @return bool
     */
    public function isItalic(): bool {
        return $this->isItalic;
    }

    /**
     * @return bool
     */
    public function isUnderline(): bool {
        return $this->isUnderline;
    }

    /**
     * @return bool
     */
    public function isStrikeThrough(): bool {
        return $this->isStrikeThrough;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string {
        return $this->color;
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string {
        return $this->bgColor;
    }
}
