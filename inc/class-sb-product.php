<?php
defined('ABSPATH') or die('Please do not pip me!');

if(class_exists('SB_Product')) {
    return;
}
class SB_Product {
    public static function get_out_of_stock_text() {
        return apply_filters('sb_theme_out_of_stock_text', ('vi' == SB_Core::get_language()) ? 'Tạm hết hàng' : __('Out of stock', 'sb-theme'));
    }

    public static function get_no_price_text() {
        $text = 'Vui lòng gọi';
        if('vi' != SB_Core::get_language()) {
            $text = __('Please call', 'sb-theme');
        }
        $text = apply_filters('sb_theme_no_price_text', $text);
        return $text;
    }

    public static function get_category_thumbnail_url($cat) {
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        return wp_get_attachment_url( $thumbnail_id );
    }

    public static function get_categories($args = array()) {
        return get_terms('product_cat', $args);
    }

    private $product;

    public function __construct($id = null) {
        if($id && class_exists('WC_Product')) {
            $this->product = new WC_Product($id);
        }
        return $this->product;
    }

    public function get_sku() {
        return $this->product->get_sku();
    }

    public static function get_featured($args = array()) {
        $args['meta_key'] = '_featured';
        $args['meta_value'] = 'yes';
        return self::get($args);
    }

    public function get_add_to_cart_button($args = array()) {
        $defaults = array('id' => $this->product->id, 'sku' => $this->get_sku());
        $args = wp_parse_args($args, $defaults);
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id) || !is_numeric($id)) {
            $id = isset($args['post_id']) ? $args['post_id'] : '';
            if(empty($id)) {
                $id = get_the_ID();
            }
        }
        if(!empty($id) && is_numeric($id)) {
            $price = self::get_price($id);
            $style = '';
            if($price == 0) {
                $style = ' pointer-events: none; cursor: default;';
                $style = trim($style);
            }
            $sku = isset($args['sku']) ? $args['sku'] : '';
            $class = 'custom-add-to-cart';
            if(0 == $price) {
                $class .= ' please-call';
            }
            return '<div class="' . $class . '" data-post-id="' . $id . '">' . do_shortcode('[add_to_cart id="' . $id . '" sku="' . $sku . '" style="' . $style . '"]') . '</div>';
        }
        return '';
    }

    public static function get_sku_number($post_id) {
        $product = self::get_product_object($post_id);
        return $product->get_sku();
    }

    public static function choose_price($regular_price, $sale_price) {
        $price = 0;
        if(is_numeric($regular_price)) {
            $price = $regular_price;
        }
        if(is_numeric($sale_price) && (0 == $price || ($price > 0 && $sale_price < $price))) {
            $price = $sale_price;
        }
        return $price;
    }

    public static function get_add_to_cart($args = array()) {
        $post_id = isset($args['post_id']) ? absint($args['post_id']) : get_the_ID();
        $sku = isset($args['sku']) ? $args['sku'] : self::get_sku_number($post_id);
        $style = isset($args['style']) ? $args['style'] : '';
        $prices = self::get_prices($post_id);
        $price = $prices['price'];
        $container_class = isset($args['container_class']) ? $args['container_class'] : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'custom-add-to-cart sb-theme-add-to-cart sb-theme-atc-button sb-theme-catcb');
        if(0 == $price) {
            $container_class = SB_PHP::add_string_with_space_before($container_class, 'please-call');
        }
        $field_class = isset($args['field_class']) ? $args['field_class'] : '';
        $quantity = isset($args['quantity']) ? absint($args['quantity']) : 1;
        $show_price = isset($args['show_price']) ? (bool)$args['show_price'] : true;
        $show_price = ($show_price) ? 'true' : 'false';
        $shortcode = do_shortcode('[add_to_cart id="' . $post_id . '" sku="' . $sku . '" style="' . $style . '" class="' . $field_class . '" show_price="' . $show_price . '" quantity="' . $quantity . '"]');
        return '<div class="'. $container_class .'">' . $shortcode . '</div>';
    }

    public static function the_add_to_cart($args = array()) {
        echo self::get_add_to_cart($args);
    }

    public function the_add_to_cart_button($args = array()) {
        echo $this->get_add_to_cart_button($args);
    }

    public static function get_checkout_uri() {
        global $woocommerce;
        return $woocommerce->cart->get_checkout_url();
    }

    public static function get_checkout_url() {
        return self::get_checkout_uri();
    }

    public static function the_checkout_uri() {
        echo SB_Product::get_checkout_uri();
    }

    public static function the_checkout_url() {
        self::the_checkout_uri();
    }

    public static function get_wishlist_url() {
        $url = '';
        if(function_exists('YITH_WCWL')) {
            $url = YITH_WCWL()->get_wishlist_url('');
        }
        return $url;
    }

    public static function the_wishlist_url() {
        echo self::get_wishlist_url();
    }

    public static function count_wishlist_product() {
        $result = 0;
        if(function_exists('YITH_WCWL')) {
            $result = YITH_WCWL()->count_products();
        }
        return $result;
    }

    public static function get_cart_uri() {
        global $woocommerce;
        return $woocommerce->cart->get_cart_url();
    }

    public static function get_cart_url() {
        return self::get_cart_uri();
    }

    public static function please_call_html() {
        return '<span class="price"><span class="no-price call amount">' . self::get_no_price_text() . '</span></span>';
    }

    public static function get_price_html($product = null) {
        if(null == $product) {
            $product = $GLOBALS['product'];
        }
        $html = $product->get_price_html();
        if(empty($html)) {
            $html = self::please_call_html();
        }
        return $html;
    }

    public static function get_cart($args = array()) {
        $lang = SB_Core::get_language();
        $title = isset($args['title']) ? $args['title'] : (('vi' == $lang) ? 'Thông tin giỏ hàng' : __('View your shopping cart', 'sb-theme'));
        $show_item = isset($args['show_item']) ? (bool)$args['show_item'] : true;
        $show_price = isset($args['show_price']) ? (bool)$args['show_price'] : true;
        $show_icon = isset($args['show_icon']) ? (bool)$args['show_icon'] : true;
        $show_preview = isset($args['show_preview']) ? (bool)$args['show_preview'] : true;
        $cart = '<a class="cart-content" href="' . self::get_cart_uri() . '" title="' . $title . '">';
        if($show_icon) {
            $cart .= '<i class="fa fa-shopping-cart icon-left"></i>';
        }
        if($show_item) {
            $item_text = self::count_cart() . 'sản phẩm';
            if('vi' != $lang) {
                $item_text = sprintf(_n('%d item', '%d items', self::count_cart(), 'sb-theme'), self::count_cart());
            }
            $cart .= '<span class="product-number">' . $item_text . '</span>';
            if($show_price) {
                $cart .= '<span class="sep"> - </span>';
            }
        }
        if($show_price) {
            $cart .= self::cart_total();
        }
        if($show_preview) {
            $cart .= '<i class="fa fa-angle-down icon-right"></i>';
        }
        $cart .= '</a>';
        return $cart;
    }

    public static function the_cart() {
        echo '<div class="cart-group">';
        do_action('sbwp_cart_before');
        do_action('sb_theme_cart_before');
        echo self::get_cart();
        do_action('sb_theme_cart_after');
        do_action('sbwp_cart_after');
        echo '</div>';
    }

    public static function get_currency_symbol() {
        return apply_filters('sb_theme_product_currency_symbol', get_woocommerce_currency_symbol());
    }

    public static function get_price_format() {
        return apply_filters('sb_theme_product_price_format', get_woocommerce_price_format());
    }

    public static function get_currency_symbol_html() {
        return apply_filters('sb_theme_product_currency_symbol_html', '<span class="currency-symbol">' . self::get_currency_symbol() . '</span>');
    }

    public static function get_product_object($post_id) {
        return new WC_Product($post_id);
    }

    public static function get_prices($post_id) {
        $product = self::get_product_object($post_id);
        $result = array(
            'price' => $product->get_price(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price()
        );
        return $result;
    }

    public static function get_price_by_key($post_id, $price_key) {
        $prices = self::get_prices($post_id);
        return floatval(isset($prices[$price_key]) ? $prices[$price_key] : '');
    }

    public static function get_price($post_id = 0) {
        $key = 'price';
        if(!is_numeric($post_id)) {
            $key = $post_id;
            $post_id = get_the_ID();
        }
        return self::get_price_by_key($post_id, 'price');
    }

    public static function get_regular_price($post_id) {
        return self::get_price_by_key($post_id, 'regular_price');
    }

    public static function get_sale_price($post_id) {
        return self::get_price_by_key($post_id, 'sale_price');
    }

    public static function get_formatted_price($post_id, $show_sale_price = true) {
        $result = self::please_call_html();
        $prices = self::get_prices($post_id);
        $product_price = $prices['regular_price'];
        $negative = (bool)($product_price < 0);
        if(is_numeric($product_price) && $product_price > 0) {
            $price_format = self::get_price_format();
            $currency_symbol = self::get_currency_symbol_html();
            $price_decimal = self::get_price_decimals();
            $price_decimal_separator = self::get_price_decimal_separator();
            $price_thousand_separator = self::get_price_thousand_separator();
            $formatted_price = SB_PHP::format_number_vietnamese($product_price, $price_decimal, $price_decimal_separator, $price_thousand_separator);
            $formatted_price = ($negative ? '-' : '') . sprintf($price_format, $currency_symbol, $formatted_price);
            $formatted_price = '<span class="amount">' . $formatted_price . '</span>';
            $result = $formatted_price;
            if($show_sale_price) {
                $sale_price = $prices['sale_price'];
                if(is_numeric($sale_price) && $sale_price < $product_price) {
                    $paragraph = new SB_HTML('p');
                    $paragraph->set_attribute('class', 'price sb-product-price');
                    $formatted_price = SB_PHP::format_number_vietnamese($sale_price, $price_decimal, $price_decimal_separator, $price_thousand_separator);
                    $formatted_price = sprintf($price_format, $currency_symbol, $formatted_price);
                    $formatted_price = '<span class="amount">' . $formatted_price . '</span>';
                    $del = '<del>' . $result . '</del>';
                    $ins = '<ins>' . $formatted_price . '</ins>';
                    $paragraph->set_text($ins . $del);
                    $result = $paragraph->build();
                }
            }
        }
        return apply_filters('sb_theme_product_formatted_price_html', $result, $post_id);
    }

    public static function the_formatted_price($post_id, $show_sale_price = true) {
        echo self::get_formatted_price($post_id, $show_sale_price);
    }

    public function the_price($show_sale_price = true) {
        $product = $this->product;
        echo self::get_formatted_price($product->id, $show_sale_price);
    }

    public static function get_price_decimals() {
        return apply_filters('sb_theme_product_price_decimal', wc_get_price_decimals());
    }

    public static function get_price_thousand_separator() {
        return apply_filters('sb_theme_product_price_thousand_separator', wc_get_price_thousand_separator());
    }

    public static function get_price_decimal_separator() {
        return apply_filters('sb_theme_product_price_decimal_separator', wc_get_price_decimal_separator());
    }

    public function get_attribute($name) {
        return $this->product->get_attribute($name);
    }

    public static function count_cart() {
        global $woocommerce;
        return $woocommerce->cart->cart_contents_count;
    }

    public static function cart_total() {
        global $woocommerce;
        return $woocommerce->cart->get_cart_total();
    }

    public static function add_cart_item_to_menu($args = array()) {
        $li = new SB_HTML('li');
        $li->set_class('cart-item menu-item');
        $li->set_attribute('id', 'sbt_cart_item');
        $li->set_text(self::get_cart());
        $args['item'] = $li->build();
        return SB_Core::add_item_to_menu($args);
    }

    public static function the_price_html($price, $sale_price = 0) {
        if(0 == $price) {
            echo self::please_call_html();
        } else {
            $result = '';
            if($sale_price > 0 && $sale_price < $price) {
                $ins = '<ins><span class="amount">' . number_format($sale_price) . ' đ</span></ins>';
                $del = '<del><span class="amount">' . number_format($price) . ' đ</span></del>';
                $result = $ins . ' ' . $del;
            }
            else {
                $result = '<ins><span class="amount">' . number_format($price) . ' đ</span></ins>';
            }
            echo '<span class="price">' . trim($result) . '</span>';
        }
    }

    public function price() {
        $post_id = get_the_ID();
        $prices = self::get_prices($post_id);
        self::the_price_html($prices['regular_price'], $prices['sale_price']);
    }

    public function is_sale() {
        $prices = self::get_prices(get_the_ID());
        $price = $prices['price'];
        if($price > 0) {
            $regular = $prices['regular_price'];
            if($price < $regular) {
                return true;
            }
        }
        return false;
    }

    public function sale_percentage() {
        self::the_sale_percentage(get_the_ID());
    }

    public static function get_sale_percentage($post_id) {
        $prices = self::get_prices($post_id);
        $sale_price = $prices['sale_price'];
        if($sale_price < 1) {
            return 0;
        }
        return SB_PHP::percentage($sale_price, $prices['regular_price'], 2);
    }

    public static function is_sale_off($post_id) {
        $percent = self::get_sale_percentage($post_id);
        if($percent > 0) {
            return true;
        }
        return false;
    }

    public static function get_sale_percentage_html($post_id) {
        $percent = self::get_sale_percentage($post_id);
        $result = '';
        if($percent > 0) {
            $result = '<span class="sale-percentage">' . '-' . self::get_sale_percentage($post_id) . '%' . '</span>';
        }
        return $result;
    }

    public static function the_sale_percentage($post_id) {
        echo self::get_sale_percentage_html($post_id);
    }

    public static function get_warranty($post_id) {
        $product = self::get_product_object($post_id);
        $bao_hanh = $product->get_attribute('bao-hanh');
        if(empty($bao_hanh)) {
            $bao_hanh = SB_Post::get_meta($post_id, 'wpcf-title-description');
            $bao_hanh = SB_PHP::lowercase($bao_hanh);
            if(SB_PHP::is_string_contain($bao_hanh, 'bảo hành')) {
                $bao_hanh_pos = strrpos($bao_hanh, 'bảo hành');
                $bao_hanh = mb_substr($bao_hanh, $bao_hanh_pos);
                $bao_hanh = mb_ereg_replace('bảo hành', '', $bao_hanh);
                $bao_hanh = mb_ereg_replace('o hành', '', $bao_hanh);
                $bao_hanh = mb_ereg_replace('hành:', '', $bao_hanh);
                $bao_hanh = mb_ereg_replace('hành', '', $bao_hanh);
                $bao_hanh = mb_ereg_replace('chính hãng', '', $bao_hanh);
            } else {
                $bao_hanh = '';
            }
        }
        $bao_hanh = trim($bao_hanh);
        return $bao_hanh;
    }

    public static function count_product() {
        $products = SB_Query::get(array("post_type" => "product", "posts_per_page" => -1));
        return $products->post_count;
    }

    public static function get($args = array()) {
        return self::get_product($args);
    }

    public static function get_most_views($args = array()) {
        $args['type'] = 'most_view';
        return self::get_product($args);
    }

    public static function get_best_sell($args = array()) {
        $args['type'] = 'best_seller';
        return self::get($args);
    }

    public static function get_sale_off($args = array()) {
        $args['type'] = 'sale';
        return self::get($args);
    }

    public static function get_product($args = array()) {
        $defaults = array(
            'offset' => 0,
            'type' => 'recent',
            'params' => array(),
            'post_type' => 'product'
        );
        $args = wp_parse_args($args, $defaults);
        $type = isset($args['type']) ? $args['type'] : 'recent';
        $tmp_args = $args;
        switch($type) {
            case 'most_view':
                $args = array(
                    'meta_key'	=> 'views',
                    'order_by'	=> 'meta_value'
                );
                break;
            case 'best_seller':
                $args = array(
                    'meta_key'			=> 'total_sales',
                    'orderby'			=> 'meta_value'
                );
                break;
            case 'sale':
                $args = array(
                    'meta_query'		=> array(
                        'relation' => 'OR',
                        array(
                            'key'           => '_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        ),
                        array(
                            'key'           => '_min_variation_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        )
                    )
                );
                break;
            case 'sale_slide':
                $args = array(
                    'meta_query'		=> array(
                        'relation' => 'AND',
                        array(
                            'key'           => '_sale_price',
                            'value'         => 0,
                            'compare'       => '>',
                            'type'          => 'numeric'
                        ),
                        array(
                            'key'           => 'wpcf-product-slide',
                            'value'         => 1,
                            'compare'       => '=',
                            'type'          => 'numeric'
                        )
                    )
                );
                break;
            case 'most_comment':
                $args = array(
                    'orderby'			=> 'comment_count'
                );
                break;
            default:
                $args = array();
        }
        $args = wp_parse_args($args, $tmp_args);
        $params = isset($args['params']) ? $args['params'] : array();
        if(SB_PHP::is_array_has_value($params)) {
            $args = wp_parse_args($params, $args);
        }
        return SB_Query::get($args);
    }

    public static function get_account_uri() {
        $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
        $myaccount_page_url = '';
        if($myaccount_page_id && is_numeric($myaccount_page_id) && $myaccount_page_id > 0) {
            $myaccount_page_url = get_permalink($myaccount_page_id);
        }
        return $myaccount_page_url;
    }

    public static function get_account_url() {
        return self::get_account_uri();
    }

    public static function the_account_url() {
        echo self::get_account_uri();
    }

    public static function get_store_uri() {
        return get_permalink( woocommerce_get_page_id( 'shop' ) );
    }

    public static function get_shop_url() {
        return self::get_store_uri();
    }

    public static function get_payment_uri() {
        $payment_page = get_permalink( woocommerce_get_page_id( 'pay' ) );
        if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) $payment_page = str_replace( 'http:', 'https:', $payment_page );
        return $payment_page;
    }

    public static function get_category_thumbnail_uri($cat) {
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        return wp_get_attachment_url( $thumbnail_id );
    }

    public static function the_thumbnail() {
        SB_Post::the_thumbnail(array('bfi_thumb' => false));
    }

    public function is_in_stock() {
        return $this->product->is_in_stock();
    }

    public static function get_category($args = array()) {
        return get_terms('product_cat', $args);
    }

    public static function get_category_parent($args = array()) {
        $defaults = array(
            'parent'	=> 0
        );
        $args = wp_parse_args($args , $defaults);
        return self::get_category($args);
    }

    public static function get_by_category($cat, $args = array()) {
        if(is_numeric($cat)) {
            $term_id = $cat;
        } else {
            $term_id = $cat->term_id;
        }
        $defaults = array(
            'post_type' => 'product',
            'posts_per_page' => 8,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $term_id
                )
            )
        );
        $args = wp_parse_args($args, $defaults);
        return SB_Query::get($args);
    }

    public function get_bulk_discount() {
        $result = array();
        $quantity = 5;
        $discount = get_post_meta($this->product->id, 'wpcf-product-quantity-5-price', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = 10;
        $discount = get_post_meta($this->product->id, 'wpcf-product-quantity-10-price', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = 15;
        $discount = get_post_meta($this->product->id, 'wpcf-product-quantity-15-price', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = 20;
        $discount = get_post_meta($this->product->id, 'wpcf-product-quantity-20-price', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = 25;
        $discount = get_post_meta($this->product->id, 'wpcf-product-quantity-25-price', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        return $result;
    }
}

class SB_Product_Discount {
    public $quantity;
    public $discount;
    public function __construct($quantity, $discount) {
        $this->quantity = $quantity;
        $this->discount = $discount;
    }
}