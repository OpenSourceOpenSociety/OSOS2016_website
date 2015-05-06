<div class="wrap">
  <h2><?php the_title() ?> &mdash; Contestants <a href="<?php echo admin_url('edit.php?post_type='.KS_GIVEAWAYS_POST_TYPE) ?>" class="add-new-h2">Back</a></h2>

  <form action="" method="post">
  <?php echo $list_table->display() ?>
  </form>

</div>
