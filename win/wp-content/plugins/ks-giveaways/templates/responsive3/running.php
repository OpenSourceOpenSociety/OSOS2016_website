<div class="row text-center">
  <div class="small-12 columns">
    <h4 class="timer">
      <div id="countdown" data-until="<?php echo ks_giveaways_get_date_end() ?>"></div>
    </h4>
  </div>
</div>

<?php if (ks_giveaways_has_contestant()): ?>

<div class="row text-center">
  <div class="small-12 columns">
    <br>
    <h4>You have <strong><?php echo ks_giveaways_get_my_entries() ?> <?php if (ks_giveaways_is_confirmed_contestant()): ?>confirmed <?php endif ?><?php if (ks_giveaways_get_my_entries() > 1): ?>entries<?php else: ?>entry<?php endif ?></strong></h4>
  </div>
</div>

<div class="row text-center">
  <div class="small-12 columns">
    <h5>Get <strong><?php echo ks_giveaways_get_entries_per_friend() ?> more <?php if (ks_giveaways_get_entries_per_friend() > 1): ?>entries<?php else: ?>entry<?php endif ?></strong> for every friend you refer</h5>
  </div>
</div>

<div class="row text-left sharing">
  <div class="medium-6 small-12 columns">
    <span class="step">1</span> <a class="share-fb" title="Share it on Facebook" href="javascript:void(0)" onclick="ks_giveaways_fb('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>')">Facebook</a>
  </div>
  <div class="medium-6 small-12 columns">
    <span class="step">2</span> <a class="share-tw" title="Tweet it on Twitter" href="javascript:void(0)" onclick="ks_giveaways_tw('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>', '<?php echo esc_js(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)) ?>')">Twitter</a>
  </div>
  <div class="medium-6 small-12 columns">
    <span class="step">3</span> <a class="share-li" title="Share it on LinkedIn" href="javascript:void(0)" onclick="ks_giveaways_li('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>')">LinkedIn</a>
  </div>
  <div class="medium-6 small-12 columns">
    <span class="step">4</span> <a class="share-pi" title="Share it on Pinterest" href="javascript:void(0)" onclick="ks_giveaways_pi('<?php echo esc_js(ks_giveaways_get_lucky_url()) ?>', '<?php echo esc_js(ks_giveaways_get_share_message()) ?>', '<?php echo esc_js(ks_giveaways_get_prize_image_url()) ?>')">Pinterest</a>
  </div>
</div>
<div class="row text-left sharing">
  <div class="medium-6 small-12 columns">
    <span class="step">5</span> Share Lucky URL
  </div>
  <div class="medium-6 small-12 columns">
    <input type="text" value="<?php echo esc_attr(ks_giveaways_get_lucky_url()) ?>" onclick="this.select();" />
  </div>
</div>
<div class="row text-left sharing">
  <?php if (get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)): ?>
  <div class="medium-6 small-12 columns">
    <span class="step">6</span>
    <a href="https://twitter.com/<?php echo esc_attr(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)) ?>" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @<?php echo get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA) ?></a>
  </div>
  <?php endif ?>
  <?php if (get_option(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE)): ?>
  <div class="medium-6 small-12 columns">
    <span class="step">7</span>
    <div class="fb-like" data-href="<?php echo esc_attr(get_option(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE)) ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" data-width="90"></div>
  </div>
  <?php endif ?>
</div>

<?php else: ?>

<div class="row text-left">
  <div class="small-12 columns">
    <?php echo ks_giveaways_get_description() ?>
  </div>
</div>
<!-- Contest Question -->
<div class="row text-center contest-question">
  <div class="small-12 columns">
    <h4><span class="step">1</span> Answer correctly to qualify</h4>
  </div>
  <div class="small-1 columns">&nbsp;</div>
  <div class="small-10 columns">
    <?php echo ks_giveaways_question() ?>
    <small class="error" style="display:none;">Incorrect answer, try again!</small>
  </div>
  <div class="small-1 columns">&nbsp;</div>
</div>
<!-- End Contest Question -->

<!-- Contest Entry -->
<div class="row text-center contest-entry">
  <div class="small-12 columns">
    <h4><span class="step">2</span> Enter your email address</h4>
  </div>
  <div class="small-12 columns">
    <form id="giveaways_form" action="" method="post" data-abide>
      <?php wp_nonce_field('ks_giveaways_form', 'giveaways_nonce') ?>
      <?php if (isset($_REQUEST['lucky'])): ?>
      <input type="hidden" name="lucky" value="<?php echo $_REQUEST['lucky'] ?>" />
      <?php endif ?>
      <div class="row collapse">
        <div class="small-1 columns">&nbsp;</div>
        <div class="small-3 columns">
          <label class="prefix" for="giveaways_email">Email</label>
        </div>
        <div class="small-7 columns">
          <input type="email" required name="giveaways_email" id="giveaways_email" value="<?php echo esc_attr(ks_giveaways_cookie_email()) ?>" />
          <small class="error">An email address is required.</small>
        </div>
        <div class="small-1 columns">&nbsp;</div>
      </div>
      <div class="row">
        <div class="small-12 columns text-center">
          <div id="giveaways_email_hint" style="display:none"><p>Did you mean <a href="javascript:void(0)"></a>?</p></div>
        </div>
      </div>
      <?php if (get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY)): ?>
      <div class="row collapse">
        <div class="small-1 columns">&nbsp;</div>
        <div class="small-10 columns text-center">
          <div class="g-recaptcha" data-sitekey="<?php echo get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) ?>"></div>
        </div>
        <div class="small-1 columns">&nbsp;</div>
      </div>
      <?php endif ?>
      <div class="row">
        <div class="small-12 columns text-center">
          <button type="submit" class="button large radius">Enter</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- End Contest Entry -->

<?php endif ?>
