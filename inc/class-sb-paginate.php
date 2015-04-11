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
        $query = $wp_query;
        $options = get_option('sb_options');
        $label = isset($options['paginate']['label']) ? $options['paginate']['label'] : __('Trang', 'sb-theme');
        $previous = isset($options['paginate']['previous_text']) ? $options['paginate']['previous_text'] : '&laquo;';
        $next = isset($options['paginate']['next_text']) ? $options['paginate']['next_text'] : '&raquo;';
        $request = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($request)) {
            $request = self::get_request();
        }
        if(!isset($query) || empty($query) || !is_object($query)) {
            $query = $wp_query;
        }
        $posts_per_page = isset($query->query_vars['posts_per_page']) ? $query->query_vars['posts_per_page'] : get_option('posts_per_page');
        if(1 > $posts_per_page) return;
        $total_page = intval(ceil($query->found_posts / $posts_per_page));
        $current_page = isset($query->query_vars['paged']) ? $query->query_vars['paged'] : '0';
        if(1 > $current_page || $current_page > $total_page) {
            $current_page = self::get_paged();
        }
        $args['current_page'] = $current_page;
        if(1 >= $total_page) return;
        $args['total_page'] = $total_page;
        $result = '';
        $label = trim($label);
        if(!empty($label)) {
            $result .= '<span class="paginate-item label-item">'.$label.'</span>';
        }
        if ($current_page > 1) {
            $result .= '<a class="paginate-item previous-item" href="'.self::get_pagenum_link(array('pagenum' => ($current_page - 1), 'request' => $request)).'" data-paged="'.($current_page - 1).'">'.$previous.'</a>';
        }
        $result .= self::loop_paginate($args);
        if ($current_page < $total_page) {
            $result .= '<a href="'.self::get_pagenum_link(array('pagenum' => ($current_page + 1), 'request' => $request)).'" class="paginate-item next-item" data-paged="'.($current_page + 1).'">'.$next.'</a>';
        }
        return $result;
    }

    public static function show($args = null) {
        $options = get_option('sb_options');
        $style = isset($options['paginate']['style']) ? $options['paginate']['style'] : 'default';
        $border_radius = isset($options['paginate']['border_radius']) ? $options['paginate']['border_radius'] : 'default';
        extract($args, EXTR_OVERWRITE);
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
        $range = isset($options['paginate']['range']) ? $options['paginate']['range'] : 3;;
        // The number of page links to show at beginning and end of pagination.
        $anchor = isset($options['paginate']['anchor']) ? $options['paginate']['anchor'] : 1;
        // The minimum number of page links before ellipsis shows.
        $gap = isset($options['paginate']['gap']) ? $options['paginate']['gap'] : 3;
        $current_page = 1;
        $total_page = $current_page;
        $request = self::get_request();
        extract($args, EXTR_OVERWRITE);
        $hidden_button = '<span class="paginate-item hidden-item">...</span>';
        $result = "";
        $hidden_before = false;
        $hidden_after = false;
        $before_current = $current_page - $range;
        $after_current = $current_page + $range;
        for($i = 1; $i <= $total_page; $i++) {
            if($current_page == $i) {
                $result .= '<span class="paginate-item current-item">'.$i.'</span>';
            } else {
                $count_hidden_button_before = $before_current - ($anchor + 1);
                $count_hidden_button_after = $total_page - ($after_current + 1);
                $show_hidden_button_before = ($i < $before_current && !$hidden_before && $count_hidden_button_before >= $gap) ? true : false;
                $show_hidden_button_after = ($i > $after_current && !$hidden_after && $count_hidden_button_after >= $gap) ? true : false;
                if(1 == $i || $total_page == $i || ($i <= $after_current && $i >= $before_current)) {
                    $result .= '<a class="paginate-item" href="'.self::get_pagenum_link(array('pagenum' => $i, 'request' => $request)).'" data-paged="'.$i.'">'.$i.'</a>';
                } else {
                    if($show_hidden_button_before) {
                        $result .= $hidden_button;
                        $hidden_before = true;
                        $i = $before_current - 1;
                    } elseif($i < $before_current) {
                        $result .= '<a class="paginate-item" href="'.self::get_pagenum_link(array('pagenum' => $i, 'request' => $request)).'" data-paged="'.$i.'">'.$i.'</a>';
                    } elseif($show_hidden_button_after) {
                        $result .= $hidden_button;
                        $hidden_after = true;
                        $i = $total_page - 1;
                    } else {
                        $result .= '<a class="paginate-item" href="'.self::get_pagenum_link(array('pagenum' => $i, 'request' => $request)).'" data-paged="'.$i.'">'.$i.'</a>';
                    }
                }
            }
        }
        return $result;
    }

    private static function get_paged() {
        $paged = intval(get_query_var('paged')) ? intval(get_query_var('paged')) : 1;
        return $paged;
    }
}