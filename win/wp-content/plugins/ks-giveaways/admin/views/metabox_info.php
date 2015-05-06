<table class="form-table">
  <tr valign="top">
    <th scope="row">
      <label>Description</label>
    </th>
    <td>
      <?php wp_editor(get_post_meta($post->ID, '_contest_description', true), 'contest_description', array('wpautop' => true, 'media_buttons' => false, 'textarea_rows' => 5)); ?>
      <p class="description">Enter a brief but meaningful description of your giveaway and/or prize.  We suggest approximately 2-3 paragraphs.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Starts</label>
    </th>
    <td>
      <input type="text" name="date_start" class="date-text" data-value="<?php echo gmdate('Y-m-d', (KS_Helper::get_date_start($post->ID) ? KS_Helper::get_date_start($post->ID) : strtotime('+1 day'))+(get_option('gmt_offset')*3600)) ?>" />
      @
      <input type="text" name="time_start" class="time-text" data-value="<?php echo gmdate('H:i', (int) get_post_meta($post->ID, '_date_start', true)+(get_option('gmt_offset')*3600)) ?>" />
      <p class="description">Date &amp; time the giveaway starts on (<?php echo KS_Helper::get_timezone_abbr(get_option('gmt_offset')) ?>).</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Ends</label>
    </th>
    <td>
      <input type="text" name="date_end" class="date-text" data-value="<?php echo gmdate('Y-m-d', (KS_Helper::get_date_end($post->ID) ? KS_Helper::get_date_end($post->ID) : strtotime('+1 weeks'))+(get_option('gmt_offset')*3600)) ?>" />
      @
      <input type="text" name="time_end" class="time-text" data-value="<?php echo gmdate('H:i', (int) get_post_meta($post->ID, '_date_end', true)+(get_option('gmt_offset')*3600)) ?>" />
      <p class="description">Date &amp; time the giveaway ends on (<?php echo KS_Helper::get_timezone_abbr(get_option('gmt_offset')) ?>).</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Awarded</label>
    </th>
    <td>
      <input type="text" name="date_awarded" class="date-text" data-value="<?php echo gmdate('Y-m-d', (KS_Helper::get_date_awarded($post->ID) ? KS_Helper::get_date_awarded($post->ID) : strtotime('+2 weeks'))+(get_option('gmt_offset')*3600)) ?>" />
      @
      <input type="text" name="time_awarded" class="time-text" data-value="<?php echo gmdate('H:i', (int) get_post_meta($post->ID, '_date_awarded', true)+(get_option('gmt_offset')*3600)) ?>" />
      <p class="description">Date &amp; time the giveaway prize will be awarded on (<?php echo KS_Helper::get_timezone_abbr(get_option('gmt_offset')) ?>).</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Rules</label>
    </th>
    <td>
      <?php wp_editor(get_post_meta($post->ID, '_contest_rules', true) ? get_post_meta($post->ID, '_contest_rules', true) : $default_rules, 'contest_rules', array('wpautop' => true, 'media_buttons' => false, 'textarea_rows' => 5)); ?>
      <p class="description">The default rules are designed to serve as a foundation for your own rules.  Please click the go full screen icon and be sure to read through and modify the rules to suit your own needs and laws.  We cannot be held responsible if you fail to do so.  Be sure to also <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=settings') ?>">fill in your address details</a> or remove the text relating to postal correspondence.  <?php echo $shortcodes ?></p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Entries Per Friend</label>
    </th>
    <td>
      <input type="number" name="entries_per_friend" placeholder="3" class="regular-text" value="<?php echo get_post_meta($post->ID, '_entries_per_friend', true) ?>" />
      <p class="description">Number of entries to give for each friend referred.</p>
    </td>
  </tr>
</table>

<script type="text/javascript">
jQuery(document).ready(function($) {
  $('input[name="date_start"], input[name="date_end"], input[name="date_awarded"]').pickadate({
    formatSubmit: 'yyyy-mm-dd',
    hiddenName: true
  });
  $('input[name="time_start"], input[name="time_end"], input[name="time_awarded"]').pickatime({
    formatSubmit: 'HH:i',
    hiddenName: true,
    interval: 15
  });

  preview_ga = function()
  {
    var w = $(window).width() - 80;
    var h = $(window).height() - 80;
    tb_show('Preview: My contest yay hehe!', 'http://support.wordpress.dev/giveaways/my-contest/?TB_iframe=true&width=' + w + '&height=' + h);
  };

  $('.form-table label').each(function() {
    var p = $('<a href="javascript:void(0);" class="preview-giveaway" title="Preview Giveaway"></a>');
    $(this).append(p);
    p.hide();
  });

  $('.form-table tbody tr').hover(function() {
    $(this).find('label a').fadeIn();
  }, function() {
    $(this).find('label a').hide();
  });

  $('.form-table label a').click(function() {
    $('#post-preview').click();
  });
});
</script>
