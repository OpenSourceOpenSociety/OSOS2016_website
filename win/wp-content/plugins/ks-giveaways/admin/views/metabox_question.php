<table class="form-table">
  <tr valign="top">
    <th scope="row">
      <label>Question</label>
    </th>
    <td>
      <input type="text" placeholder="Who is giving away this prize?" name="question" class="regular-text" value="<?php echo esc_attr(get_post_meta($post->ID, '_question', true)) ?>" />
      <p class="description">Enter a really easy question used to qualify people before entering.  You want people to be able to get it without too much thinking.  This helps increases the conversion rate.</p>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Wrong Answer #1</label>
    </th>
    <td>
      <input type="text" placeholder="Al Gore, I think?" name="wrong_answer1" class="regular-text" value="<?php echo esc_attr(get_post_meta($post->ID, '_wrong_answer1', true)) ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Wrong Answer #2</label>
    </th>
    <td>
      <input type="text" placeholder="Bill Clinton, maybe?" name="wrong_answer2" class="regular-text" value="<?php echo esc_attr(get_post_meta($post->ID, '_wrong_answer2', true)) ?>" />
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">
      <label>Right Answer</label>
    </th>
    <td>
      <input type="text" placeholder="<?php echo esc_attr(bloginfo('name')) ?>, duh!" name="right_answer" class="regular-text" value="<?php echo esc_attr(get_post_meta($post->ID, '_right_answer', true)) ?>" />
    </td>
  </tr>
</table>
