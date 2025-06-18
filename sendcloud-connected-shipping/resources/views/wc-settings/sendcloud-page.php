<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Contains data regarding shipping configuration
 *
 * @var array $data
 */

?>
<input type="hidden"  id="sc-connecting-label"
        value="<?php esc_html_e( 'Connecting...', 'sendcloud-connected-shipping' ); ?>"
>
<ul id="sendcloud-config-panel">
	<li class="sendcloud-side-content">
		<div id="sendcloud_shipping_connect" class="sendcloud-panel-box sendcloud-integration-info">
			<h3>
				<?php esc_html_e( 'Sendcloud Integration', 'sendcloud-connected-shipping' ); ?>
			</h3>

			<div id="sc-connect-container" class="sendcloud-button-text-container <?php if($data['integration_id']){
                echo esc_attr('sc-hidden');
            }?>">
                <button disabled class="sendcloud-button sendcloud-button--primary connect-button<?php echo esc_attr( !$data['permalinks_enabled'] ? ' sendcloud-button-disabled' : '' ); ?>">
                    <?php esc_html_e( 'Connect', 'sendcloud-connected-shipping' ); ?>
                </button>
                <div class="sendcloud-agreement-container">
                    <p>
                        <?php echo translate( 'This plugin will create a new user named sendcloud_api', 'sendcloud-connected-shipping' ); ?>
                    </p>
                    <p>
                        <label for="cs_agreement" class="show_if_simple tips has-checkbox" style="">
                            <input id="cs_agreement" name="cs_agreement" type="checkbox"><?php esc_html_e( 'I acknowledge and agree', 'sendcloud-connected-shipping' ); ?>
                        </label>
                    </p>
                </div>
            </div>

			<div id="sc-dashboard-container" class="sendcloud-button-text-container <?php if(!$data['integration_id']){
				echo esc_attr('sc-hidden');
			}?>">
				<p>
					<?php esc_html_e( 'Want to change any setting?', 'sendcloud-connected-shipping' ); ?>
				</p>
				<a href="<?php echo esc_url( $data['panel_url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<button class="sendcloud-button sendcloud-button--primary">
						<?php esc_html_e( 'Go to Sendcloud', 'sendcloud-connected-shipping' ); ?>
					</button>
				</a>
			</div>
            <div id="sendcloud_migration_panel" class="sendcloud-button-text-container <?php if (!$data['integration_id'] || !$data['migration_required']) {
                echo esc_attr('sc-hidden');
            } ?>">
                <h3><?php esc_html_e('Service Point Migration', 'sendcloud-connected-shipping'); ?></h3>
                <p>
                    <?php esc_html_e('Click the button below to migrate your service points from V1 to V2.', 'sendcloud-connected-shipping'); ?>
                </p>
                <button id="migrate-service-points" class="sendcloud-button sendcloud-button--primary">
                    <?php esc_html_e('Migrate Service Points', 'sendcloud-connected-shipping'); ?>
                </button>
                <div id="migration-message" class="sendcloud-notification" style="display: none;"></div>
            </div>
		</div>
	</li>
</ul>
