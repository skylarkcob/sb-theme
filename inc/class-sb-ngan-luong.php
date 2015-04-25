<?php
class SB_Ngan_Luong {
    // Địa chỉ thanh toán hoá đơn của NgânLượng.vn
    private $nganluong_url = 'https://www.nganluong.vn/checkout.php';

    private $ngaluong_button_url = 'https://www.nganluong.vn/button_payment.php';

    private $min_price = 2000;

    // Mã website của bạn đăng ký trong chức năng tích hợp thanh toán của NgânLượng.vn.
    private $merchant_site_code = ''; // 100001 chỉ là ví dụ, bạn hãy thay bằng mã của bạn

    // Mật khẩu giao tiếp giữa website của bạn và NgânLượng.vn.
    private $secure_pass = ''; //d685739bf1 chỉ là ví dụ, bạn hãy thay bằng mật khẩu của bạn
    // Nếu bạn thay đổi mật khẩu giao tiếp trong quản trị website của chức năng tích hợp thanh toán trên NgânLượng.vn, vui lòng update lại mật khẩu này trên website của bạn

    private $affiliate_code = ''; // Mã đối tác tham gia chương trình liên kết của NgânLượng.vn

    private $return_url;

    private $receiver;

    public function __construct($args = array()) {
        $data = SB_Option::get_ngan_luong_info();
        $merchant_site_code = isset($args['merchant_site_code']) ? $args['merchant_site_code'] : '';
        if(empty($merchant_site_code)) {
            $merchant_site_code = isset($data['merchant_site_code']) ? $data['merchant_site_code'] : '';
        }
        $this->merchant_site_code = $merchant_site_code;
        $secure_pass = isset($args['secure_pass']) ? $args['secure_pass'] : '';
        if(empty($secure_pass)) {
            $secure_pass = isset($data['secure_pass']) ? $data['secure_pass'] : '';
        }
        $this->secure_pass = $secure_pass;
        $affiliate_code = isset($args['affiliate_code']) ? $args['affiliate_code'] : '';
        if(empty($affiliate_code)) {
            $affiliate_code = isset($data['affiliate_code']) ? $data['affiliate_code'] : '';
        }
        $this->affiliate_code = $affiliate_code;
        $this->return_url = isset($args['return_url']) ? $args['return_url'] : '';
        $this->receiver = isset($args['receiver']) ? $args['receiver'] : '';
    }

    /*
     * Hàm tạo mã bảo mật trước khi gửi thông tin thanh toán lên Ngân Lượng.
     * Cú phám này đã được Ngân Lượng định sẵn, không được sửa đổi.
     */
    private function build_secure_code_param($arr_param) {
        $secure_code = isset($arr_param['secure_code']) ? $arr_param['secure_code'] : '';
        if(empty($secure_code)) {
            $merchant_site_code = isset($arr_param['merchant_site_code']) ? $arr_param['merchant_site_code'] : '';
            $return_url = isset($arr_param['return_url']) ? $arr_param['return_url'] : '';
            $receiver = isset($arr_param['receiver']) ? $arr_param['receiver'] : '';
            $transaction_info = isset($arr_param['transaction_info']) ? $arr_param['transaction_info'] : '';
            $order_code = isset($arr_param['order_code']) ? $arr_param['order_code'] : '';
            $price = isset($arr_param['price']) ? $arr_param['price'] : '';
            $str = strval($merchant_site_code);
            $str .= ' ' . strtolower(urlencode($return_url));
            $str .= ' ' . strval($receiver);
            $str .= ' ' . strval($transaction_info);
            $str .= ' ' . strval($order_code);
            $str .= ' ' . strval($price);
            $str .= ' ' . $this->secure_pass;
            $arr_param['secure_code'] = md5($str);
        }
        return $arr_param;
    }

    public function is_price_valid($price) {
        if(!is_numeric($price) || $price < $this->min_price) {
            return false;
        }
        return true;
    }

    public function add_param_to_url($url, $params) {
        if(isset($params['price'])) {
            $price = $params['price'];
            if(!$this->is_price_valid($price)) {
                unset($params['price']);
            }
        }
        $params = $this->build_secure_code_param($params);
        return add_query_arg($params, $url);
    }

    private function build_checkout_url_by_param($arr_param) {
        $redirect_url = $this->get_checkout_url();
        $arr_param = $this->build_secure_code_param($arr_param);
        $redirect_url = add_query_arg($arr_param, $redirect_url);
        return $redirect_url;
    }

    public function get_checkout_url() {
        $url = $this->nganluong_url;
        if(empty($this->merchant_site_code) || empty($this->secure_pass)) {
            $url = $this->ngaluong_button_url;
        }
        return $url;
    }

    public function set_merchant_site_code($merchant_site_code) {
        $this->merchant_site_code = $merchant_site_code;
    }

    public function get_merchant_site_code() {
        return $this->merchant_site_code;
    }

    public function set_secure_pass($secure_pass) {
        $this->secure_pass = $secure_pass;
    }

    public function get_secure_pass() {
        return $this->secure_pass;
    }

    public function set_affiliate_code($affiliate_code) {
        $this->affiliate_code = $affiliate_code;
    }

    public function get_affiliate_code() {
        return $this->affiliate_code;
    }

    public function set_return_url($return_url) {
        $this->return_url = $return_url;
    }

    public function get_return_url() {
        return $this->return_url;
    }

    public function set_receiver($receiver) {
        $this->receiver = $receiver;
    }

    public function get_receiver() {
        return $this->receiver;
    }

    public function build_checkout_custom_url_expand($return_url, $receiver, $transaction_info, $order_code, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $fee_cal = 0, $fee_shipping = 0, $order_description = '', $buyer_info = '', $affiliate_code = '') {
        if(empty($affiliate_code)) {
            $affiliate_code = $this->affiliate_code;
        }
        $arr_param = array(
            'merchant_site_code' => strval($this->merchant_site_code),
            'return_url' => strval(strtolower($return_url)),
            'receiver' => strval($receiver),
            'transaction_info' => strval($transaction_info),
            'order_code' => strval($order_code),
            'price' => strval($price),
            'currency' => strval($currency),
            'quantity' => strval($quantity),
            'tax' => strval($tax),
            'discount' => strval($discount),
            'fee_cal' => strval($fee_cal),
            'fee_shipping' => strval($fee_shipping),
            'order_description' => strval($order_description),
            'buyer_info' => strval($buyer_info),
            'affiliate_code' => strval($affiliate_code)
        );
        $arr_param = $this->build_secure_code_param($arr_param);
        return $this->build_checkout_url_by_param($arr_param);
    }

    public function add_product_name_to_url($url, $product_name) {
        return add_query_arg(array('product_name' => $product_name), $url);
    }

    public function add_currency_to_url($url, $currency) {
        return add_query_arg(array('currency' => $currency), $url);
    }

    public function add_comment_to_url($url, $comments) {
        return add_query_arg(array('comments' => $comments), $url);
    }

    public function add_tax_to_url($url, $tax) {
        return add_query_arg(array('tax' => $tax), $url);
    }

    public function add_discount_to_url($url, $discount) {
        return add_query_arg(array('discount' => $discount), $url);
    }

    public function add_fee_cal_to_url($url, $fee_cal) {
        return add_query_arg(array('fee_cal' => $fee_cal), $url);
    }

    public function add_fee_shipping_to_url($url, $fee_shipping) {
        return add_query_arg(array('fee_shipping' => $fee_shipping), $url);
    }

    public function add_order_description_to_url($url, $order_description) {
        return add_query_arg(array('order_description' => $order_description), $url);
    }

    public function add_buyer_info_to_url($url, $buyer_info) {
        return add_query_arg(array('buyer_info' => $buyer_info), $url);
    }

    public function add_affiliate_code_to_url($url, $affilicate_code) {
        return add_query_arg(array('affiliate_code' => $affilicate_code), $url);
    }

    public function add_quantity_to_url($url, $quantity) {
        return add_query_arg(array('quantity' => $quantity), $url);
    }

    public function build_checkout_url_expand($transaction_info, $order_code, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $fee_cal = 0, $fee_shipping = 0, $order_description = '', $buyer_info = '') {
        return $this->build_checkout_custom_url_expand($this->return_url, $this->receiver, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount, $fee_cal, $fee_shipping, $order_description, $buyer_info);
    }

    public function build_checkout_url($product_name, $quantity, $price, $transaction_info = '') {
        $url = $this->get_checkout_url();
        $params = array(
            'receiver' => $this->get_receiver(),
            'return_url' => $this->get_return_url(),
            'product_name' => $product_name,
            'order_code' => $product_name,
            'quantity' => $quantity,
            'price' => $price,
            'merchant_site_code' => $this->merchant_site_code,
            'transaction_info' => $transaction_info
        );
        $params = $this->build_secure_code_param($params);
        $url = $this->add_param_to_url($url, $params);
        return $url;
    }

    public function build_checkout_custom_url($return_url, $receiver, $transaction_info, $order_code, $price) {
        if(empty($receiver)) {
            return '';
        }
        $arr_param = array(
            'receiver' => strval($receiver)
        );
        if(!empty($this->merchant_site_code)) {
            $arr_param['merchant_site_code'] = strval($this->merchant_site_code);
        }
        if(!empty($transaction_info)) {
            $arr_param['transaction_info'] = strval($transaction_info);
        }
        if(!empty($return_url)) {
            $arr_param['return_url'] = strtolower(urlencode($return_url));
        }
        if(!empty($order_code)) {
            $arr_param['order_code'] =	strval($order_code);
        }
        if($this->is_price_valid($price)) {
            $arr_param['price'] = strval($price);
        }
        $arr_param = $this->build_secure_code_param($arr_param);
        return $this->build_checkout_url_by_param($arr_param);
    }

    /*
     * Hàm tạo mã kiểm tra các giá trị trả về của Ngân Lượng, cú pháp này đã được định sẵn, không được thay đổi.
     */
    public function generate_return_secure_code($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text) {
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval($this->merchant_site_code);
        $str .= ' ' . strval($this->secure_pass);
        return md5($str);
    }

    public function verify_payment_url($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code) {
        $verify_secure_code = $this->generate_return_secure_code($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text);
        if($verify_secure_code === $secure_code) {
            return true;
        }
        return false;
    }

    public function check_return_url() {
        $data = $this->get_return_data();
        $transaction_info = $data['transaction_info'];
        $order_code = $data['order_code'];
        $price = $data['price'];
        $payment_id = $data['payment_id'];
        $payment_type = $data['payment_type'];
        $error_text = $data['error_text'];
        $secure_code = $data['secure_code'];
        $generated_code = $this->generate_return_secure_code($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text);
        if($secure_code === $generated_code) {
            return true;
        }
        return false;
    }

    public function get_return_data() {
        $token = isset($_GET['token_nl']) ? $_GET['token_nl'] : '';
        $transaction_info = isset($_GET['transaction_info']) ? $_GET['transaction_info'] : '';
        $order_code = isset($_GET['order_code']) ? $_GET['order_code'] : '';
        $price = isset($_GET['price']) ? $_GET['price'] : '';
        $payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
        $payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : '';
        $error_text = isset($_GET['error_text']) ? $_GET['error_text'] : '';
        $secure_code = isset($_GET['secure_code']) ? $_GET['secure_code'] : '';
        $result = array(
            'token_nl' => $token,
            'secure_code' => $secure_code,
            'error_text' => $error_text,
            'payment_type' => $payment_type,
            'payment_id' => $payment_id,
            'price' => $price,
            'order_code' => $order_code,
            'product_name' => $order_code,
            'transaction_info' => $transaction_info
        );
        return $result;
    }
}