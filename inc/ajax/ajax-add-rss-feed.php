<?php
defined('ABSPATH') or die('Please do not pip me!');

$count = isset($_POST['data_count']) ? $_POST['data_count'] : 1;
$count++;
$name = isset($_POST['data_name']) ? $_POST['data_name'] : '';
$id = isset($_POST['data_id']) ? $_POST['data_id'] : $count;
SB_Admin_Custom::set_current_rss_feed_item(array('name' => $name, 'count' => $count, 'id' => $id));
sb_core_get_loop('loop-admin-rss-feed');
