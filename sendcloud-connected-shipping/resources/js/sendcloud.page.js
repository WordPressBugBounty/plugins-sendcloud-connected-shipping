(function ($) {
    'use strict';
    $(document).ready(function () {
        let button = $('.sendcloud-content.connect button.sendcloud-button.connect'),
            agreement = $('#sc_agreement'),
            connectingLabel = $('#sc-connecting-label')[0],
            connectContainer = $('#sc-connect-container')[0],
            dashboardContainer = $('#sc-dashboard-container')[0],
            migrationInitiation = $('#sc-migration-initiation')[0],
            migrateButton = $('#sc-migrate-service-points'),
            screenshotElement = $('.sc-screenshot-thumb');

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

            let pollingInterval = setInterval(checkStatus, 2000);

            function checkStatus() {
                $.post(ajaxurl, data, function (response) {
                    if (response.is_connected) {
                        location.reload();
                    }
                });
            }
        });

        function checkMigrationStatus() {
            let data = {
                'action': 'sc_check_migration'
            };

            $.post(ajaxurl, data, function (response) {
                if (migrationInitiation) {
                    migrationInitiation.classList.toggle('sc-hidden', !response.show_migration_button);
                }
            });
        }

        setInterval(checkMigrationStatus, 1000);

        migrateButton.click(function () {
            migrateButton.prop('disabled', true);

            let data = {
                'action': 'migrate_service_points'
            };

            $.post(ajaxurl, data, function (response) {
                if (response.success) {
                    migrationInitiation.classList.add('sc-hidden');
                    $('#sc-migration-completed-steps').removeClass('sc-hidden');
                } else {
                    $('#sc-migration-action-error').text('Migration failed. Please try again or contact support.').removeClass('sc-hidden');
                    migrateButton.prop('disabled', false);
                }
            }).fail(function () {
                $('#sc-migration-action-error').text('AJAX request failed. Please check your connection.').removeClass('sc-hidden');

                setTimeout(() => {
                    $('#sc-migration-action-error').addClass('sc-hidden');
                }, 5000);

                migrateButton.prop('disabled', false);
            });
        });

        $('.sc-accordion-header').on('click', function(){
            let content = $(this).next('.sc-accordion-content');

            $('.sc-accordion-content').not(content).slideUp();
            $('.sc-accordion-header').not(this).removeClass('open');

            content.slideToggle();
            $(this).toggleClass('open');
        });

        $('.sc-sub-accordion-header').on('click', function(){
            let content = $(this).next('.sc-sub-accordion-content');

            $('.sc-sub-accordion-content').not(content).slideUp();
            $('.sc-sub-accordion-header').not(this).removeClass('open');

            content.slideToggle();
            $(this).toggleClass('open');
        });

        screenshotElement.on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');
        });

        $(document).on('click', function () {
            screenshotElement.removeClass('active');
        });

        $(document).on('keyup', function (e) {
            if (e.key === 'Escape') {
                screenshotElement.removeClass('active');
            }
        });
    });
})(jQuery);