jQuery(function($) {
  $('a.marctv-trash').click(function() {
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
      success: function(data) {
        if (data > -1) {
          $(trashlink).after(' <em class="marctv-ok" data-error="' + data + '">trashed!</em>');
        } else {
          $(trashlink).after(' <em class="marctv-error" data-error="' + data + '">error</em>');
        }
      },
      dataType: 'html'
    });


    return false;
  });
});

