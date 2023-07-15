<?php

namespace cherrytomd\util;

/**
 *
 */
class MarkDownHelper {
    /**
     * @param string|null $text
     * @param bool $skipTitleChars
     * @return string
     */
    public static function escapeMarkdown(?string $text, bool $skipTitleChars=false): string {
        if ($text === null) {
            return "";
        }

        $text =StringHelper::mb_replace([
            '\\','\#', '-', '*', '+', '`', '.', '[', ']', '(', ')', '!', '&', '<', '>', '_', '{', '}',], [
            '\\\\','\#', '\-', '\*', '\+', '\`', '\.', '\[', '\]', '\(', '\)', '\!', '\&', '\<', '\>', '\_', '\{', '\}',
        ], $text);

        return StringHelper::mb_replace(["▸", "•", "◇", "▪", "→", "⇒"], ['* ', '- ', "- ", "- ", "- ", "- "], $text);
    }


    /**
     * @param string $title
     * @param int $level
     * @return string
     */
    public static function printTitle(string $title, int $level): string {
        if(strlen($title) >= 1 && empty(trim($title))) {
            return $title;
        }
        $title = StringHelper::mb_replace("\n", " ", $title);
        $title = StringHelper::mb_replace("\\", "\\\\", $title);
        $title = StringHelper::mb_replace("#", "\#", $title);
        return "\n\n".str_repeat("#", $level) . " $title \n\n";
    }

    /**
     * @param string $text
     * @param string $target
     * @return string
     */
    public static function printLink(string $text, string $target) {
        $text = "[".StringHelper::mb_replace(["\\","[","]"], ["\\\\","\[","\]"], $text)."]";
        $target = "(".StringHelper::mb_replace(["\\","(",")","\\"], ["\\\\","\(,\)"], $target).")";
        return "$text$target\n";
    }
}
