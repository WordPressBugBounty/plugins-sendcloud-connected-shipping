<?php

namespace SCCSP\SendCloud\Connected\Shipping\Utility;

use SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service;
use WC_Logger;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Logger
{
    /**
     * Log levels
     */
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    /**
     * Level strings mapped to integer severity.
     *
     * @var array
     */
    public static $level_to_severity = array(
        self::EMERGENCY => 800,
        self::ALERT     => 700,
        self::CRITICAL  => 600,
        self::ERROR     => 500,
        self::WARNING   => 400,
        self::NOTICE    => 300,
        self::INFO      => 200,
        self::DEBUG     => 100
    );

    /**
     * Instance of Logger class
     *
     * @var SCCSP_Logger
     */
    private static $instance;

    /**
     * WooCommerce logger
     *
     * @var WC_Logger|null
     */
    private $wc_logger;

    /**
     * @var \SCCSP\SendCloud\Connected\Shipping\Services\SCCSP_Config_Service
     */
    private $config_service;

    /**
     * Logger constructor.
     */
    public function __construct() {
	    $this->wc_logger = SCCSP_Logger_Factory::create();
        $this->config_service = new SCCSP_Config_Service();
    }

    /**
     * Gets logger instance
     *
     * @return SCCSP_Logger
     */
    private static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
	 * Log info level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function info( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::INFO ) ) {
			self::get_instance()->log( self::INFO, $message, $context );
		}
	}

	/**
	 * Log debug level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function debug( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::DEBUG ) ) {
			self::get_instance()->log( self::DEBUG, $message, $context );
		}
	}

	/**
	 * Log error level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function error( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::ERROR ) ) {
			self::get_instance()->log( self::ERROR, $message, $context );
		}
	}

	/**
	 * Log notice level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function notice( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::NOTICE ) ) {
			self::get_instance()->log( self::NOTICE, $message, $context );
		}
	}

	/**
	 * Log warning level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function warning( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::WARNING ) ) {
			self::get_instance()->log( self::WARNING, $message, $context );
		}
	}

	/**
	 * Log alert level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function alert( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::ALERT ) ) {
			self::get_instance()->log( self::ALERT, $message, $context );
		}
	}

	/**
	 * Log critical level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function critical( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::CRITICAL ) ) {
			self::get_instance()->log( self::CRITICAL, $message, $context );
		}
	}

	/**
	 * Log emergency level message
	 *
	 * @param $message
	 * @param array $context
	 */
	public static function emergency( $message, $context = array() ) {
		if ( self::get_instance()->check_if_message_should_be_logged( self::EMERGENCY ) ) {
			self::get_instance()->log( self::EMERGENCY, $message, $context );
		}
	}

	/**
	 * Log message
	 *
	 * @param $level
	 * @param $message
	 * @param array $context
	 */
	private function log( $level, $message, $context = array() ) {
		if ( ! empty( $context['trace'] ) ) {
			$message .= PHP_EOL . 'Stack trace: ' . PHP_EOL . $context['trace'];
		}

		SCCSP_Logger_Factory::log( $this->wc_logger, $level, $message, $context );
	}

	/**
	 * @param string $log_level
	 *
	 * @return bool
	 */
	private function check_if_message_should_be_logged( $log_level ) {
		if ( $this->wc_logger ) {
			$min_log_level = $this->config_service->get_min_log_level() ?: self::$level_to_severity[ self::WARNING ];

			return self::$level_to_severity[ $log_level ] >= $min_log_level;
		}

		return false;
	}

}
