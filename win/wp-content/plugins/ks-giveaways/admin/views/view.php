<div class="wrap">
  <h2><?php the_title() ?> <a href="<?php echo admin_url('edit.php?post_type='.KS_GIVEAWAYS_POST_TYPE) ?>" class="add-new-h2">Back</a></h2>

  <!-- Winners Table -->
  <?php echo $list_table->display() ?>
  <!-- End Winners Table -->

</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

  saveGiveawayWinnerImage = function(id, url) {
    var data = {
      action: 'ks_save_giveaways_winner_avatar',
      id: id,
      value: url
    }

    $.post(ajaxurl, data, function(response) {
      $('#winner_avatar_' + id).attr('src', response);
    });
  };

  $('.winners td.column-name a').editable(ajaxurl, {
    indicator: 'Saving winner name...',
    tooltip: 'Click here to change',
    placeholder: 'Click here to change',
    width: 'none',
    height: 'none',
    type: 'text',
    onblur: 'submit',
    submitdata: {
     action: 'ks_save_giveaways_winner_name'
    }
  });

  $('.winners td.column-avatar').click('img', function(e) {
    var id = $(e.target).attr('id').replace('winner_avatar_', '');
    var frame = wp.media({
      title: 'Choose winner avatar',
      multiple: false,
      library: {
        type: 'image'
      },
      button: {
        text: 'Choose',
        items: {
          remove: {
            text: 'Remove Avatar',
            click: function() {
              saveGiveawayWinnerImage(id, '');
              frame.close();
            }
          }
        }
      },
      frame: 'select'
    });
    frame.on('select', function() {
      var attachment = frame.state().get('selection').first().toJSON();

      saveGiveawayWinnerImage(id, attachment.url);
    });

    frame.open();
  });
});
</script>