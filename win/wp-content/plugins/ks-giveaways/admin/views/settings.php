<div class="wrap">
  <h2><?php _e('KingSumo Giveaways'); ?></h2>

  <h2 class="nav-tab-wrapper">
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=general') ?>" class="nav-tab<?php $active_tab == 'general' and print ' nav-tab-active' ?>">General</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=email') ?>" class="nav-tab<?php $active_tab == 'email' and print ' nav-tab-active' ?>">Emails</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=settings') ?>" class="nav-tab<?php $active_tab == 'settings' and print ' nav-tab-active' ?>">Settings</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=services') ?>" class="nav-tab<?php $active_tab == 'services' and print ' nav-tab-active' ?>">Services</a>
    <a href="<?php echo admin_url('options-general.php?page=ks-giveaways-options&tab=advanced') ?>" class="nav-tab<?php $active_tab == 'advanced' and print ' nav-tab-active' ?>">Advanced</a>
  </h2>

  <form method="post" action="options.php">
    <input type="hidden" name="tab" value="<?php echo $active_tab ?>" />

    <?php settings_fields('ks_giveaways_options'); ?>
    <?php do_settings_sections('ks-giveaways-options') ?>

    <?php submit_button(); ?>
  </form>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

  $('input[name="ks_giveaways_license_key"]').on('keyup', function() {
    $('#ks-license-container').hide();
  });

  $('#ks-license-container').on('click', '.ks-giveaways-activate', function(e) {
    e.preventDefault();
    e.stopPropagation();

    $('#ks-license-container button').prop('disabled', 'disabled');

    $.post(
      ajaxurl,
      {
        action: 'ks_activate_giveaways_license'
      },
      function(response) {
        $('#ks-license-container').html(response);
        $('input[name="ks_giveaways_license_key"]').prop('readonly', 'readonly');
      }
    );
  });

  $('#ks-license-container').on('click', '.ks-giveaways-deactivate', function(e) {
    e.preventDefault();
    e.stopPropagation();

    if (!confirm('Are you sure you want to deactivate your license key?')) {
      return;
    }

    $('#ks-license-container button').prop('disabled', 'disabled');

    $.post(
      ajaxurl,
      {
        action: 'ks_deactivate_giveaways_license'
      },
      function(response) {
        $('#ks-license-container').html(response);
        $('input[name="ks_giveaways_license_key"]').prop('readonly', null);
      }
    );
  });

  $('#ks_giveaways_aweber_auth').click(function(e) {
    e.preventDefault();

    var url = $(this).attr('href');
    window.open(url, 'ks-giveaways-aweber', 'width=800,height=500');
  });

});
</script>