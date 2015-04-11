<div class="sb-login-page-box sb-accounts">
    <div class="sb-wrap container">
        <div class="sb-login-page-container">
            <?php
            if(SB_User::is_logged_in()) {
                include SB_LOGIN_PAGE_INC_PATH . '/module/module-account.php';
            } else {
                $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';
                switch($action) {
                    case 'register':
                        include SB_LOGIN_PAGE_INC_PATH . '/module/module-register.php';
                        break;
                    case 'lostpassword':
                        include SB_LOGIN_PAGE_INC_PATH . '/module/module-lost-password.php';
                        break;
                    case 'verify':
                        include SB_LOGIN_PAGE_INC_PATH . '/module/module-verify-email.php';
                        break;
                    default:
                        include SB_LOGIN_PAGE_INC_PATH . '/module/module-login.php';
                        break;
                }
            }
            ?>
        </div>
    </div>
</div>