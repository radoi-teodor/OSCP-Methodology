<?php

namespace cherrytomd\element;

use cherrytomd\util\MarkDownHelper;
use SimpleXMLElement;

/**
 *
 */
class Image extends OffsetElement {
    /**
     * @var string
     */
    private string $outputPath;

    /**
     * @param SimpleXMLElement $element
     * @param string $outputPath
     */
    public function __construct(SimpleXmlElement $element, string $outputPath) {
        parent::__construct($element);
        $this->outputPath = $outputPath;
    }

    /**
     * @return string
     */
    protected function getFileName() {
        return "";
    }

    /**
     * @return string
     */
    protected function getPrefix() {
        return "!";
    }

    /**
     * @return string
     */
    public function render(): string {
        $element=$this->getContent();
        $encodedImage = strval($element);
        $id = md5($encodedImage);
        $image = base64_decode($element);
        assert($image !==false);

        $filePath = "/images/{$id}.png";
        $file = fopen($this->outputPath . "$filePath", "w") or die("Unable to open file!");
        fwrite($file, $image);
        fclose($file);

        $type = exif_imagetype($this->outputPath . "$filePath");
        if ($type === false) { // not valid image
            echo "WARN: BROKEN IMAGE";
            return "";
        }

        return $this->getPrefix().MarkDownHelper::printLink("", ".$filePath");
    }
}
