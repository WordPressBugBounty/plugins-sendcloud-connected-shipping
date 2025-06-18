<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Auth_Code_Generator {

	/**
	 * Generates random code which will be used for authorization purposes
	 *
	 * @param $length
	 *
	 * @return string
	 */
	public static function generate($length)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		return substr(str_shuffle($chars), 0, $length);
	}
}
