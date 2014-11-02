jQuery(function ($) {
    $('.marctv-trash-btn').click(function () {
        var trashlink = $(this);
        var cid = $(this).data('cid');
        var nonce = $(this).data('nonce');

        $(trashlink).addClass('marctv-loading');

        if ( $(trashlink).hasClass('marctv-trash')) {
            $(trashlink).text(marctvedc.trashing_string);
        }

        if ( $(trashlink).hasClass('marctv-untrash')) {
            $(trashlink).text(marctvedc.untrashing_string);
        }

        $.ajax({
            type: 'POST',
            url: marctvedc.adminurl,
            data: {
                action: 'marctv_trash_comment',
                cid: cid,
                _wpnonce: nonce
            },
            success: function (data) {
                $(trashlink).removeClass('marctv-loading');

                switch(data) {
                    case 'trashed':
                        $(trashlink).removeClass('marctv-trash').addClass('marctv-untrash').text(marctvedc.untrash_string);
                        break;
                    case 'untrashed':
                        $(trashlink).removeClass('marctv-untrash').addClass('marctv-trash').text(marctvedc.trash_string);
                        break;
                    default:
                        $(trashlink).addClass('marctv-error').text(marctvedc.error_string);

                }

            },
            dataType: 'html'
        });

        return false;
    });
});

