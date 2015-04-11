<?php
class SB_List_Plugin {
    private $plugins;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->plugins = array();
    }

    public function add($plugin) {
        array_push($this->plugins, $plugin);
    }

    public function get() {
        return $this->plugins;
    }
}