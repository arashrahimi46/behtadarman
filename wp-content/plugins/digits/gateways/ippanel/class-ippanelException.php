<?php
/**
 * ippanel SMS PHP API
 *
 * @package     ippanel
 * @copyright   Rangine 2018
 * @license     MIT
 * @link        http://www.ippanel.com
 */ 

/*
 * ippanelException
 *
 * The ippanelsms wrapper class will throw these if a general error
 * occurs with your request, for example, an invalid username.
 *
 * @package     ippanelsms
 * @subpackage  Exception
 * @since       1.0
 */
class ippanelException extends Exception {

    public function __construct( $message, $code = 0 ) {
        // make sure everything is assigned properly
        parent::__construct( $message, $code );
    }
}
