<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 15/05/2016
 * Time: 17:15
 */

/**
 * Helper function for flash message
 *
 * @param $message
 */
function flash($message) {
	session()->flash('message', $message);
}