<?php
class SB_Default_Setting {
	public static function paginate() {
		$options = array(
			'label' => 'Trang',
			'next_text' => '&raquo;',
			'previous_text' => '&laquo;',
			'range' => 3,
			'anchor' => 1,
			'gap' => 3,
			'style' => 'default',
			'border_radius' => 'default'
		);
		return $options;
	}

    public static function sb_paginate() {
        return self::paginate();
    }
}