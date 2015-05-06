<table class="form-table">
  <tr valign="top">
    <th scope="row">
      <label>Prize</label>
    </th>
    <td>
      <input type="text" name="prize_name" value="<?php echo esc_attr(get_post_meta($post->ID, '_prize_name', true)) ?>" class="regular-text" placeholder="MacBook Air 13&quot;" />
      <p class="description">Name of prize.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Prize Brand</label>
    </th>
    <td>
      <input type="text" name="prize_brand" value="<?php echo esc_attr(get_post_meta($post->ID, '_prize_brand', true)) ?>" class="regular-text" placeholder="Apple" />
      <p class="description">Brand/manufacturer/supplier of prize.  This is required if you are not affiliated with the giveaway otherwise it may be omitted.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Image</label>
    </th>
    <td>
      <input type="hidden" name="prize_image" value="<?php echo esc_attr(get_post_meta($post->ID, '_prize_image', true)) ?>" />
      <img id="prize-image" src="<?php echo esc_attr(get_post_meta($post->ID, '_prize_image', true)) ?>" />
      <a class="button" id="prize-image-choose">Choose Image</a>
      <p class="description">Used as the default share image.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Value</label>
    </th>
    <td>
      <input type="text" name="prize_value" value="<?php echo esc_attr(get_post_meta($post->ID, '_prize_value', true)) ?>" class="regular-text" placeholder="$1,399" />
      <p class="description">Value of prize.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Winners</label>
    </th>
    <td>
      <input type="number" name="winner_count" min="1" value="<?php echo esc_attr(get_post_meta($post->ID, '_winner_count', true)) ?>" class="small-text" />
      <p class="description">Number of winners to draw.</p>
    </td>
  </tr>
</table>

<script type="text/javascript">
jQuery(document).ready(function($) {
  $('#prize-image-choose').click(function() {
    var frame = wp.media({
      title: 'Prize Image',
      multiple: false,
      library: {
        type: 'image'
      },
      button: {
        text: 'Choose'
      }
    });
    frame.on('select', function() {
      var attachment = frame.state().get('selection').first().toJSON();
      $('input[name="prize_image"]').val(attachment.url);
      $('#prize-image').attr('src', attachment.url);
    });
    frame.open();
  });
});
</script>
