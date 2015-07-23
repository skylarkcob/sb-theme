<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_CSS {
    public static function build($elements, $properties) {
        $elements = (array)$elements;
        $elements = array_filter($elements);
        $properties = (array)$properties;
        $properties = array_filter($properties);
        $before = '';
        foreach($elements as $element) {
            $before .= $element . ',';
        }
        $before = trim($before, ',');
        $after = '';
        foreach($properties as $key => $property) {
            $after .= $key . ':' . $property . ';';
        }
        $after = trim($after, ';');
        return $before . '{' . $after . '}';
    }

    public static function shorten_hex($content) {
        $content = preg_replace('/(?<![\'"])#([0-9a-z])\\1([0-9a-z])\\2([0-9a-z])\\3(?![\'"])/i', '#$1$2$3', $content);
        return $content;
    }

    public static function shorten_zero($content) {
        $before = '(?<=[:(, ])';
        $after = '(?=[ ,);}])';
        $units = '(em|ex|%|px|cm|mm|in|pt|pc|ch|rem|vh|vw|vmin|vmax|vm)';
        $content = preg_replace('/'.$before.'(-?0*(\.0+)?)(?<=0)'.$units.$after.'/', '\\1', $content);
        $content = preg_replace('/'.$before.'\.0+'.$after.'/', '0', $content);
        $content = preg_replace('/'.$before.'(-?[0-9]+)\.0+'.$units.'?'.$after.'/', '\\1\\2', $content);
        $content = preg_replace('/'.$before.'-?0+'.$after.'/', '0', $content);
        return $content;
    }

    public static function strip_white_space($content) {
        $content = preg_replace('/^\s*/m', '', $content);
        $content = preg_replace('/\s*$/m', '', $content);
        $content = preg_replace('/\s+/', ' ', $content);
        $content = preg_replace('/\s*([\*$~^|]?+=|[{};,>~]|!important\b)\s*/', '$1', $content);
        $content = preg_replace('/([\[(:])\s+/', '$1', $content);
        $content = preg_replace('/\s+([\]\)])/', '$1', $content);
        $content = preg_replace('/\s+(:)(?![^\}]*\{)/', '$1', $content);
        $content = preg_replace('/\s*([+-])\s*(?=[^}]*{)/', '$1', $content);
        $content = preg_replace('/;}/', '}', $content);
        return trim($content);
    }

    public static function minify($files) {
        $buffer = '';
        if(is_array($files)) {
            foreach($files as $file) {
                $buffer .= @file_get_contents($file);
            }
        } else {
            $buffer = @file_get_contents($files);
        }
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        $buffer = self::shorten_hex($buffer);
        $buffer = self::shorten_zero($buffer);
        $buffer = self::strip_white_space($buffer);
        return $buffer;
    }
}