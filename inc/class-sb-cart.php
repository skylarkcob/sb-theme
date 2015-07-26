<?php
defined('ABSPATH') or die('Please do not pip me!');

if(class_exists('SB_Cart')) {
	return;
}

class SB_Cart {
	public static function add($args = array()) {
		$post_id = isset($args['post_id']) ? $args['post_id'] : 0;
		$quantity = isset($args['quantity']) ? $args['quantity'] : 1;
		if(!is_numeric($post_id) || !is_numeric($quantity) || $post_id < 1 || $quantity < 1) {
			return;
		}
		$price = isset($args['price']) ? $args['price'] : 0;
		if(!self::is_price_valid($price)) {
			return;
		}
		$price = floatval($price);
		$cart = self::get();
		$items = isset($cart['items']) ? $cart['items'] : array();
		$item = isset($items[$post_id]) ? $items[$post_id] : '';
		if(!empty($item)) {
			$item['quantity'] = (isset($item['quantity']) ? $item['quantity'] : 0) + $quantity;
		} else {
			$item = array(
				'id' => $post_id,
				'quantity' => $quantity,
				'price' => $price
			);
		}
		$items[$post_id] = $item;
		$cart['items'] = $items;
		$cart_total = self::get_cart_total($cart);
		$cart_total += $quantity * $price;
		$cart['total'] = $cart_total;
		self::update($cart);
		do_action('sb_theme_add_to_cart', $post_id);
	}

	public static function get() {
		$result = isset($_SESSION['sb_theme_cart']) ? $_SESSION['sb_theme_cart'] : '';
		$result = SB_PHP::json_string_to_array($result);
		return $result;
	}

	public static function update($cart = array()) {
		$cart = json_encode($cart);
		$_SESSION['sb_theme_cart'] = $cart;
	}

	public static function empty_cart() {
		unset($_SESSION['sb_theme_cart']);
	}

	public static function update_items($products) {
		if(SB_PHP::is_array_has_value($products)) {
			$cart = self::get();
			$items = self::get_cart_items($cart);
			if(SB_PHP::is_array_has_value($items)) {
				$total = self::get_cart_total($cart);
				foreach($products as $akey => $data) {
					$id = isset($data['id']) ? $data['id'] : 0;
					$quantity = isset($data['quantity']) ? $data['quantity'] : 1;
					$item = isset($items[$id]) ? $items[$id] : array();
					if(SB_PHP::is_array_has_value($item)) {
						$price = isset($item['price']) ? $item['price'] : 0;
						$old_qty = isset($item['quantity']) ? $item['quantity'] : 1;
						if($quantity != $old_qty && $quantity > 0) {
							$total -= ($old_qty * $price);
							$total += ($quantity * $price);
							$item['quantity'] = $quantity;
							$items[$id] = $item;
						}
					}
				}
				$cart['items'] = $items;
				$cart['total'] = $total;
				self::update($cart);
			}
		}
	}

	public static function remove_item($id) {
		self::delete_item($id);
	}

	public static function delete_item($id) {
		$id = absint($id);
		if($id > 0) {
			$cart = self::get();
			$items = self::get_cart_items($cart);
			$item = isset($items[$id]) ? $items[$id] : array();
			$price = isset($item['price']) ? $item['price'] : 0;
			$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
			$price *= $quantity;
			$total = isset($cart['total']) ? $cart['total'] : 0;
			if($total > 0) {
				$total -= $price;
				$cart['total'] = $total;
			}
	        unset($items[$id]);
	        $cart['items'] = $items;
	        self::update($cart);
	    }
	}

	public static function get_item_price($item) {
		$result = 0;
		if(isset($item['price'])) {
			$result = $item['price'];
		}
		return floatval($result);
	}

	public static function get_item_quantity($item) {
		$quantity = isset($item['quantity']) ? $item['quantity'] : 1;
		return absint($quantity);
	}

	public static function get_cart_items($cart = array()) {
		if(!SB_PHP::is_array_has_value($cart)) {
			$cart = self::get();
		}
		$items = isset($cart['items']) ? $cart['items'] : array();
		return $items;
	}

	public static function has_item($cart = array()) {
		$items = self::get_cart_items($cart);
		return SB_PHP::is_array_has_value(($items));
	}

	public static function get_cart_total($cart = array()) {
		if(!SB_PHP::is_array_has_value($cart)) {
			$cart = self::get();
		}
		$cart_total = isset($cart['total']) ? $cart['total'] : 0;
		$cart_total = floatval($cart_total);
		return $cart_total;
	}

	public static function is_price_valid($price) {
		if(!is_numeric($price) || 1 > $price) {
			return false;
		}
		return true;
	}

	public static function the_add_to_cart_button($args = array()) {
		$post_id = isset($args['post_id']) ? $args['post_id'] : 0;
		$quantity = isset($args['quantity']) ? $args['quantity'] : 1;
		$text = isset($args['text']) ? $args['text'] : '';
		if(empty($text)) {
			$text = SB_Text::get_add_to_cart();
		}
		$price = isset($args['price']) ? $args['price'] : 0;
		$class = isset($args['class']) ? $args['class'] : '';
		$class = SB_PHP::add_string_with_space_before($class, 'sbt-btn-atc');
		if(!self::is_price_valid($price)) {
			$class = SB_PHP::add_string_with_space_before($class, 'disabled');
		}
		?>
		<p class="<?php echo $class; ?>" data-id="<?php echo $post_id; ?>" data-quantity="<?php echo $quantity; ?>" data-price="<?php echo $price; ?>"><?php echo $text; ?></p>
		<?php
	}

	public static function the_cart_readonly($args = array()) {
		$return_url = isset($args['return_url']) ? $args['return_url'] : home_url('/');
		$checkout_url = isset($args['checkout_url']) ? $args['checkout_url'] : '';
		$cart_title = apply_filters('sb_theme_cart_title_text', __('Giỏ hàng', 'sb-theme'));
		$cart = isset($args['cart']) ? $args['cart'] : self::get();
		$cart_total = self::get_cart_total($cart);
		?>
		<div class="sb-theme-cart">
			<div class="cart-content">
				<?php if(self::has_item($cart)) : ?>
					<table class="cart-products">
						<tbody>
						<tr>
							<td width="45%" class="column-title"><?php _e('Tên sản phẩm', 'sb-theme'); ?></td>
							<td width="10%" class="column-title"><?php _e('Số lượng', 'sb-theme'); ?></td>
							<td width="20%" class="column-title"><?php _e('Đơn giá', 'sb-theme'); ?></td>
							<td width="25%" class="column-title"><?php _e('Thành tiền', 'sb-theme'); ?></td>
						</tr>
						<?php foreach($cart['items'] as $id => $data) :
							$post = get_post($id);
							if(!SB_Core::is_valid_object($post)) {
								continue;
							}
							$quantity = self::get_item_quantity($data);
							$price = self::get_item_price($data);
							?>
							<tr>
								<td class="product-title">
									<a href="<?php echo get_permalink($post); ?>"><?php echo $post->post_title; ?></a>
								</td>
								<td class="product-quantity" align="center">
									<?php echo $quantity; ?>
								</td>
								<td style="text-align:right;" class="product-price" data-price="<?php echo $price; ?>">
									<?php echo SB_PHP::currency_format_vietnamese($price); ?>
								</td>
								<td style="text-align:right;" class="product-total" data-price="<?php echo ($quantity * $price); ?>">
									<?php echo SB_PHP::currency_format_vietnamese($quantity * $price); ?>
								</td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td style="text-align:right" colspan="2" class="product-quantity-update"></td>
							<td style="text-align:right; padding-right:5px" class="cart-total-text">
								<strong><?php _e('Tổng cộng:', 'sb-theme'); ?></strong>
							</td>
							<td style="text-align:right; padding-right:5px" class="cart-total" data-price="<?php echo $cart_total; ?>">
								<?php echo SB_PHP::currency_format_vietnamese($cart_total); ?>
							</td>
						</tr>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function the_cart($args = array()) {
		$return_url = isset($args['return_url']) ? $args['return_url'] : home_url('/');
		$checkout_url = isset($args['checkout_url']) ? $args['checkout_url'] : '';
		$cart_title = apply_filters('sb_theme_cart_title_text', __('Giỏ hàng', 'sb-theme'));
		$cart = isset($args['cart']) ? $args['cart'] : self::get();
		$cart_total = self::get_cart_total($cart);
		?>
		<div class="sb-theme-cart">
			<div class="cart-title">
				<i class="fa fa-shopping-cart icon-left"></i>
				<span><?php echo $cart_title; ?></span>
			</div>
			<div class="cart-content">
				<?php if(self::has_item($cart)) : ?>
					<table class="cart-table">
						<tbody>
						<tr>
							<td colspan="2">
								<table class="cart-products">
									<tbody>
									<tr>
										<td width="44%" class="column-title"><?php _e('Tên sản phẩm', 'sb-theme'); ?></td>
										<td width="11%" class="column-title"><?php _e('Số lượng', 'sb-theme'); ?></td>
										<td width="17%" class="column-title"><?php _e('Đơn giá', 'sb-theme'); ?></td>
										<td width="20%" class="column-title"><?php _e('Thành tiền', 'sb-theme'); ?></td>
										<td width="8%" class="column-title"><?php _e('Xóa', 'sb-theme'); ?></td>
									</tr>
									<?php foreach($cart['items'] as $id => $data) :
										$post = get_post($id);
										if(!SB_Core::is_valid_object($post)) {
											continue;
										}
										$quantity = self::get_item_quantity($data);
										$price = self::get_item_price($data);
										?>
										<tr>
											<td class="product-title">
												<a href="<?php echo get_permalink($post); ?>"><?php echo $post->post_title; ?></a>
											</td>
											<td class="product-quantity">
												<input data-id="<?php echo $post->ID; ?>" type="number" min="1" name="product_<?php echo $post->ID; ?>_quantity" value="<?php echo $quantity; ?>" style="width: 55px" autocomplete="off">
											</td>
											<td style="text-align:right;" class="product-price" data-price="<?php echo $price; ?>">
												<?php echo SB_PHP::currency_format_vietnamese($price); ?>
											</td>
											<td style="text-align:right;" class="product-total" data-price="<?php echo ($quantity * $price); ?>">
												<?php echo SB_PHP::currency_format_vietnamese($quantity * $price); ?>
											</td>
											<td class="product-delete">
												<span class="sbt-remove-item" data-id="<?php echo $post->ID; ?>"><?php _e('Xóa', 'sb-theme'); ?></span>
											</td>
										</tr>
									<?php endforeach; ?>
									<tr>
										<td style="text-align:right" colspan="2" class="product-quantity-update">
											<input class="sbt-btn-uc" type="button" style="width: 130px" value="<?php _e('Cập nhập số lượng', 'sb-theme'); ?>" name="product_quantity_update">
										</td>
										<td style="text-align:right; padding-right:5px" class="cart-total-text">
											<strong><?php _e('Tổng cộng:', 'sb-theme'); ?></strong>
										</td>
										<td style="text-align:right; padding-right:5px" class="cart-total" data-price="<?php echo $cart_total; ?>">
											<?php echo SB_PHP::currency_format_vietnamese($cart_total); ?>
										</td>
										<td></td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td width="73%" align="right" class="return-shop">
								<a class="sbt-btn-rs" href="<?php echo $return_url; ?>"><i class="fa fa-backward icon-left"></i><?php _e('Tiếp tục chọn hàng', 'sb-theme'); ?></a>
							</td>
							<td width="27%" align="center" class="checkout">
								<a class="sbt-btn-checkout" href="<?php echo $checkout_url; ?>"><i class="fa fa-dollar icon-left"></i><?php _e('Đặt mua', 'sb-theme'); ?></a>
							</td>
						</tr>
						</tbody>
					</table>
				<?php else : ?>
					<div class="no-content">
						<p><?php _e('Hiện không có sản phẩm nào trong giỏ hàng.', 'sb-theme'); ?></p>
						<p><a class="return-shopping" href="<?php echo $return_url; ?>"><i class="fa fa-backward icon-left"></i><?php _e('Quay trở lại mua hàng', 'sb-theme'); ?></a></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}