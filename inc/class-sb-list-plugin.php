<?php
defined('ABSPATH') or die('Please do not pip me!');

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