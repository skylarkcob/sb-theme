<?php
if(!defined("ABSPATH")) exit;
if(!class_exists("WC_Product")) return;

class SB_Product extends WC_Product {
    private $product;

    public function __construct($id = null) {
        if(null == $id) {
            $id = get_the_ID();
        }
        $this->product = new WC_Product($id);
    }

    public function get_sku() {
        return $this->product->get_sku();
    }

    public function get_add_to_cart_button() {
        $id = $this->product->id;
        $sku = $this->get_sku();
        if(empty($id) || !is_numeric($id)) {
            $id = get_the_ID();
        }
        if(!empty($id) && is_numeric($id)) {
            $price = $this->get_price(array('id' => $id));
            if($price == 0) {
                $style = ' pointer-events: none; cursor: default;';
                $style = trim($style);
            }
            if(0 == $this->get_price()) {
                $class = ' please-call';
            }
            return '<div class="sb-add-cart custom-add-to-cart'.$class.'">'.do_shortcode('[add_to_cart id="'.$id.'" sku="'.$sku.'" style="'.$style.'"]').'</div>';
        }
        return '';
    }

    public function add_cart_button() {
        $this->the_add_to_cart_button();
    }

    public function the_add_to_cart_button() {
        echo $this->get_add_to_cart_button();
    }

    public function get_stock_status() {
        if($this->product->is_in_stock()) {
            return __(SB_WP::phrase("in_stock"), SB_DOMAIN);
        }
        return __(SB_WP::phrase("out_of_stock"), SB_DOMAIN);
    }

    public function get_tag($args = array()) {
        $separate_by = ', ';
        extract($args, EXTR_OVERWRITE);
        return $this->product->get_tags($separate_by, '', '');
    }

    public function get_category($args = array()) {
        $separate_by = ', ';
        extract($args, EXTR_OVERWRITE);
        return $this->product->get_categories($separate_by, '', '');
    }

    public function get_weight() {
        $lang = SB_WP::get_current_language();
        $weight_id = "trong-luong";
        if("en" == $lang) {
            $weight_id = "weight";
        }
        $weight = $this->product->get_attribute($weight_id);
        if(empty($weight)) {
            $weight = '0.00g';
        }
        return $weight;
    }

    public function get_brand() {
        $lang = SB_WP::get_current_language();
        $attr = "thuong-hieu";
        if("en" == $lang) {
            $attr = "brand";
        }
        return $this->product->get_attribute($attr);
    }

    public function get_price($type = "price") {
        $product = $this->product;
        $price = 0;
        switch($type) {
            case 'sale':
                $price = $product->get_sale_price();
                break;
            case 'regular':
                $price = $product->get_regular_price();
                break;
            default:
                $price = $product->price;
        }
        if(!is_numeric($price)) {
            $price = 0;
        }
        return $price;
    }

    public function price() {
        $price = $this->get_price();
        if(0 == $price) {
            echo '<span class="price"><span class="no-price call">Vui lòng gọi</span></span>';
        }
        else {
            $sale = $this->get_price('sale');
            if($sale > 0) {
                $price = $this->get_price('regular');
                $sale = '<ins><span class="amount">'.number_format($sale).' đ</span></ins>';
                $price = '<del><span class="amount">'.number_format($price).' đ</span></del>';
                $price = $price.' '.$sale;
            }
            else {
                $price = $this->get_price();
                $price = '<ins><span class="amount">'.number_format($price).' đ</span></ins>';
            }
            echo '<span class="price">'.trim($price).'</span>';
        }
    }

    public function is_sale() {
        $price = $this->get_price('sale');
        if($price > 0) {
            $regular = $this->get_price('regular');
            if($price < $regular) {
                return true;
            }
        }
        return false;
    }

    public function sale_percentage($precision = 2) {
        echo '-'.$this->get_sale_percentage($precision).'%';
    }

    public function get_sale_percentage($precision = 2) {
        return SB_PHP::percentage($this->get_price('sale'), $this->get_price('regular'), $precision);
    }

    public function get_bulk_discount() {
        $result = array();
        $quantity = get_post_meta($this->product->id, '_bulkdiscount_quantity_1', true);
        $discount = get_post_meta($this->product->id, '_bulkdiscount_discount_1', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = get_post_meta($this->product->id, '_bulkdiscount_quantity_2', true);
        $discount = get_post_meta($this->product->id, '_bulkdiscount_discount_2', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = get_post_meta($this->product->id, '_bulkdiscount_quantity_3', true);
        $discount = get_post_meta($this->product->id, '_bulkdiscount_discount_3', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = get_post_meta($this->product->id, '_bulkdiscount_quantity_4', true);
        $discount = get_post_meta($this->product->id, '_bulkdiscount_discount_4', true);
        if(!empty($quantity) && !empty($discount)) {
            $bulk_discount = new SB_Product_Discount($quantity, $discount);
            array_push($result, $bulk_discount);
        }
        $quantity = get_post_meta($this->product->id, '_bulkdiscount_quantity_5', true);
        $discount = get_post_meta($this->product->id, '_bulkdiscount_discount_5', true);
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