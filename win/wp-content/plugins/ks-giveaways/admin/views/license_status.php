<?php if (get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) == 'valid'): ?>

<button class="button ks-giveaways-deactivate">Deactivate</button>
<div style="font-size: 85%">
<?php if (isset($errors) && !empty($errors)): ?>
<ul>
<li><?php echo implode('</li><li>', $errors) ?></li>
</ul>
<?php endif ?>
<span style="color:green">Your license key is currently active</span>
</div>

<?php else: ?>

<button class="button ks-giveaways-activate">Activate</button>
<div style="font-size: 85%">
<?php if (isset($errors) && !empty($errors)): ?>
<ul>
<li><?php echo implode('</li><li>', $errors) ?></li>
</ul>
<?php endif ?>
<span style="color:red">Your license key is currently inactive</span>
</div>

<?php endif ?>