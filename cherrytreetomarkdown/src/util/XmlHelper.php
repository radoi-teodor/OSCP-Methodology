<?php

namespace cherrytomd\util;

/**
 *
 */
class XmlHelper {
    /**
     * @param $array
     * @return array
     */
    public static function parseXmlArray($array): array {
        if ($array === null) {
            return [];
        }
        $array = current($array);
        if (!is_array($array)) {
            return [$array];
        }

        return $array;
    }
}
