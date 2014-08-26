<?php
if(!defined("ABSPATH")) exit;
class SB_Spam {
    var $comments;
    var $spam_texts;

    public function __construct() {
        global $sb_spam;
        if(empty($sb_spam) || !is_object($sb_spam)) {
            $this->init();
        } else {
            return $sb_spam;
        }
    }

    private function init() {
        $this->init_comment_spam_data();
        $this->init_spam_data();
    }

    private function init_spam_data() {
        $this->spam_texts = array();
        $this->spam_texts = array_merge($this->comments);
    }

    private function init_comment_spam_data() {
        $this->comments = array();
        $this->add_comment("sivictxixxgn");
        $this->add_comment("ealwibidnwfc");
    }

    public function add_comment($string) {
        if(is_array($this->comments) && !in_array($string, $this->comments)) {
            array_push($this->comments, $string);
        }
    }

    public function get_list_comment() {
        return $this->comments;
    }

    public function check($text) {
        foreach($this->spam_texts as $value) {
            if(SB_PHP::is_string_contain($text, $value)) {
                return true;
            }
        }
        return false;
    }

    public function check_comment($comment_data) {
        return SB_WP::is_spam_comment($comment_data);
    }
}