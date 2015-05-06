<div class="row text-center">
  <div class="small-12 columns">
    <h3 class="timer">
      Giveaway Ended
    </h3>
  </div>
</div>

<div class="row contest-winners">
  <?php foreach (ks_giveaways_get_winners() as $winner): ?>
  <div class="medium-6 columns">
    <h5>
      <?php if (in_array($winner['status'], array('confirmed','notified')) && $winner['winner_avatar']): ?>
        <img src="<?php echo $winner['winner_avatar'] ?>" alt="" />
      <?php else: ?>
        <img src="<?php echo ks_giveaways_assets_url() ?>/images/user-avatar.jpg" alt="" />
      <?php endif ?>

      <?php if (in_array($winner['status'], array('confirmed','notified'))): ?>
        <?php echo $winner['winner_name'] ? $winner['winner_name'] : 'Anonymous' ?>
      <?php elseif ($winner['status'] == 'unconfirmed'): ?>
        Verifying Winner
      <?php elseif ($winner['status'] == 'pending'): ?>
        Pending
      <?php endif ?>
    </h5>
  </div>
  <?php endforeach ?>
</div>
