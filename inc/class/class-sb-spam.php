<?php
if(!defined("ABSPATH")) exit;
class SB_Spam {
    var $comments;


    private function init() {
        $this->init_comment_spam_data();
    }

    private function init_comment_spam_data() {
        $this->comments = array();

    }

    public function add_comment($comment_data) {
        array_push($this->comments, $comment_data);
    }

    public function get_list_comment() {
        return $this->comments;
    }
}