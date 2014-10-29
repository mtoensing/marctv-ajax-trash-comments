jQuery(function($) {
  $('a.marctv-trash').click(function() {
    
    var cid = $(this).data('cid');
    var nonce = $(this).data('nonce');

    function getData() {
      return $.ajax({
        type: 'POST',
        url: marctvedc.adminurl,
        data: {
          action: 'marctv_trash_comment',
          cid: cid,
          _wpnonce: nonce
        },
        dataType: 'html'
      });
    }

    function handleData(data /* , textStatus, jqXHR */) {
      if (data > -1) {
        $('#li-comment-' + cid).fadeOut('slow');
      } else {
         $('a.marctv-trash','#li-comment-' + cid).after(' <em class="marctv-error" data-error="' + data + '">error</em>');
      }
    }

    getData().done(handleData);

    return false;
  });
});

