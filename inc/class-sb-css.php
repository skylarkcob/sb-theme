<?php
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
}