<?php

namespace cherrytomd\mapper;

use cherrytomd\config\RenderConfig;
use cherrytomd\element\Heading;
use cherrytomd\element\Link;
use cherrytomd\element\RichText;
use cherrytomd\element\style\TextStyle;
use cherrytomd\util\XmlHelper;
use SimpleXMLElement;

/**
 *
 */
class MapToRichText implements MapToRichTextInterface {
    /**
     * @param SimpleXMLElement $element
     * @param RenderConfig $config
     * @param int $level
     * @return RichText
     */
    public function mapToRichText(SimpleXMLElement $element, RenderConfig $config, int $level): RichText {
        $attributes=XmlHelper::parseXmlArray($element->attributes());
        $link=$attributes['link']?? null;
        $scale=$attributes['scale']??null;
        $weight=$attributes['weight']?? null;
        $color=$attributes['foreground']??null;
        $elementStyle=$attributes['style']??null;
        $bgColor=$attributes['background']??null;
        $underline=$attributes['underline']??null;
        $strikethrough=$attributes['strikethrough']??null;

        $style= $config->isTextFormatting() ? new TextStyle(
            $weight==="heavy",
            $elementStyle==='italic',
            $underline==="single",
            $strikethrough==="true",
            $color,
            $bgColor
        ) : new TextStyle($weight==="heavy");

        $elementText=strval($element)??"";

        if ($link!==null) {
            $contentArray = explode(' ', $link);
            $type = $contentArray[0];
            $content = $contentArray[1];
            $isNodeLink=substr_count($link, " ")===2;
            return match ($type) {
                "file", "fold" => new Link(base64_decode($content), $elementText, $style),
                "webs", => new Link($content, $elementText, $style),
                "node" => new Link($link, $elementText, $style, $isNodeLink),
                default => new RichText($elementText, $style),
            };
        }



        if ($scale!==null) {
            if (in_array($scale, ["sub","sup"])) {
                return new RichText($elementText, $style);
            }

            // apply scale of element to current heading level
            if ($scale !== 0) {
                $scaleNumbersGroups = array_filter(preg_split("/\D+/", $scale));
                $scale = reset($scaleNumbersGroups) + $level;
            }
            // do not increase scale by 2 in subtrees
            if ($scale > 0) {
                $scale -= 1;
            }


            if (mb_strlen($elementText) === 0) {
                return new RichText("", $style);
            }


            if ($scale > 0) {
                return new Heading($elementText, $scale);
            }

            return new RichText($elementText, $style);
        }

        return new RichText($elementText, $style);
    }
}
