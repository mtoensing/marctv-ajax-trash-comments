jQuery(function ($) {
    $('.marctv-trash').click(function () {
        var trashlink = $(this);
        var cid = $(this).data('cid');
        var nonce = $(this).data('nonce');

        $.ajax({
            type: 'POST',
            url: marctvedc.adminurl,
            data: {
                action: 'marctv_trash_comment',
                cid: cid,
                _wpnonce: nonce
            },
            success: function (data) {
                if (data == 'error' || data == -1) {
                    $(trashlink).addClass('marctv-error').text('Error!');
                } else {
                    $(trashlink).addClass('marctv-ok').text(data);
                }
            },
            dataType: 'html'
        });


        return false;
    });
});

