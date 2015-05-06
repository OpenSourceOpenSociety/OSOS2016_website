<table class="form-table">
  <tr valign="top">
    <th scope="row">
      <label>Template</label>
    </th>
    <td>
      <select id="template_file" name="template_file">
        <?php foreach ($templates as $template => $info): ?>
        <option value="<?php echo esc_attr($template) ?>"<?php ($template == get_post_meta($post->ID, '_template_file', true)) and print ' selected'?>><?php echo $info['TemplateName'] ?><?php isset($info['IsOverride']) && $info['IsOverride'] && print ' (O)' ?></option>
        <?php endforeach ?>
      </select>
    </td>
  </tr>
  <tr><th colspan="2"><hr /></th></tr> 
  <tr valign="top">
    <th scope="row">
      <label>Logo</label>
    </th>
    <td>
      <input type="hidden" name="logo_image" value="<?php echo esc_attr(get_post_meta($post->ID, '_logo_image', true)) ?>" />
      <img id="logo-image" src="<?php echo esc_attr(get_post_meta($post->ID, '_logo_image', true)) ?>" />
      <a class="button" id="logo-image-choose">Choose Image</a>
    </td>
  </tr>
  <tr><th colspan="2"><hr /></th></tr> 
  <tr valign="top">
    <th scope="row">
      <label>Background</label>
    </th>
    <td>
      <input type="hidden" name="background_image" value="<?php echo esc_attr(get_post_meta($post->ID, '_background_image', true)) ?>" />
      <img id="background-image" src="<?php echo esc_attr(get_post_meta($post->ID, '_background_image', true)) ?>" />
      <a class="button" id="background-image-choose">Choose Image</a>
    </td>
  </tr>
  <tr><th colspan="2"><hr /></th></tr> 
  <tr valign="top">
    <th scope="row">
      <label>Image 1</label>
    </th>
    <td>
      <input type="hidden" name="image1" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_1', true)) ?>" />
      <img id="image1" src="<?php echo esc_attr(get_post_meta($post->ID, '_image_1', true)) ?>" />
      <a class="button" id="image1-choose">Choose Image</a>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Link 1</label>
    </th>
    <td>
      <input type="url" name="image1_link" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_1_link', true)) ?>" style="width: 100%;" />
    </td>
  </tr>
  <tr><th colspan="2"><hr /></th></tr>
  <tr valign="top">
    <th scope="row">
      <label>Image 2</label>
    </th>
    <td>
      <input type="hidden" name="image2" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_2', true)) ?>" />
      <img id="image2" src="<?php echo esc_attr(get_post_meta($post->ID, '_image_2', true)) ?>" />
      <a class="button" id="image2-choose">Choose Image</a>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Link 2</label>
    </th>
    <td>
      <input type="url" name="image2_link" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_2_link', true)) ?>" style="width: 100%;" />
    </td>
  </tr>
  <tr><th colspan="2"><hr /></th></tr> 
  <tr valign="top">
    <th scope="row">
      <label>Image 3</label>
    </th>
    <td>
      <input type="hidden" name="image3" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_3', true)) ?>" />
      <img id="image3" src="<?php echo esc_attr(get_post_meta($post->ID, '_image_3', true)) ?>" />
      <a class="button" id="image3-choose">Choose Image</a>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Link 3</label>
    </th>
    <td>
      <input type="url" name="image3_link" value="<?php echo esc_attr(get_post_meta($post->ID, '_image_3_link', true)) ?>" style="width: 100%;" />
    </td>
  </tr>
</table>

<script type="text/javascript">
jQuery(document).ready(function($) {
<?php

  $images = array(
    array('button' => '#logo-image-choose', 'title' => 'Logo Image', 'el' => '#logo-image', 'input' => 'logo_image'),
    array('button' => '#background-image-choose', 'title' => 'Background Image', 'el' => '#background-image', 'input' => 'background_image'),
    array('button' => '#image1-choose', 'title' => 'Image 1', 'el' => '#image1', 'input' => 'image1'),
    array('button' => '#image2-choose', 'title' => 'Image 2', 'el' => '#image2', 'input' => 'image2'),
    array('button' => '#image3-choose', 'title' => 'Image 3', 'el' => '#image3', 'input' => 'image3')
  );

  foreach ($images as $img):
?>

  $('<?php echo $img['button'] ?>').click(function() {
    var frame = wp.media({
      title: '<?php echo $img['title'] ?>',
      multiple: false,
      library: {
        type: 'image'
      },
      button: {
        text: 'Choose',
        items: {
          remove: {
            text: 'Remove Image',
            click: function() {
              $('input[name="<?php echo $img['input'] ?>"]').val('');
              $('<?php echo $img['el'] ?>').attr('src', '').hide();
              frame.close();
            }
          }
        }
      },
      frame: 'select'
    });
    frame.on('select', function() {
      var attachment = frame.state().get('selection').first().toJSON();
      $('input[name="<?php echo $img['input'] ?>"]').val(attachment.url);
      $('<?php echo $img['el'] ?>').attr('src', attachment.url).show();
    });
    frame.open();
  });

  <?php endforeach ?>
});
</script>
