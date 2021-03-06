<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action('register_form','dig_wp_bp_reg_fields');
function dig_wp_bp_reg_fields(){
    if(is_account_page() || is_dig_page())return;
    $dig_reg_details = digit_get_reg_fields();
    $reg_mobile_accept = $dig_reg_details['dig_reg_mobilenumber'];
    echo '<div class="dig_wp_bp_fields">';
    if($reg_mobile_accept>0) {
        ?>
        <div class="bbp-mobile">
            <label for="user_login"><?php _e('Mobile Number','digits');?> </label>
            <input type="text" name="" value="" id="username" data-dig-mob="1" only-mob="1">
        </div>
        <?php
    }
    wooc_extra_register_fields_custom();
    wooc_add_extra_otp_reg_field();
    echo '<input type="hidden" name="username" id="dig_reg_wp_elem" value="" />';
    ?>
    <input type="hidden" name="code" id="register_code"/>
    <input type="hidden" name="csrf" id="register_csrf"/>
    <?php

    echo '</div>';
}


add_action('login_form','dig_wp_bp_otp_box');
function dig_wp_bp_otp_box(){
    if(is_account_page() || is_dig_page())return;
    addNewUserNameInLogin(1,'dig_bb_wp_otp_field');
    echo '<input type="hidden" name="username" id="dig_login_wp_elem" value="" />';
}



if(!function_exists('is_account_page')) {
    function is_account_page(){
        if(!function_exists('wc_get_page_id')){
            return false;
        }
        $page_id = wc_get_page_id( 'myaccount' );

        return ( $page_id && is_page( $page_id ) ) || wc_post_content_has_shortcode( 'woocommerce_my_account' ) || apply_filters( 'woocommerce_is_account_page', false );
    }
}




function dig_add_loader(){

    if(is_account_page() || is_dig_page())return;
    $digit_tapp = get_option("digit_tapp", 1);
    ?>
    <div class="dig_load_overlay">
        <div class="dig_load_content">
            <div class="dig_spinner">
                <div class="dig_double-bounce1"></div>
                <div class="dig_double-bounce2"></div>
            </div>
            <?php
            if ($digit_tapp == 1) {
                echo '<div class="dig_overlay_text">' . __("Please check the Pop-up.", "digits") . '</div>';
            }

            ?>

        </div>
    </div>

    <style>
        .login #loginform .dig_wc_mobileLogin{
            margin-bottom: 20px;
        }
        .digor{
            display: inline-block;
        }
        #wp_bb_log_submit{
            width: 78% !important
        }
        .login #loginform .countrycode{
            max-width: 60px;
        }
        .login #loginform #dig_wc_log_otp{
            margin-bottom: 0px;
        }
        #wp_bb_log_submit{
            margin-bottom: 8px !important;
        }
        .login #loginform .dig_resendotp{
            font-size: 12px;
        }
        .wp_login{
            display: flex;
            flex-flow: row wrap;
        }
        .forgetmenot{
            width: 100%;
            padding-bottom: 16px;
        }
        div.digor{
            height: 30px;
            font-size: 12px;
            line-height: 1;
            margin: 9px 7px 0px 7px;
            font-weight: 600;
        }
        .login-action-login .wp_login .dig_otp_block{
            top: 0;
        }
        #wp-submit{
            width: 120px !important;
        }
        .dig_wc_mobileLogin{
            width: 121px !important;
        }
        .login form{
            padding-bottom:28px;
        }
        #dig_wc_log_otp_container{
            padding-bottom: 20px;
        }
        .login #loginform .dig_resendotp{
            margin-bottom: 0px !important;
        }
        .dig_otp_block{
            float: right;
        }
        #wp-submit{
            float: left;
        }
        .dig_otp_blk,.dig_otp_blk #wp_bb_log_submit{
            display: block;
            width: 100% !important;
        }
        #dig_man_resend_otp_btn{
            margin-top: 30px;
        }


    </style>

    <script>
        jQuery(document).ready(function(){
            jQuery('#username').attr('removeStyle',1);
        })
    </script>
    <?php
}
add_action('login_header','dig_add_loader');




function dig_validate_new_user_custom_fields($errors, $user_login, $user_email){
    if(is_numeric($user_login)){

        $user = getUserFromPhone($user_login);
        if($user!=null) {
            $errors->add("UsernameinUse", __("Username is already in use!", "digits"));
        }
    }


    $dig_reg_details = digit_get_reg_fields();
    $reg_mobile_accept = $dig_reg_details['dig_reg_mobilenumber'];
    if($reg_mobile_accept>0){
        $phone = sanitize_mobile_field_dig($_POST['mobile/email']);
        if(empty($phone) && $reg_mobile_accept==2){
            $errors->add('phone',__('Please enter a valid Mobile Number', 'digits'));
            return $errors;
        }else if(!empty($phone)) {
            $code = sanitize_text_field($_POST['code']);
            $csrf = sanitize_text_field($_POST['csrf']);

            $otp = sanitize_text_field($_POST['reg_billing_otp']);

            $countrycode = sanitize_text_field($_POST['digt_countrycode']);

            $digit_tapp = get_option('digit_tapp', 1);

            if (!is_numeric($phone)) {

                $errors->add('phone',__('Please enter a valid Mobile Number', 'digits'));
                return $errors;
            }


            $mobVerificationFailed = __('Mobile Number verification failed','digits');
            if ($digit_tapp == 1) {
                if (empty($code) || !wp_verify_nonce($csrf, 'crsf-otp')) {
                    $errors->add('phone',$mobVerificationFailed);
                    return $errors;
                }
                $json = getUserPhoneFromAccountkit($code);
                $phoneJson = json_decode($json, true);
                if ($json == null) {
                    $errors->add('phone',$mobVerificationFailed);
                    return $errors;

                }

                $mob = $countrycode . $phone;

                if($phoneJson['phone']!=$mob){
                    $errors->add('phone',$mobVerificationFailed);
                    return $errors;

                }

                $mob = $phoneJson['phone'];
                $phone = $phoneJson['nationalNumber'];
                $countrycode = $phoneJson['countrycode'];



            } else {
                if (empty($otp)) {
                    $errors->add('phone',__('Please enter a valid OTP', 'digits'));
                    return $errors;
                }
                if (verifyOTP($countrycode, $phone, $otp, true)) {

                    $mob = $countrycode . $phone;
                } else {
                    $errors->add('phone',$mobVerificationFailed);
                    return $errors;
                }
            }



            $user = getUserFromPhone($mob);
            if ($user == null) {
                global $dig_save_details;
                $dig_save_details = 1;
            }else{
                $errors->add("phone", __("Mobile Number is already in use!", "digits"));
            }

        }



    }





    $reg_custom_fields = stripslashes(base64_decode(get_option("dig_reg_custom_field_data", "e30=")));
    $reg_custom_fields = json_decode($reg_custom_fields, true);
    $errors = validate_digp_reg_fields($reg_custom_fields, $errors);
    return $errors;

}
add_action('registration_errors','dig_validate_new_user_custom_fields',10,3);



function dig_update_new_user_fields_custom_fields($user_id){
    global $dig_save_details;

    if($dig_save_details==1){
        $phone = sanitize_mobile_field_dig($_POST['mobile/email']);
        $countrycode = sanitize_text_field($_POST['digt_countrycode']);

        if(!empty($phone) && !empty($countrycode)) {
            update_user_meta($user_id, 'digt_countrycode', $countrycode);
            update_user_meta($user_id, 'digits_phone_no', $phone);
            update_user_meta($user_id, 'digits_phone', $countrycode . $phone);
        }
    }


    $reg_custom_fields = stripslashes(base64_decode(get_option("dig_reg_custom_field_data", "e30=")));
    $reg_custom_fields = json_decode($reg_custom_fields, true);
    update_digp_reg_fields($reg_custom_fields, $user_id);

}
add_action('register_new_user','dig_update_new_user_fields_custom_fields');



add_action( 'wp_authenticate' , 'dig_update_username' );
function dig_update_username(&$username){
    if(isset($_POST['digt_countrycode']) && isset($_POST['mobile/email'])){
        $countrycode = sanitize_text_field($_POST['digt_countrycode']);
        $mobile = sanitize_text_field($_POST['mobile/email']);

        if(!empty($countrycode) && !empty($mobile)){

            $user = getUserFromPhone($countrycode.$mobile);
            $dig_login_details = digit_get_login_fields();

            if(!empty($user) && $dig_login_details['dig_login_mobilenumber']>0){
                $username = $user->user_login;
            }else{
                $username = $mobile;
            }
        }
    }

}


function is_dig_page(){
    global $dig_logingpage;
    if($dig_logingpage==1){
        return true;
    }else return false;
}


function login_function() {

    add_filter( 'gettext', 'dig_update_login_label', 20, 3 );
    function dig_update_login_label( $translated_text, $text, $domain )
    {

        if($text=== 'Username or Email Address'){
            $dig_login_details = digit_get_login_fields();
            if($dig_login_details['dig_login_mobilenumber']>0) {
                $translated_text = __('Mobile Number/Email Address','digits');
            }
        }
        return $translated_text;
    }
}
add_action( 'login_head', 'login_function' );


function dig_url_language(){
    if(isset($_GET['lang'])){
        return '&lang='.esc_attr($_GET['lang']);
    }else return '';
}




