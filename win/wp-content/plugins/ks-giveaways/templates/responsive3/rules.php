<div class="row text-left">
  <div class="small-6 columns">
    <h5 class="cal">
      <?php if (!ks_giveaways_has_started()): ?>
      Giveaway Starts<br />
      <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_start()) ?></em>
      <?php else: ?>
        <?php if (ks_giveaways_has_ended()): ?>
        Giveaway Ended<br />
        <?php else: ?>
        Giveaway Ends<br />
        <?php endif ?>
        <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_end()) ?></em>
      <?php endif ?>
    </h5>
  </div>
  <div class="small-6 columns">
    <h5 class="cal">
      Prizes Awarded<br />
      <em><?php echo ks_giveaways_get_date_text(ks_giveaways_get_date_awarded()) ?></em>
    </h5>
  </div>
  <div class="small-12 columns">
    <h5 class="rules">
      Enter sweepstakes and receive exclusive offers from <?php bloginfo('name') ?>. Unsubscribe anytime.
      <?php if (ks_giveaways_get_prize_brand()): ?>
      <?php echo ks_giveaways_get_prize_brand() ?> is not affiliated with the giveaway.
      <?php endif ?>
      <a href="javascript:void(0)" id="giveaways_toggle_rules">Read official rules.</a>
    </h5>
    <div class="full_rules" id="giveaways_full_rules">
      <?php echo ks_giveaways_get_rules() ?>
    </div>
  </div>
  <div class="small-12 columns text-center">
    <a href="http://kingsumo.com/apps/giveaways/?ref=<?php echo $_SERVER['HTTP_HOST'] ?>" class="powered-by" target="_blank">Powered by KingSumo Giveaways</a>
  </div>
</div>
