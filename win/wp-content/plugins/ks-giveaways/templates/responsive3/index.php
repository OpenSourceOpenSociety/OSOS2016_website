<?php
/**
 * Template Name:       Responsive 3
 */
?>
<!doctype html>
<html class="no-js" lang="" prefix="og: http://ogp.me/ns#">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php the_title() ?></title>

    <link rel="canonical" href="<?php echo esc_attr(get_permalink()) ?>" />

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr(get_the_title()) ?>">
    <meta property="og:description" content="<?php echo esc_attr(strip_tags(ks_giveaways_get_description())) ?>">
    <meta property="og:image" content="<?php echo esc_attr(ks_giveaways_get_prize_image_url()) ?>">
    <meta property="og:url" content="<?php echo esc_attr(get_permalink()) ?>">

<?php if (get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)): ?>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@<?php echo esc_attr(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)) ?>">
    <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()) ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(strip_tags(ks_giveaways_get_description())) ?>">
    <meta name="twitter:image" content="<?php echo esc_attr(ks_giveaways_get_prize_image_url()) ?>">
<?php endif ?>

    <link rel="stylesheet" href="<?php echo ks_giveaways_assets_url() . 'css/responsive3.css' ?>" />
  </head>
  <body>

    <div class="row">
        <div class="medium-5 small-12 left columns contest-images logo">
          <?php if (ks_giveaways_get_logo_image_url()): ?>
            <a href="<?php echo esc_url(home_url('/')) ?>"><img src="<?php echo ks_giveaways_get_logo_image_url() ?>" alt="" /></a>
          <?php endif ?>
        </div>
      <div class="medium-7 small-12 right columns contest">
        <div class="row">
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>

          <!-- Contest -->
          <div class="small-12 medium-10 columns text-center">

            <div class="row">
              <div class="small-12 columns">
                <h1 class="text-center"><?php the_title() ?></h1>
              </div>
            </div>
            <?php if (!ks_giveaways_has_contestant()): ?>
              <div class="row text-center">
                <div class="small-12 medium-6 columns">
                  <h4 class="value"><?php echo ks_giveaways_get_prize_value() ?> Value</h4>
                </div>
                <div class="small-12 medium-6 columns">
                  <h4 class="winners"><?php echo ks_giveaways_get_winner_count() ?> Winner<?php echo ks_giveaways_get_winner_count() == 1 ? '' : 's' ?></h4>
                </div>
              </div>
            <?php endif ?>

            <?php if (!ks_giveaways_has_started()): ?>
              <?php include 'not-started.php' ?>
            <?php elseif (ks_giveaways_has_ended()): ?>
              <?php include 'ended.php' ?>
            <?php elseif (ks_giveaways_has_started() && !ks_giveaways_has_ended()): ?>
              <?php include 'running.php' ?>
            <?php endif ?>

          </div>
          <!-- End Contest -->

          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>
        </div>
        <div class="row footer">
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>

          <!-- Rules -->
          <div class="small-12 medium-10 columns text-center">
            <?php include 'rules.php' ?>
          </div>
          <!-- End Rules -->
          <div class="show-for-medium-up medium-1 columns">&nbsp;</div>
        </div>
      </div>
      <div class="medium-5 small-12 left columns contest-images products">
        <?php if (ks_giveaways_has_image1()): ?>
            <?php if (ks_giveaways_has_image1_link()): ?>
                <a href="<?php echo ks_giveaways_get_image1_link() ?>">
            <?php endif ?>
            <img src="<?php echo ks_giveaways_get_image1_url() ?>" alt="" />
            <?php if (ks_giveaways_has_image1_link()): ?>
                </a>
            <?php endif ?>
        <?php endif ?>

          <?php if (ks_giveaways_has_image2()): ?>
              <?php if (ks_giveaways_has_image2_link()): ?>
                  <a href="<?php echo ks_giveaways_get_image2_link() ?>">
              <?php endif ?>
              <img src="<?php echo ks_giveaways_get_image2_url() ?>" alt="" />
              <?php if (ks_giveaways_has_image2_link()): ?>
                  </a>
              <?php endif ?>
          <?php endif ?>

          <?php if (ks_giveaways_has_image3()): ?>
              <?php if (ks_giveaways_has_image3_link()): ?>
                  <a href="<?php echo ks_giveaways_get_image3_link() ?>">
              <?php endif ?>
              <img src="<?php echo ks_giveaways_get_image3_url() ?>" alt="" />
              <?php if (ks_giveaways_has_image3_link()): ?>
                  </a>
              <?php endif ?>
          <?php endif ?>
      </div>
    </div>

    <div class="back">
      <div class="fullscreen background" style="background-image: url(<?php echo ks_giveaways_get_background_image_url() ?>)">
    </div>

    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/jquery.plugin.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/jquery.countdown.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/mailcheck.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/foundation.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo ks_giveaways_public_assets_url() . 'js/giveaways.js' ?>"></script>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>                </div>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=859406404089021&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <?php echo ks_giveaways_extra_footer() ?>
    <?php echo ks_giveaways_extra_contestant_footer() ?>

  </body>
</html>