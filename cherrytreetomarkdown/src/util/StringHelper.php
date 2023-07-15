<?php

namespace cherrytomd\util;

namespace cherrytomd\util;

/**
 *
 */
class StringHelper {
    /**
     * @param $str
     * @return array|string|string[]|null
     */
    public static function mb_trim($str) {
        return preg_replace("/^\s+|\s+$/u", "", $str);
    }

    /**
     * @param $original
     * @param $replacement
     * @param $position
     * @param $length
     * @return string
     */
    public static function mb_substr_replace($original, $replacement, $position, $length) {
        $startString = mb_substr($original, 0, $position, "UTF-8");
        $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

        $out = $startString . $replacement . $endString;

        return $out;
    }

    /**
     * @param $str
     * @param int $width
     * @param string $break
     * @param false $cut
     * @return string
     */
    public static function mb_wordwrap($str, $width = 50, $break = "\n", $cut = false) {
        $lines = explode($break, $str);
        foreach ($lines as &$line) {
            $line = rtrim($line);
            if (mb_strlen($line) <= $width) {
                continue;
            }
            $words = explode(' ', $line);
            $line = '';
            $actual = '';
            foreach ($words as $word) {
                if (mb_strlen($actual . $word) <= $width) {
                    $actual .= $word . ' ';
                } else {
                    if ($actual != '') {
                        $line .= rtrim($actual) . $break;
                    }
                    $actual = $word;
                    if ($cut) {
                        while (mb_strlen($actual) > $width) {
                            $line .= mb_substr($actual, 0, $width) . $break;
                            $actual = mb_substr($actual, $width);
                        }
                    }
                    $actual .= ' ';
                }
            }
            $line .= trim($actual);
        }
        return implode($break, $lines);
    }

    /**
     * @param $search
     * @param $replace
     * @param $subject
     * @param int $count
     * @return false|mixed|string
     */
    public static function mb_replace($search, $replace, $subject, &$count = 0) {
        if (!is_array($search) && is_array($replace)) {
            return false;
        }
        if (is_array($subject)) {
            // call mb_replace for each single string in $subject
            foreach ($subject as &$string) {
                $mb_replace = self::mb_replace($search, $replace, $string, $c);
                $string = &$mb_replace;
                $count += $c;
            }
        } elseif (is_array($search)) {
            if (!is_array($replace)) {
                foreach ($search as &$string) {
                    $subject =StringHelper::mb_replace($string, $replace, $subject, $c);
                    $count += $c;
                }
            } else {
                $n = max(count($search), count($replace));
                while ($n--) {
                    $subject = StringHelper::mb_replace(current($search), current($replace), $subject, $c);
                    $count += $c;
                    next($search);
                    next($replace);
                }
            }
        } else {
            $parts = mb_split(preg_quote($search), $subject);
            $count = count($parts) - 1;
            $subject = implode($replace, $parts);
        }
        return $subject;
    }
}
