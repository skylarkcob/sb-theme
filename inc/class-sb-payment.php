<?php
class SB_Payment {
    private $paygates;

    public function __construct($name = null) {
        $this->init();
    }

    private function init() {
        $this->paygates = array();
    }

    public function add_gateway($gate) {
        $this->paygates[] = $gate;
    }

    public function get_gateways() {
        return $this->paygates;
    }
}

