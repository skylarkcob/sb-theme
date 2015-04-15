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
}