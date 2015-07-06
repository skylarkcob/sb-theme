<?php
class SB_Paginate {
    public static function get_request() {
        return SB_Core::get_request();
    }

    public static function get_pagenum_link( $args = array() ) {
        return SB_Core::get_pagenum_link($args);
    }

    public static function build($args = array()) {
        global $wp_query;
        $query = isset($args['query']) ? $args['query'] : $wp_query;
        $options = get_option('sb_options');
        $default_label = isset($options['paginate']['label']) ? $options['paginate']['label'] : __('Trang', 'sb-theme');
        $default_previous = isset($options['paginate']['previous_text']) ? $options['paginate']['previous_text'] : '&laquo;';
        $default_next = isset($options['paginate']['next_text']) ? $options['paginate']['next_text'] : '&raquo;';
        $label = isset($args['label']) ? $args['label'] : $default_label;
        $previous = isset($args['prviouse_text']) ? $args['previous_text'] : $default_previous;
        $next = isset($args['next_text']) ? $args['next_text'] : $default_next;
        $request = isset($args['request']) ? $args['request'] : '';

        if(empty($request)) {
            $request = self::get_request();
        }
        if(!isset($query) || empty($query) || !is_object($query)) {
            $query = $wp_query;
        }
        $posts_per_page = isset($query->query_vars['posts_per_page']) ? $query->query_vars['posts_per_page'] : get_option('posts_per_page');
        if(1 > $posts_per_page) {
            return;
        }
        $total_page = intval(ceil($query->found_posts / $posts_per_page));
        $current_page = isset($query->query_vars['paged']) ? $query->query_vars['paged'] : '0';
        if(1 > $current_page || $current_page > $total_page) {
            $current_page = self::get_paged();
        }
        $args['current_page'] = $current_page;
        if(1 >= $total_page) {
            return;
        }
        $args['total_page'] = $total_page;
        $result = '';
        $label = trim($label);
        if(!empty($label)) {
            $result .= '<span class="paginate-item label-item">' . $label . '</span>';
        }
        if($current_page > 1) {
            $link_href = self::get_pagenum_link(array('pagenum' => ($current_page - 1), 'request' => $request));
            $result .= '<a class="paginate-item previous-item" href="' . $link_href . '" data-paged="' . ($current_page - 1) . '">' . $previous . '</a>';
        }
        $result .= self::loop_paginate($args);
        if($current_page < $total_page) {
            $link_href = self::get_pagenum_link(array('pagenum' => ($current_page + 1), 'request' => $request));
            $result .= '<a href="' . $link_href . '" class="paginate-item next-item" data-paged="' . ($current_page + 1) . '">' . $next . '</a>';
        }
        return $result;
    }

    public static function show($args = array()) {
        $options = get_option('sb_options');
        $default_style = isset($options['paginate']['style']) ? $options['paginate']['style'] : 'default';
        $default_border_radius = isset($options['paginate']['border_radius']) ? $options['paginate']['border_radius'] : 'default';
        $style = isset($args['style']) ? $args['style'] : $default_style;
        $border_radius = isset($args['border_radius']) ? $args['border_radius'] : $default_border_radius;

        $style .= '-style';
        $class = 'pagination loop-pagination sb-paginate';
        $class .= ' '.$style;
        switch($border_radius) {
            case 'circle':
                $class .= ' border-radius-circle';
                break;
            case 'default':
                break;
            case 'none':
                $class .= ' no-border-radius';
                break;
        }
        $class = trim($class);
        echo '<nav class="'.$class.'">';
        echo self::build($args);
        echo '</nav>';
    }

    private static function loop_paginate($args = array()) {
        $options = get_option('sb_options');
        // The number of page links to show before and after the current page.
        $default_range = isset($options['paginate']['range']) ? $options['paginate']['range'] : 3;
        $range = isset($args['range']) ? $args['range'] : $default_range;
        // The number of page links to show at beginning and end of pagination.
        $default_anchor = isset($options['paginate']['anchor']) ? $options['paginate']['anchor'] : 1;
        $anchor = isset($args['anchor']) ? $args['anchor'] : $default_anchor;
        // The minimum number of page links before ellipsis shows.
        $default_gap = isset($options['paginate']['gap']) ? $options['paginate']['gap'] : 3;
        $gap = isset($args['gap']) ? $args['gap'] : $default_gap;
        $current_page = isset($args['current_page']) ? $args['current_page'] : 1;
        $total_page = isset($args['total_page']) ? $args['total_page'] : $current_page;
        $request = isset($args['request']) ? $args['request'] : self::get_request();

        $hidden_button = '<span class="paginate-item hidden-item">' . SB_THEME_THREE_DOT . '</span>';
        $result = '';
        $hidden_before = false;
        $hidden_after = false;
        $before_current = $current_page - $range;
        $after_current = $current_page + $range;
        for($i = 1; $i <= $total_page; $i++) {
            if($current_page == $i) {
                $result .= '<span class="paginate-item current-item">' . $i .'</span>';
            } else {
                $count_hidden_button_before = $before_current - ($anchor + 1);
                $count_hidden_button_after = $total_page - ($after_current + 1);
                $show_hidden_button_before = ($i < $before_current && !$hidden_before && $count_hidden_button_before >= $gap) ? true : false;
                $show_hidden_button_after = ($i > $after_current && !$hidden_after && $count_hidden_button_after >= $gap) ? true : false;
                if(1 == $i || $total_page == $i || ($i <= $after_current && $i >= $before_current)) {
                    $link_href = self::get_pagenum_link(array('pagenum' => $i, 'request' => $request));
                    $result .= '<a class="paginate-item" href="' . $link_href . '" data-paged="' . $i . '">' . $i . '</a>';
                } else {
                    if($show_hidden_button_before) {
                        $result .= $hidden_button;
                        $hidden_before = true;
                        $i = $before_current - 1;
                    } elseif($i < $before_current) {
                        $link_href = self::get_pagenum_link(array('pagenum' => $i, 'request' => $request));
                        $result .= '<a class="paginate-item" href="' . $link_href . '" data-paged="' . $i . '">' . $i . '</a>';
                    } elseif($show_hidden_button_after) {
                        $result .= $hidden_button;
                        $hidden_after = true;
                        $i = $total_page - 1;
                    } else {
                        $link_href = self::get_pagenum_link(array('pagenum' => $i, 'request' => $request));
                        $result .= '<a class="paginate-item" href="' . $link_href . '" data-paged="' . $i . '">' . $i . '</a>';
                    }
                }
            }
        }
        return $result;
    }

    private static function get_paged() {
        return SB_Query::get_paged();
    }
}