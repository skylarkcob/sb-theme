<?php
defined('ABSPATH') or die('Please do not pip me!');

if(isset($_POST['submit'])) {
    $files = isset($_FILES['file_names']) ? $_FILES['file_names'] : array();
    $result = SB_Core::upload(array('files' => $files, 'is_image' => true));
}