<?php

namespace SCCSP\SendCloud\Connected\Shipping\Services;

use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Auth_Data;
use SCCSP\SendCloud\Connected\Shipping\Models\SCCSP_Deactivation_Data;
use SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Config_Repository;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SCCSP_Config_Service {
	/**
	 * @var \SCCSP\SendCloud\Connected\Shipping\Repositories\SCCSP_Config_Repository
	 */
	private $config_repository;

	/**
	 * Config_Service constructor.
	 */
	public function __construct() {
		$this->config_repository = new SCCSP_Config_Repository();
	}

	/**
	 * @return string
	 */
	public function get_min_log_level() {
		return $this->config_repository->get( 'MIN_LOG_LEVEL' );
	}

	/**
	 * @param $min_log_level
	 *
	 * @return void
	 */
	public function set_min_log_level( $min_log_level ) {
		$this->config_repository->save( 'MIN_LOG_LEVEL', $min_log_level );
	}

	/**
	 * @return string
	 */
	public function get_integration_id() {
		return $this->config_repository->get( 'INTEGRATION_ID' );
	}

	/**
	 * @param $integration_id
	 *
	 * @return void
	 */
	public function set_integration_id( $integration_id ) {
		$this->config_repository->save( 'INTEGRATION_ID', $integration_id );
	}

	/**
	 * @return string
	 */
	public function get_service_point_script() {
		return $this->config_repository->get( 'SERVICE_POINT_SCRIPT' );
	}

	/**
	 * @param $script
	 *
	 * @return void
	 */
	public function set_service_point_script( $script ) {
		$this->config_repository->save( 'SERVICE_POINT_SCRIPT', $script );
	}

	/**
	 * @return array
	 */
    public function get_service_point_carriers() {
        $cached_carriers = get_transient('service_point_carriers');
        if ($cached_carriers !== false) {
            return $cached_carriers;
        }

        $carriers = $this->config_repository->get( 'SERVICE_POINT_CARRIERS' );

        $carriers_array = $carriers ? json_decode($carriers, true) : [];

        set_transient('sccsp_service_point_carriers', $carriers_array, 30 * MINUTE_IN_SECONDS);

        return $carriers_array;
    }

	/**
	 * @param $carriers
	 *
	 * @return void
	 */
	public function set_service_point_carriers( $carriers ) {
		$this->config_repository->save( 'SERVICE_POINT_CARRIERS', json_encode( $carriers ) );

        delete_transient('service_point_carriers');
    }

	/**
	 * @param SCCSP_Auth_Data $auth_data
	 *
	 * @return void
	 */
	public function set_auth_data( SCCSP_Auth_Data $auth_data ) {
		$this->config_repository->save( 'AUTH_DATA', json_encode( $auth_data->to_array() ) );
	}

	/**
	 * @return SCCSP_Auth_Data
	 */
	public function get_auth_data() {
		$data = $this->config_repository->get( 'AUTH_DATA' );

		return SCCSP_Auth_Data::from_array( $data ? json_decode( $data, true ) : array() );
	}

	/**
	 * @return void
	 */
	public function delete_auth_data() {
		$this->config_repository->delete( 'AUTH_DATA' );
	}

	/**
	 * @param SCCSP_Deactivation_Data $data
	 *
	 * @return void
	 */
	public function save_deactivation_data( SCCSP_Deactivation_Data $data ) {
		$this->config_repository->save( 'DEACTIVATION_DATA', json_encode( $data->to_array() ) );
	}

	/**
	 * @return SCCSP_Deactivation_Data
	 */
	public function get_deactivation_data() {
		$data = $this->config_repository->get( 'DEACTIVATION_DATA' );

		return SCCSP_Deactivation_Data::from_array( $data ? json_decode( $data, true ) : array() );
	}

	/**
	 * @return void
	 */
	public function delete_deactivation_data() {
		$this->config_repository->delete( 'DEACTIVATION_DATA' );

	}

	/**
	 * Gets SendCloud panel url
	 *
	 * @return string
	 */
	public function get_panel_url() {
		$panel_url = getenv( 'SENDCLOUDSHIPPING_PANEL_URL' );
		if ( empty( $panel_url ) ) {
			$panel_url = 'https://panel.sendcloud.sc';
		}

		return $panel_url;
	}

    /**
     * Sets the migration required flag.
     *
     * @param bool $value
     *
     * @return void
     */
    public function set_migration_required($value) {
        $this->config_repository->save('MIGRATION_REQUIRED', $value ? 1 : 0);
    }

    /**
     * Sets the migration completed flag.
     *
     * @return void
     */
    public function set_migration_completed() {
        $this->config_repository->save('MIGRATION_COMPLETED', 1);
    }

    /**
     * Deletes the migration required flag.
     *
     * @return void
     */
    public function delete_migration_required() {
        $this->config_repository->delete('MIGRATION_REQUIRED');
    }

    /**
     * Deletes the migration completed flag.
     *
     * @return void
     */
    public function delete_migration_completed() {
        $this->config_repository->delete('MIGRATION_COMPLETED');
    }

    /**
     * Returns is migration required flag.
     *
     * @return bool
     */
    public function is_migration_required(): bool
    {
        return (bool)$this->config_repository->get('MIGRATION_REQUIRED');
    }

    /**
     * Returns is migration completed flag.
     *
     * @return bool
     */
    public function is_migration_completed(): bool
    {
        return (bool)$this->config_repository->get('MIGRATION_COMPLETED');
    }
}
