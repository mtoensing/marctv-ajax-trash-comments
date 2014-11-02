jQuery(function ($) {
    $('.marctv-trash-btn').click(function () {

        var trashlink = $(this);
        var cid = $(this).data('cid');
        var nonce = $(this).data('nonce');

        $(trashlink).addClass('marctv-loading');

        if ($(trashlink).hasClass('marctv-trash')) {
            $(trashlink).text(marctvmoderate.trashing_string + '…');
        }

        if ($(trashlink).hasClass('marctv-untrash')) {
            $(trashlink).text(marctvmoderate.untrashing_string + '…');
        }

        $.ajax({
            type: 'POST',
            url: marctvmoderate.adminurl,
            data: {
                action: 'marctv_trash_comment',
                cid: cid,
                _wpnonce: nonce
            },
            success: function (response_data) {
                $(trashlink).removeClass('marctv-loading');

                switch (response_data) {
                    case 'trashed':
                        $(trashlink).removeClass('marctv-trash').addClass('marctv-untrash').text(marctvmoderate.untrash_string);
                        break;
                    case 'untrashed':
                        $(trashlink).removeClass('marctv-untrash').addClass('marctv-trash').text(marctvmoderate.trash_string);
                        break;
                    default:
                        $(trashlink).addClass('marctv-error').text(marctvmoderate.error_string);

                }

            },
            dataType: 'html'
        });

        return false;
    });

    $('.marctv-replace-btn').click(function () {
        if (confirm(marctvmoderate.confirm_string)) {

            var replacelink = $(this);
            var cid = $(this).data('cid');
            var nonce = $(this).data('nonce');

            $(replacelink).addClass('marctv-loading');

            if ($(replacelink).hasClass('marctv-replace')) {
                $(replacelink).text(marctvmoderate.replacing_string + '…');
            }

            $.ajax({
                type: 'POST',
                url: marctvmoderate.adminurl,
                data: {
                    action: 'marctv_replace_comment',
                    cid: cid,
                    _wpnonce: nonce
                },
                success: function (response_data) {
                    if (response_data != 0) {
                        $(replacelink).addClass("marctv-done").removeClass('marctv-loading').text(marctvmoderate.replaced_string);
                    } else {
                        $(replacelink).addClass("marctv-error").removeClass('marctv-loading').text(marctvmoderate.already_replaced_string);
                    }
                },
                dataType: 'html'
            });
        }

        return false;
    });
});

