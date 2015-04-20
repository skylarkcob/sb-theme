<?php
if(!SB_Option::social_login_enabled()) {
    return;
}
$facebook = SB_Option::get_social_login_app('facebook');
$facebook_enabled = isset($facebook['enabled']) ? (bool)$facebook['enabled'] : false;
$google = SB_Option::get_social_login_app('google');
$google_enabled = isset($google['enabled']) ? (bool)$google['enabled'] : false;
$twitter = SB_Option::get_social_login_app('twitter');
$twitter_enabled = isset($twitter['enabled']) ? (bool)$twitter['enabled'] : false;
$has_button = false;
if($facebook_enabled || $google_enabled || $twitter_enabled) {
    $has_button = true;
}
?>
<?php if($has_button) : ?>
    <div class="sb-theme-social-login">
        <div class="hr">
            <div class="inner">or</div>
        </div>
        <?php if($facebook_enabled) : ?>
            <button type="button" class="sb-theme-btn-social btn-facebook btn-primary btn-social" data-social="facebook">
                <svg class="flip-icon" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 33 33">
                    <g>
                        <path d="M 17.996,32L 12,32 L 12,16 l-4,0 l0-5.514 l 4-0.002l-0.006-3.248C 11.993,2.737, 13.213,0, 18.512,0l 4.412,0 l0,5.515 l-2.757,0 c-2.063,0-2.163,0.77-2.163,2.209l-0.008,2.76l 4.959,0 l-0.585,5.514L 18,16L 17.996,32z"/>
                    </g>
                </svg>
                <span>Login with Facebook</span>
            </button>
        <?php endif; ?>
        <?php if($google_enabled) : ?>
            <button type="button" class="sb-theme-btn-social btn-gplus btn-primary btn-social" data-social="gplus">
                <svg class="flip-icon" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 30 28">
                    <g>
                        <path d="M 17.471,2c0,0-6.28,0-8.373,0C 5.344,2, 1.811,4.844, 1.811,8.138c0,3.366, 2.559,6.083, 6.378,6.083 c 0.266,0, 0.524-0.005, 0.776-0.024c-0.248,0.475-0.425,1.009-0.425,1.564c0,0.936, 0.503,1.694, 1.14,2.313 c-0.481,0-0.945,0.014-1.452,0.014C 3.579,18.089,0,21.050,0,24.121c0,3.024, 3.923,4.916, 8.573,4.916 c 5.301,0, 8.228-3.008, 8.228-6.032c0-2.425-0.716-3.877-2.928-5.442c-0.757-0.536-2.204-1.839-2.204-2.604 c0-0.897, 0.256-1.34, 1.607-2.395c 1.385-1.082, 2.365-2.603, 2.365-4.372c0-2.106-0.938-4.159-2.699-4.837l 2.655,0 L 17.471,2z M 14.546,22.483c 0.066,0.28, 0.103,0.569, 0.103,0.863c0,2.444-1.575,4.353-6.093,4.353 c-3.214,0-5.535-2.034-5.535-4.478c0-2.395, 2.879-4.389, 6.093-4.354c 0.75,0.008, 1.449,0.129, 2.083,0.334 C 12.942,20.415, 14.193,21.101, 14.546,22.483z M 9.401,13.368c-2.157-0.065-4.207-2.413-4.58-5.246 c-0.372-2.833, 1.074-5.001, 3.231-4.937c 2.157,0.065, 4.207,2.338, 4.58,5.171 C 13.004,11.189, 11.557,13.433, 9.401,13.368zM 26,8L 26,2L 24,2L 24,8L 18,8L 18,10L 24,10L 24,16L 26,16L 26,10L 32,10L 32,8 z"/>
                    </g>
                </svg>
                <span>Login with Google+</span>
            </button>
        <?php endif; ?>
        <?php if($twitter_enabled) : ?>
            <button type="button" class="sb-theme-btn-social btn-twitter btn-primary btn-social" data-social="twitter">
                <svg class="flip-icon" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 33 33">
                    <g>
                        <path d="M 32,6.076c-1.177,0.522-2.443,0.875-3.771,1.034c 1.355-0.813, 2.396-2.099, 2.887-3.632 c-1.269,0.752-2.674,1.299-4.169,1.593c-1.198-1.276-2.904-2.073-4.792-2.073c-3.626,0-6.565,2.939-6.565,6.565 c0,0.515, 0.058,1.016, 0.17,1.496c-5.456-0.274-10.294-2.888-13.532-6.86c-0.565,0.97-0.889,2.097-0.889,3.301 c0,2.278, 1.159,4.287, 2.921,5.465c-1.076-0.034-2.088-0.329-2.974-0.821c-0.001,0.027-0.001,0.055-0.001,0.083 c0,3.181, 2.263,5.834, 5.266,6.438c-0.551,0.15-1.131,0.23-1.73,0.23c-0.423,0-0.834-0.041-1.235-0.118 c 0.836,2.608, 3.26,4.506, 6.133,4.559c-2.247,1.761-5.078,2.81-8.154,2.81c-0.53,0-1.052-0.031-1.566-0.092 c 2.905,1.863, 6.356,2.95, 10.064,2.95c 12.076,0, 18.679-10.004, 18.679-18.68c0-0.285-0.006-0.568-0.019-0.849 C 30.007,8.548, 31.12,7.392, 32,6.076z"/>
                    </g>
                </svg>
                <span>Login with Twitter</span>
            </button>
        <?php endif; ?>
    </div>
<?php endif; ?>