jQuery(function ($) {
    $('.' + marctvmoderatejs.pluginprefix + '-trash').click(function () {

        var element = $(this);
        var cid = $(this).data('cid');
        var nonce = $(this).data('nonce');


        $(element).addClass('marctv-moderate-loading');

        if ($(element).hasClass('marctv-moderate-trash')) {
            $(element).text(marctvmoderatejs.trashing_string + '…');
        }

        if ($(element).hasClass('marctv-moderate-untrash')) {
            $(element).text(marctvmoderatejs.untrashing_string + '…');
        }

        $.ajax({
            type: 'POST',
            url: marctvmoderatejs.adminurl,
            data: {
                action: marctvmoderatejs.pluginprefix + '_trash',
                id: cid,
                _ajax_nonce: nonce
            },
            success: function (response_data) {

                $(element).removeClass('marctv-moderate-loading');

                switch (response_data) {
                    case 'trashed':
                        $(element).removeClass('marctv-moderate-trash').addClass('marctv-moderate-untrash').text(marctvmoderatejs.untrash_string);
                        break;
                    case 'untrashed':
                        $(element).removeClass('marctv-moderate-untrash').addClass('marctv-moderate-trash').text(marctvmoderatejs.trash_string);
                        break;
                    default:
                        $(element).addClass('marctv-moderate-error').text(marctvmoderatejs.error_string);
                }
            },
            dataType: 'html'
        });

        return false;
    });
});