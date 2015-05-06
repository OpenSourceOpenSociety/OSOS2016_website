<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';

function ks_giveaways_get_winner_count()
{
  return KS_Helper::get_winner_count();
}

function ks_giveaways_get_winners()
{
  return KS_Helper::get_winners();
}

function ks_giveaways_get_entries_per_friend()
{
  return KS_Helper::get_entries_per_friend();
}

function ks_giveaways_get_lucky_url()
{
  return KS_Helper::get_lucky_url();
}

function ks_giveaways_get_my_entries()
{
  return KS_Helper::get_my_entries();
}

function ks_giveaways_get_total_entries()
{
  return KS_Helper::get_total_entries();
}

function ks_giveaways_get_total_winners()
{
  return KS_Helper::get_total_winners();
}

function ks_giveaways_get_description()
{
  return KS_Helper::get_description();
}

function ks_giveaways_get_rules()
{
  $rules = KS_Helper::replace_shortcodes(KS_Helper::get_rules());

  return $rules;
}

function ks_giveaways_get_timezone_city($offset = null)
{
  return KS_Helper::get_timezone_city($offset);
}

function ks_giveaways_get_timezone_abbr($offset = null)
{
  return KS_Helper::get_timezone_abbr($offset);
}

function ks_giveaways_has_started()
{
  return KS_Helper::has_started(get_post());
}

function ks_giveaways_has_ended()
{
  return KS_Helper::has_ended(get_post());
}

function ks_giveaways_get_date_start()
{
  return KS_Helper::get_date_start(get_post());
}

function ks_giveaways_get_date_text($epoch)
{
  $offset = get_option('gmt_offset');

  return sprintf('%s %s', gmdate(get_option('date_format') . ' ' . get_option('time_format'), $epoch+($offset*3600)), KS_Helper::get_timezone_abbr($offset));
}

function ks_giveaways_get_date_end()
{
  return KS_Helper::get_date_end(get_post());
}

function ks_giveaways_get_date_awarded()
{
  return KS_Helper::get_date_awarded(get_post());
}

function ks_giveaways_get_image1_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_image_1', true);
}

function ks_giveaways_has_image1()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_image_1', true) != '');
}

function ks_giveaways_get_image1_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_1_link', true);
}

function ks_giveaways_has_image1_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_1_link', true) != '');
}

function ks_giveaways_get_image2_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_image_2', true);
}

function ks_giveaways_has_image2()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_image_2', true) != '');
}

function ks_giveaways_get_image2_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_2_link', true);
}

function ks_giveaways_has_image2_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_2_link', true) != '');
}

function ks_giveaways_get_image3_url()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_3', true);
}

function ks_giveaways_has_image3()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_3', true) != '');
}

function ks_giveaways_get_image3_link()
{
    $post = get_post();

    return get_post_meta($post->ID, '_image_3_link', true);
}

function ks_giveaways_has_image3_link()
{
    $post = get_post();

    return (get_post_meta($post->ID, '_image_3_link', true) != '');
}

function ks_giveaways_get_background_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_background_image', true);
}

function ks_giveaways_get_prize_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_prize_image', true);
}

function ks_giveaways_get_prize_value()
{
  return KS_Helper::get_prize_value();
}

function ks_giveaways_get_prize_name()
{
  return KS_Helper::get_prize_name();
}

function ks_giveaways_get_prize_brand()
{
  return KS_Helper::get_prize_brand();
}

function ks_giveaways_get_share_message()
{
  return get_the_title();
}

function ks_giveaways_get_logo_image_url()
{
  $post = get_post();

  return get_post_meta($post->ID, '_logo_image', true);
}

function ks_giveaways_cookie_email()
{
  return isset($_COOKIE[KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS]) ? $_COOKIE[KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS] : '';
}

function ks_giveaways_has_background_image()
{
  $post = get_post();

  return (get_post_meta($post->ID, '_background_image', true) != '');
}

function ks_giveaways_question()
{
  $question = KS_Helper::get_question();
  $wrong_answer1 = KS_Helper::get_wrong_answer1();
  $wrong_answer2 = KS_Helper::get_wrong_answer2();
  $right_answer = KS_Helper::get_right_answer();

  $answers = array();
  $answers[] = array('value' => 'wrong', 'text' => $wrong_answer1);
  $answers[] = array('value' => 'wrong', 'text' => $wrong_answer2);
  $answers[] = array('value' => 'right', 'text' => $right_answer);

  $ret = sprintf('<label for="giveaways_answer">%s</label>', $question);
  $ret .= '<select id="giveaways_answer">';
  $ret .= '<option>-- Select your answer --</option>';
  foreach ($answers as $answer) {
    $ret .= sprintf('<option value="%s">%s</option>', $answer['value'], $answer['text']);
  }
  $ret .= '</select>';

  return $ret;
}

function ks_giveaways_has_contestant()
{
  if (!empty($GLOBALS['ks_giveaways_contestant'])) {
    return true;
  }

  return false;
}

function ks_giveaways_is_confirmed_contestant()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return false;
  }

  return ($GLOBALS['ks_giveaways_contestant']->status == 'confirmed');
}

function ks_giveaways_assets_url()
{
   $content_dir = trailingslashit(WP_CONTENT_DIR);

   $template_file = KS_Helper::get_template_file();

   // content directory theme
   $dir = str_replace($content_dir, '', $template_file);
   $dir = dirname($dir);
   $url = trailingslashit(content_url($dir));

   return trailingslashit($url . 'assets');
}

function ks_giveaways_public_assets_url()
{
  $file = realpath(dirname(__FILE__));
  $url = trailingslashit(trailingslashit(plugin_dir_url($file)) . 'assets');
  return $url;
}

function ks_giveaways_extra_footer()
{
  return get_option(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER);
}

function ks_giveaways_extra_contestant_footer()
{
  if (empty($GLOBALS['ks_giveaways_contestant'])) {
    return '';
  }

  return get_option(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER);
}