<?php


if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__DIR__) . 'Twilio/autoload.php';

use Twilio\Rest\Client;


add_action("wp_ajax_nopriv_digits_resendotp", "digits_resendotp");

add_action("wp_ajax_digits_resendotp", "digits_resendotp");

function digits_resendotp()
{
    $digit_tapp = get_option('digit_tapp', 1);
    if ($digit_tapp == 1) die();
    $countrycode = sanitize_text_field($_REQUEST['countrycode']);
    $mobileno = sanitize_mobile_field_dig($_REQUEST['mobileNo']);
    $csrf = $_REQUEST['csrf'];
    $login = $_REQUEST['login'];

    if (!checkwhitelistcode($countrycode)) {
        echo "-99";
        die();
    }

    if (!wp_verify_nonce($csrf, 'dig_form')) {
        echo '0';
        die();
    }

    $users_can_register = get_option('dig_enable_registration', 1);
    $digforgotpass = get_option('digforgotpass', 1);
    if ($users_can_register == 0 && $login == 2) {
        echo "0";
        die();
    }
    if ($digforgotpass == 1 && $login == 3) {
        echo "0";
        die();
    }

    if (OTPexists($countrycode, $mobileno, true)) {
        digits_check_mob();
    }
    echo "0";
    die();

}


add_action("wp_ajax_nopriv_digits_verifyotp_login", "digits_verifyotp_login");

add_action("wp_ajax_digits_verifyotp_login", "digits_verifyotp_login");


function checkwhitelistcode($code)
{


    $whiteListCountryCodes = get_option("whitelistcountrycodes");

    $size = sizeof($whiteListCountryCodes);
    if ($size > 0 && is_array($whiteListCountryCodes)) {

        $countryarray = getCountryList();
        $code = str_replace("+", "", $code);

        foreach ($countryarray as $key => $value) {
            if ($value == $code) {
                if (in_array($key, $whiteListCountryCodes)) {
                    return true;
                }
            }

        }

        return false;
    }
    return true;

}

function digits_verifyotp_login()
{
    $digit_tapp = get_option('digit_tapp', 1);
    if ($digit_tapp == 1) die();
    $countrycode = sanitize_text_field($_REQUEST['countrycode']);


    if (!checkwhitelistcode($countrycode)) {
        echo "-99";
        die();
    }


    $mobileno = sanitize_mobile_field_dig($_REQUEST['mobileNo']);
    $csrf = $_REQUEST['csrf'];
    $otp = sanitize_text_field($_REQUEST['otp']);
    $del = false;


    $users_can_register = get_option('dig_enable_registration', 1);
    $digforgotpass = get_option('digforgotpass', 1);
    if ($users_can_register == 0 && $_REQUEST['dtype'] == 2) {
        echo "1013";
        die();
    }
    if ($digforgotpass == 0 && $_REQUEST['dtype'] == 3) {
        echo "0";
        die();
    }

    if (!wp_verify_nonce($csrf, 'dig_form')) {
        echo '1011';
        die();
    }


    if ($_REQUEST['dtype'] == 1) $del = true;

    $rememberMe = false;
    if (isset($_GET['rememberMe']) && $_GET['rememberMe'] === true) {
        $rememberMe = true;
    }

    if (verifyOTP($countrycode, $mobileno, $otp, $del)) {

        $user1 = getUserFromPhone($countrycode . $mobileno);
        if ($user1) {

            if ($_REQUEST['dtype'] == 1) {
                wp_set_current_user($user1->ID, $user1->user_login);
                wp_set_auth_cookie($user1->ID, $rememberMe);
                echo '11';
            } else {
                echo '1';
            }

            die();
        } else {
            echo '-1';
            die();
        }


    } else {
        echo '0';
        die();
    }

}

add_action("wp_ajax_nopriv_digits_check_mob", "digits_check_mob");
add_action("wp_ajax_digits_check_mob", "digits_check_mob");


function sanitize_mobile_field_dig($mobile)
{
    return ltrim(sanitize_text_field($mobile), '0');
}

function digits_check_mob()
{


    $dig_login_details = digit_get_login_fields();
    $mobileaccp = $dig_login_details['dig_login_mobilenumber'];
    $otpaccp = $dig_login_details['dig_login_otp'];

    $digit_tapp = get_option('digit_tapp', 1);
    if ($digit_tapp == 1) die();
    $countrycode = sanitize_text_field($_REQUEST['countrycode']);
    $mobileno = sanitize_mobile_field_dig($_REQUEST['mobileNo']);
    $csrf = $_REQUEST['csrf'];
    $login = $_REQUEST['login'];


    if (($otpaccp == 0 && $login == 1) || ($mobileaccp == 0 && $login == 1)) {
        echo "-99";
        die();
    }

    if (!checkwhitelistcode($countrycode)) {
        echo "-99";
        die();
    }

    if (!wp_verify_nonce($csrf, 'dig_form')) {
        echo '0';
        die();
    }


    if (isset($_POST['captcha']) && isset($_POST['captcha_ses'])) {
        $ses = filter_var($_POST['captcha_ses'], FILTER_SANITIZE_NUMBER_FLOAT);
        if ($_POST['captcha'] != $_SESSION['dig_captcha' . $ses]) {
            echo '9194';
            die();
        }
    }
    $users_can_register = get_option('dig_enable_registration', 1);
    $digforgotpass = get_option('digforgotpass', 1);
    if ($users_can_register == 0 && $login == 2) {
        echo "0";
        die();
    }
    if ($digforgotpass == 0 && $login == 3) {
        echo "0";
        die();
    }

    if ($login == 2 || $login == 11) {
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = sanitize_text_field($_POST['username']);
            if (username_exists($username)) {
                die('9192');
            }
        }
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $email = sanitize_text_field($_POST['email']);
            if (email_exists($email)) {
                if ($login == 11) {
                    $user = get_user_by('email', $email);
                    if ($user->ID != get_current_user_id()) {
                        die('9193');
                    }

                } else {
                    die('9193');
                }
            }
        }

    }


    $user1 = getUserFromPhone($countrycode . $mobileno);
    if (($user1 != null && $login == 11) || ($user1 != null && $login == 2)) {

        echo "-1";
        die();
    }
    if ($user1 != null || $login == 2 || $login == 11) {

        $digit_tapp = get_option('digit_tapp', 1);


        if ($digit_tapp != 13) {

            if (OTPexists($countrycode, $mobileno)) {
                echo "1";
                die();
            }

            $code = dig_get_otp();


            if (!digit_send_otp($digit_tapp, $countrycode, $mobileno, $code)) {
                echo "0";
                die();
            }


            $mobileVerificationCode = md5($code);

            global $wpdb;
            $table_name = $wpdb->prefix . "digits_mobile_otp";

            $db = $wpdb->replace($table_name, array(
                'countrycode' => $countrycode,
                'mobileno' => $mobileno,
                'otp' => $mobileVerificationCode,
                'time' => date("Y-m-d H:i:s", strtotime("now"))
            ), array(
                    '%d',
                    '%s',
                    '%s',
                    '%s')
            );

            if (!$db) {
                echo "0";
                die();
            }

        }

        echo "1";
        die();

    } else {
        echo '-11';
        die();
    }

    echo "0";
    die();

}


function digit_send_otp($digit_tapp, $countrycode, $mobile, $otp, $testCall = false)
{


    $dig_messagetemplate = get_option("dig_messagetemplate", "Your OTP for %NAME% is %OTP%");
    $dig_messagetemplate = str_replace("%NAME%", get_option('blogname'), $dig_messagetemplate);
    $dig_messagetemplate = str_replace("%OTP%", $otp, $dig_messagetemplate);

    return digit_send_message($digit_tapp, $countrycode, $mobile, $otp, $dig_messagetemplate, $testCall);

}

function digit_send_message($digit_tapp, $countrycode, $mobile, $otp, $dig_messagetemplate, $testCall = false)
{


    switch ($digit_tapp) {

        case 512:    //IppanelSMS

            try {
                require_once plugin_dir_path(__DIR__) . 'gateways/ippanel/class-ippanel.php';
                $ippanel = get_option('digit_ippanel');
                $sample = $ippanel['sample'];
                $shop_name = $ippanel['shopname'];
                if ($sample == 1) {
                    if ($shop_name == '') {
                        $dig_messagetemplate = "patterncode:121;activate-code:" . $otp;
                    } else {
                        $dig_messagetemplate = "patterncode:246;app_name:" . $shop_name . ";code:" . $otp;
                    }
                }
                $ippanelSMS = new digits_ippanelsms($ippanel);
                $param = array
                (
                    'message' => $dig_messagetemplate,
                    'to' => $mobile,
                );

                // Setup and send a message
                $result = $ippanelSMS->send($param);
                // Check if the send was successful
                if ($result['success']) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        default:
            return false;
    }


}

add_action("wp_ajax_nopriv_digits_login_user", "digits_login_user");


function digits_login_user()
{


    $code = sanitize_text_field($_REQUEST['code']);
    $csrf = sanitize_text_field($_REQUEST['csrf']);


    $dig_login_details = digit_get_login_fields();
    $mobileaccp = $dig_login_details['dig_login_mobilenumber'];
    $otpaccp = $dig_login_details['dig_login_otp'];


    if (!wp_verify_nonce($csrf, 'crsf-otp') || $mobileaccp == 0 || $otpaccp == 0) {
        echo '0';
        die();
    }


    $json = getUserPhoneFromAccountkit($code);

    $phoneJson = json_decode($json, true);


    $phone = $phoneJson['phone'];


    $rememberMe = false;
    if (isset($_GET['rememberMe']) && $_GET['rememberMe'] === true) {
        $rememberMe = true;
    }


    if ($json != null) {
        $user1 = getUserFromPhone($phone);
        if ($user1) {
            wp_set_current_user($user1->ID, $user1->user_login);
            wp_set_auth_cookie($user1->ID, $rememberMe);

            do_action('wp_login', $user1->user_login, $user1);

            echo '1';
            die();
        } else {
            echo '-1';
            die();
        }
    } else {
        echo '-9';
        die();
    }


    echo '0';
    die();
}


function dig_get_otp($isPlaceHolder = false)
{
    $dig_otp_size = get_option("dig_otp_size", 5);
    $digit_tapp = get_option('digit_tapp', 1);
    if ($digit_tapp == 1 || $digit_tapp == 13) {
        $dig_otp_size = 6;
    }
    $code = "";
    for ($i = 0; $i < $dig_otp_size; $i++) {
        if (!$isPlaceHolder) {
            $code .= rand(0, 9);
        } else {
            $code .= '-';
        }

    }

    return $code;
}

function digits_test_api()
{

    if (!current_user_can('manage_options')) {
        echo '0';
        die();
    }

    $mobile = sanitize_text_field($_POST['digt_mobile']);
    $countrycode = sanitize_text_field($_POST['digt_countrycode']);
    if (empty($mobile) || !is_numeric($mobile) || empty($countrycode) || !is_numeric($countrycode)) {
        _e('Invalid Mobile Number', 'digits');
        die();
    }

    $digit_tapp = get_option('digit_tapp', 1);

    $code = dig_get_otp();

    $result = digit_send_otp($digit_tapp, $countrycode, $mobile, $code, true);
    if (!$result) {
        _e('Error', 'digits');
        die();
    }
    print_r($result);
    die();

}

add_action('wp_ajax_digits_test_api', 'digits_test_api');


function dig_validate_login_captcha()
{
    $ses = filter_var($_POST['dig_captcha_ses'], FILTER_SANITIZE_NUMBER_FLOAT);
    if ($_POST['digits_reg_logincaptcha'] != $_SESSION['dig_captcha' . $ses]) {
        return false;
    } else if (isset($_SESSION['dig_captcha' . $ses])) {
        unset($_SESSION['dig_captcha' . $ses]);
        return true;
    }

}


?>
