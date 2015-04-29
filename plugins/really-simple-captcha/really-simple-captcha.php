<?php
if(class_exists('ReallySimpleCaptcha')) {
    return;
}

class ReallySimpleCaptcha {

	public function __construct() {
		$this->chars = SB_PHP::uppercase(SB_PHP::get_all_safe_char());
		$this->char_length = 4;
		$this->fonts = array(
			SB_THEME_PATH . '/fonts/Tahoma.ttf',
            SB_THEME_PATH . '/fonts/Calibri.ttf',
            SB_THEME_PATH . '/fonts/Calibri-Bold.ttf'
        );
        $upload = SB_Core::get_upload_folder_detail();
        $upload = $upload['path'];
		$this->tmp_dir = path_join( $upload, 'sb-theme-captcha' );
		$this->img_size = array( 72, 24 );
		$this->bg = array( 255, 255, 255 );
		$this->fg = array( 0, 0, 0 );
		$this->base = array( 6, 18 );
		$this->font_size = 14;
		$this->font_char_width = 15;
		$this->img_type = 'png';
		$this->file_mode = 0444;
		$this->answer_file_mode = 0440;
	}

	public function generate_random_word() {
		$word = '';
		for ( $i = 0; $i < $this->char_length; $i++ ) {
			$pos = mt_rand( 0, strlen( $this->chars ) - 1 );
			$char = $this->chars[$pos];
			$word .= $char;
		}
		return $word;
	}

	public function generate_image( $prefix, $word ) {
		if ( ! $this->make_tmp_dir() )
			return false;

		$this->cleanup();

		$dir = trailingslashit( $this->tmp_dir );
		$filename = null;

		if ( $im = imagecreatetruecolor( $this->img_size[0], $this->img_size[1] ) ) {
			$bg = imagecolorallocate( $im, $this->bg[0], $this->bg[1], $this->bg[2] );
			$fg = imagecolorallocate( $im, $this->fg[0], $this->fg[1], $this->fg[2] );

			imagefill( $im, 0, 0, $bg );

			$x = $this->base[0] + mt_rand( -2, 2 );

			for ( $i = 0; $i < strlen( $word ); $i++ ) {
				$font = $this->fonts[array_rand( $this->fonts )];
				$font = $this->normalize_path( $font );

				imagettftext( $im, $this->font_size, mt_rand( -12, 12 ), $x,
					$this->base[1] + mt_rand( -2, 2 ), $fg, $font, $word[$i] );
				$x += $this->font_char_width;
			}

			switch ( $this->img_type ) {
				case 'jpeg':
					$filename = sanitize_file_name( $prefix . '.jpeg' );
					$file = $this->normalize_path( $dir . $filename );
					imagejpeg( $im, $file );
					break;
				case 'gif':
					$filename = sanitize_file_name( $prefix . '.gif' );
					$file = $this->normalize_path( $dir . $filename );
					imagegif( $im, $file );
					break;
				case 'png':
				default:
					$filename = sanitize_file_name( $prefix . '.png' );
					$file = $this->normalize_path( $dir . $filename );
					imagepng( $im, $file );
			}

			imagedestroy( $im );
			@chmod( $file, $this->file_mode );
		}

		$this->generate_answer_file( $prefix, $word );

		return $filename;
	}

	public function generate_answer_file( $prefix, $word ) {
		$dir = trailingslashit( $this->tmp_dir );
		$answer_file = $dir . sanitize_file_name( $prefix . '.txt' );
		$answer_file = $this->normalize_path( $answer_file );

		if ( $fh = @fopen( $answer_file, 'w' ) ) {
			$word = strtoupper( $word );
			$salt = wp_generate_password( 64 );
			$hash = hash_hmac( 'md5', $word, $salt );

			$code = $salt . '|' . $hash;

			fwrite( $fh, $code );
			fclose( $fh );
		}

		@chmod( $answer_file, $this->answer_file_mode );
	}

	public function check( $prefix, $response ) {
		if ( 0 == strlen( $prefix ) ) {
			return false;
		}

		$response = str_replace( array( " ", "\t" ), '', $response );
		$response = strtoupper( $response );

		$dir = trailingslashit( $this->tmp_dir );
		$filename = sanitize_file_name( $prefix . '.txt' );
		$file = $this->normalize_path( $dir . $filename );

		if ( @is_readable( $file ) && ( $code = file_get_contents( $file ) ) ) {
			$code = explode( '|', $code, 2 );

			$salt = $code[0];
			$hash = $code[1];

			if ( hash_hmac( 'md5', $response, $salt ) == $hash )
				return true;
		}

		return false;
	}

	public function remove( $prefix ) {
		$suffixes = array( '.jpeg', '.gif', '.png', '.php', '.txt' );

		foreach ( $suffixes as $suffix ) {
			$dir = trailingslashit( $this->tmp_dir );
			$filename = sanitize_file_name( $prefix . $suffix );
			$file = $this->normalize_path( $dir . $filename );

			if ( @is_file( $file ) ) {
				unlink( $file );
			}
		}
	}

	public function cleanup( $minutes = 60 ) {
		$dir = trailingslashit( $this->tmp_dir );
		$dir = $this->normalize_path( $dir );

		if ( ! @is_dir( $dir ) || ! @is_readable( $dir ) )
			return false;

		$is_win = ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) );

		if ( ! ( $is_win ? win_is_writable( $dir ) : @is_writable( $dir ) ) )
			return false;

		$count = 0;

		if ( $handle = @opendir( $dir ) ) {
			while ( false !== ( $filename = readdir( $handle ) ) ) {
				if ( ! preg_match( '/^[0-9]+\.(php|txt|png|gif|jpeg)$/', $filename ) )
					continue;

				$file = $this->normalize_path( $dir . $filename );

				$stat = @stat( $file );
				if ( ( $stat['mtime'] + $minutes * 60 ) < time() ) {
					@unlink( $file );
					$count += 1;
				}
			}

			closedir( $handle );
		}

		return $count;
	}

	public function make_tmp_dir() {
		$dir = trailingslashit( $this->tmp_dir );
		$dir = $this->normalize_path( $dir );

		if ( ! wp_mkdir_p( $dir ) )
			return false;

		$htaccess_file = $this->normalize_path( $dir . '.htaccess' );

		if ( file_exists( $htaccess_file ) )
			return true;

		if ( $handle = @fopen( $htaccess_file, 'w' ) ) {
			fwrite( $handle, 'Order deny,allow' . "\n" );
			fwrite( $handle, 'Deny from all' . "\n" );
			fwrite( $handle, '<Files ~ "^[0-9A-Za-z]+\\.(jpeg|gif|png)$">' . "\n" );
			fwrite( $handle, '    Allow from all' . "\n" );
			fwrite( $handle, '</Files>' . "\n" );
			fclose( $handle );
		}

		return true;
	}

	private function normalize_path( $path ) {
		$path = str_replace( '\\', '/', $path );
		$path = preg_replace( '|/+|', '/', $path );
		return $path;
	}
}

?>