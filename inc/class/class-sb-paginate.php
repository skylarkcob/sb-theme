<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Paginate {
    public static function build($args = null) {
        $defaults = array(
            'title'		=> 'Pages',
            'query'		=> null
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        if(is_404()) {
            return;
        }
        $currentPage = null;
        $totalPage = null;
        if(empty($query)) {
            global $wp_query;
            $query = $wp_query;
            $default_query = true;
        }
        $currentPage = self::get_paged();
        $title .= ':';
        if($default_query) {
            $ppp = intval($query->query_vars['posts_per_page']);
        }
        else {
            $ppp = intval($query->query['posts_per_page']);
			if(empty($ppp)) {
				$ppp = get_option('posts_per_page');
			}
        }
        if($ppp < 1) {
            return;
        }
        $totalPage = intval(ceil($query->found_posts / $ppp));
        if($totalPage <= 1) {
            return;
        }
        $paginateResult = '<div id="sPaginate" class="spaginate paginate col-xs-12 no-padding"><div class="spaginate-inner paginate-inner"><span class="spaginate-title paginate-title">'.$title.'</span>';

        if ($currentPage > 1) {
            $paginateResult .= '<a class="spaginate-prev prev page-item" href="'.get_pagenum_link($currentPage - 1).'">&laquo;</a>';
        }
        $paginateResult .= self::build_list_item(1, $totalPage, $currentPage);
        if ($currentPage < $totalPage) {
            $paginateResult .= "<a href='" . get_pagenum_link($currentPage + 1) . "' class='spaginate-next next page-item'>&raquo;</a>";
        }
        $paginateResult .= "</div></div>";
        echo $paginateResult;
    }

    public static function show($args = null) {
        self::build($args);
    }



    public static function build_list_item($intStart, $totalPage, $currentPage) {
        $pageHidden = '<span class="spaginate-hidden hidden">...</span>';
        $linkResult = "";
        $hiddenBefore = false;
        $hiddenAfter = false;
        for ($i = $intStart; $i <= $totalPage; $i++) {
            if($currentPage === intval($i)) {
                $linkResult .= '<span class="spaginate-current page-item current">'.$i.'</span>';
            } elseif(($i <= 6 && $currentPage < 10) || $i == $totalPage || $i == 1 || $i < 4 || ($i <= 6 && $totalPage <= 6) || ($i > $currentPage && ($i <= ($currentPage + 2))) || ($i < $currentPage && ($i >= ($currentPage - 2))) || ($i >= ($totalPage - 2) && $i < $totalPage)) {
                $linkResult .= '<a class="spaginate-link page-item" href="'.get_pagenum_link($i).'">'.$i.'</a>';
                if($i <= 6 && $currentPage < 10) {
                    $hiddenBefore = true;
                }
            }
            else {
                if(!$hiddenBefore) {
                    $linkResult .= $pageHidden;
                    $hiddenBefore = true;
                } elseif(!$hiddenAfter && $i > $currentPage) {
                    $linkResult .= $pageHidden;
                    $hiddenAfter = true;
                }
            }
        }
        return $linkResult;
    }

    public static function get_paged() {
        $paged = intval(get_query_var('paged')) ? intval(get_query_var('paged')) : 1;
        return $paged;
    }
}