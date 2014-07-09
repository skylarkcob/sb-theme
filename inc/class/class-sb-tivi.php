<?php
class SB_Tivi {
	private $id;
	private $tivi;
	private $size = array(580, 430);
	public function __construct($tivi_id) {
		if($tivi_id > 0) {
			$this->id = $tivi_id;
			$this->tivi = get_post($tivi_id);
		}
	}
	
	public function set_size($width, $height) {
		$this->size = array($width, $height);
	}
	
	public function get_size() {
		return $this->size;
	}
	
	public function get_server_list() {
		$srv = get_post_meta($this->id, 'wpcf-tivi_server', true);
		$srv = explode("[sb_tivi_server]", $srv);
		$kq = array();
		foreach($srv as $value) {
			if(!empty($value)) {
				$value = str_replace("[sb_tivi_server]", "", $value);
				$value = str_replace("[/sb_tivi_server]", "", $value);
				$value = SB_WP::tivi_source_edit(trim($value), $this->size[0], $this->size[1]);
				array_push($kq, $value);
			}
		}
		$kq = array_reverse($kq, true);
		return $kq;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_title() {
		return $this->tivi->post_title;
	}
}
?>