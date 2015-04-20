<?php
if(!class_exists('FacebookSession')) {
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookSession.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookRedirectLoginHelper.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookRequest.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookResponse.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookSDKException.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookRequestException.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/FacebookAuthorizationException.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/GraphObject.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/HttpClients/FacebookCurl.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/HttpClients/FacebookHttpable.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/HttpClients/FacebookCurlHttpClient.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/Entities/AccessToken.php');
    require_once(FACEBOOK_SDK_V4_SRC_DIR . 'Facebook/GraphUser.php');
}

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\GraphUser;

if(!class_exists('Google_Client')) {
    require SB_THEME_LIB_PATH . '/google-api-php-client/src/Google/autoload.php';
}

class SB_Login {

    private $type;
    private $facebook;
    private $google;
    private $twitter;

    public function __construct($type) {
        $this->type = $type;
        $this->init();
    }

    private function init() {
        $this->facebook = array();
        $this->google = array();
        $this->twitter = array();
    }

    private function add_data_social_to_url($url) {
        $type = $this->type;
        $type = SB_Core::encrypt($type);
        if(!empty($url)) {
            $url = add_query_arg(array('data_social' => $type), $url);
        }
        return $url;
    }

    public function set_facebook_arg($args = array()) {
        $app_id = isset($args['app_id']) ? $args['app_id'] : '';
        $app_secret = isset($args['app_secret']) ? $args['app_secret'] : '';
        $callback_url = isset($args['callback_url']) ? $args['callback_url'] : '';
        if(empty($callback_url)) {
            $callback_url = isset($args['redirect_uri']) ? $args['redirect_uri'] : '';
        }
        $callback_url = $this->add_data_social_to_url($callback_url);
        $this->facebook['app_id'] = $app_id;
        $this->facebook['app_secret'] = $app_secret;
        $this->facebook['callback_url'] = $callback_url;
    }

    public function set_google_arg($args = array()) {
        $application_name = isset($args['app_name']) ? $args['app_name'] : 'HocWP';
        $this->google['app_name'] = $application_name;
        $oauth2_client_id = isset($args['client_id']) ? $args['client_id'] : '';
        $this->google['client_id'] = $oauth2_client_id;
        $oauth2_client_secret = isset($args['client_secret']) ? $args['client_secret'] : '';
        $this->google['client_secret'] = $oauth2_client_secret;
        $oauth2_redirect_uri = isset($args['redirect_uri']) ? $args['redirect_uri'] : '';
        if(empty($oauth2_redirect_uri)) {
            $oauth2_redirect_uri = isset($args['callback_url']) ? $args['callback_url'] : '';
        }
        $this->google['redirect_uri'] = $oauth2_redirect_uri;
        $developer_key = isset($args['developer_key']) ? $args['developer_key'] : '';
        if(empty($developer_key)) {
            $developer_key = isset($args['api_key']) ? $args['api_key'] : '';
        }
        $this->google['developer_key'] = $developer_key;
        $site_name = isset($args['site_name']) ? $args['site_name'] : 'www.hocwp.net';
        $this->google['site_name'] = $site_name;
        $this->google['code'] = isset($args['code']) ? $args['code'] : '';
    }

    public function facebook_login() {
        $result = false;
        $app_id = isset($this->facebook['app_id']) ? $this->facebook['app_id'] : '';
        $app_secret = isset($this->facebook['app_secret']) ? $this->facebook['app_secret'] : '';
        if(empty($app_id) || empty($app_secret)) {
            return $result;
        }
        $redirect_uri = isset($this->facebook['callback_url']) ? $this->facebook['callback_url'] : '';
        FacebookSession::setDefaultApplication( $app_id, $app_secret );
        $redirect_uri = esc_url(remove_query_arg(array('redirect_to'), $redirect_uri));
        $helper = new FacebookRedirectLoginHelper($redirect_uri, $app_id, $app_secret);
        $session = null;
        try {
            if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])) {
                $session = new FacebookSession($_SESSION['access_token']);
            } else {
                $session = $helper->getSessionFromRedirect();
            }
        } catch(FacebookRequestException $ex) {

        } catch(Exception $ex) {

        }

        $this->facebook['session'] = $session;

        if(isset($session)) {
            $access_token = $session->getToken();
            $_SESSION['access_token'] = $access_token;
            $this->facebook['profile'] = $access_token;
            $this->facebook['access_token'] = $access_token;
            $this->facebook['logout_url'] = $helper->getLogoutUrl( $session, $redirect_uri );
            $request = ( new FacebookRequest( $session, 'GET', '/me' ) )->execute();
            $user = $request->getGraphObject()->asArray();
            $this->facebook['profile'] = $user;
            $this->facebook['logged_in'] = true;
            $this->facebook['email'] = isset($user['email']) ? $user['email'] : '';
            $result = true;
        } else {
            $this->facebook['login_url'] = $helper->getLoginUrl(array('email'));
        }
        return $result;
    }

    public function google_login() {
        $result = false;
        $client_id = $this->google['client_id'];
        $client_secret = $this->google['client_secret'];
        if(empty($client_id) || empty($client_secret)) {
            return $result;
        }
        $client = new Google_Client();
        $client->setApplicationName($this->google['app_name']);
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $redirect_uri = $this->google['redirect_uri'];
        $redirect_uri = esc_url(remove_query_arg(array('redirect_to'), $redirect_uri));
        $client->setRedirectUri($redirect_uri);
        $client->setState(SB_Core::encrypt($this->type));
        $client->setDeveloperKey($this->google['developer_key']);
        $scopes = array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        );
        $client->addScope($scopes);

        if(!empty($this->google['code'])) {
            $client->authenticate($this->google['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
        }

        if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }
        if($client->getAccessToken()) {
            $token = $client->getAccessToken();
            $oauth = new Google_Service_Oauth2($client);
            $user = $oauth->userinfo->get();
            $this->google['user'] = SB_PHP::object_to_array($user);
            $_SESSION['access_token'] = $token;
            $this->google['access_token'] = $token;
            $this->google['logged_in'] = true;
            $result = true;
        } else {
            $this->google['login_url'] = $client->createAuthUrl();
            $result = false;
        }
        return $result;
    }

    public function get_facebook() {
        return $this->facebook;
    }

    public function get_google() {
        return $this->google;
    }

    public function get_facebook_login_url() {
        $url = '';
        $facebook = $this->get_facebook();
        if(isset($facebook['login_url'])) {
            $url = $facebook['login_url'];
        }
        return $url;
    }

    public function get_google_login_url() {
        $url = '';
        if(isset($this->google['login_url'])) {
            $url = $this->google['login_url'];
        }
        return $url;
    }

    public function get_facebook_user() {
        return isset($this->facebook['profile']) ? $this->facebook['profile'] : '';
    }

    public function get_google_user() {
        return isset($this->google['user']) ? $this->google['user'] : '';
    }

    public function get_facebook_profile() {
        return $this->get_facebook_user();
    }

    public function get_google_profile() {
        return $this->get_google_user();
    }
}