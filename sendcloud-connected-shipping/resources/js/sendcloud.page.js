(function ($) {
    'use strict';
    $(document).ready(function () {
        let button = $('#sendcloud_shipping_connect .connect-button'),
            agreement = $('#cs_agreement'),
            connectingLabel = $('#sc-connecting-label')[0],
            connectContainer = $('#sc-connect-container')[0],
            dashboardContainer = $('#sc-dashboard-container')[0];

        agreement.change(function () {
            if (this.checked) {
                button.removeAttr('disabled');
            } else {
                button.attr('disabled','disabled');
            }
        });

        button.click(function () {
            let data = {
                'action': 'get_redirect_sc_v2_url'
            };
            $.post(ajaxurl, data, function (response) {
                if (response.redirect_url) {
                    window.open(response.redirect_url, '_blank');
                    button[0].innerHTML = '<div class="sc-loader"></div> ' + connectingLabel.value;
                    agreement.attr('disabled','disabled');
                }
            });
            data = {
                'action': 'sc_check_status'
            };

            var pollingInterval = setInterval(checkStatus, 2000);

            function checkStatus() {
                $.post(ajaxurl, data, function (response) {
                    if (response.is_connected) {
                        connectContainer.classList.add('sc-hidden');
                        dashboardContainer.classList.remove('sc-hidden');
                    }
                });
            }
        });

        let migrationPanel = $('#sendcloud_migration_panel')[0];

        function checkMigrationStatus() {
            let data = {
                'action': 'sc_check_migration'
            };

            $.post(ajaxurl, data, function (response) {
                if (response.show_migration_button) {
                    migrationPanel.style.display = 'block';
                } else {
                    migrationPanel.style.display = 'none';
                }
            });
        }

        setInterval(checkMigrationStatus, 1000);

        let migrateButton = $('#migrate-service-points');
        let message = $('#migration-message');

        migrateButton.click(function () {
            let data = {
                'action': 'migrate_service_points'
            };

            message.text('').hide();

            $.post(ajaxurl, data, function (response) {
                if (response.success) {
                    showMessage(response.message, 'green');
                } else {
                    showMessage(response.message || 'Unknown error occurred.', 'red');
                }
            }).fail(function () {
                showMessage('AJAX request failed.', 'red');
            });

            function showMessage(text, color) {
                message
                    .text(text)
                    .css({'color': color, 'display': 'block'})
                    .fadeIn();

                setTimeout(() => {
                    message.fadeOut();
                }, 3000);
            }
        });
    });
})(jQuery);