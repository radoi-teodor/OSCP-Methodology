<?php

namespace cherrytomd\config;

class RenderConfig {
    private bool $printAnchors;
    private bool $printNodeTitles;
    private bool $textFormatting;
    private string $outputPath;

    /**
     * @param bool $printAnchors
     * @param bool $printNodeTitles
     */
    public function __construct(string $outputPath, bool $printAnchors, bool $printNodeTitles, bool $textFormatting) {
        $this->outputPath = $outputPath;
        $this->printAnchors = $printAnchors;
        $this->printNodeTitles = $printNodeTitles;
        $this->textFormatting=$textFormatting;
    }

    /**
     * @return bool
     */
    public function isPrintAnchors(): bool {
        return $this->printAnchors;
    }

    /**
     * @return bool
     */
    public function isPrintNodeTitles(): bool {
        return $this->printNodeTitles;
    }

    /**
     * @return string
     */
    public function getOutputPath(): string {
        return $this->outputPath;
    }

    /**
     * @return bool
     */
    public function isTextFormatting(): bool {
        return $this->textFormatting;
    }
}
