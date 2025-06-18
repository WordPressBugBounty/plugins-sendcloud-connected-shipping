<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Base64_Url_Encoder {

	/**
	 * @param $data
	 *
	 * @return string
	 */
	public static function encode($data) {
		$base64 = base64_encode($data);
		$base64url = strtr($base64, '+/', '-_');

		return rtrim($base64url, '=');
	}
}
