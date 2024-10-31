(function ($) {
    'use strict';

    $(function () {
        var $deactivateBtn = $('#deactivateScope'),
            $activatedContainer = $('#activated'),
            $deactivatedContainer = $('#activate'),
            $scopeForm = $('#scope-activator-form'),
            $categoriesList = $('#category-list input');

        $activatedContainer.add($deactivatedContainer).hide();

        $.post(ajaxurl, {
            'action': 'get_activation_status'
        }, function (response) {
            if (response && response == 0) {
                showDeactivated()
            } else {
                showActivated();
            }
        });

        $deactivateBtn.click(function () {
            var data = {
                'action': 'deactivate_box'
            };

            $.post(ajaxurl, data, function () {
                showDeactivated();
            });
        });

        $scopeForm.submit(function (e) {
            e.preventDefault();
            var data = {
                'action': 'register_token',
                'token': $('#scope-activation-key').val()
            };

            $.post(ajaxurl, data, function () {
                showActivated();
            });
        });

        $categoriesList.change(function () {
            if ($(this).prop('checked')) {
                var data = {
                    'action': 'select_category',
                    'category_id': $(this).val()
                };
            } else {
                var data = {
                    'action': 'remove_category',
                    'category_id': $(this).val()
                };
            }

            $.post(ajaxurl, data, function () {
                console.log('success');
            });
        });

        var showActivated = function () {
            $activatedContainer.show();
            $deactivatedContainer.hide();
        };

        var showDeactivated = function () {
            $activatedContainer.hide();
            $deactivatedContainer.show();
        };
    });

})(jQuery);
