<?php

try {
  $slug = $matches[0];
  $file = 'pages/' . $slug . '.json';

  $page = $utils->getfile($file);

  $_index->title = $page['title'];
  ?>

  <p>
    <a href="javascript:history.back()">Back</a>
  </p>
  <?php echo $page['content']; ?>

  <?php

} catch (Exception $error) {
  ?>
  <p><?php echo $plugin->i18n('PAGE_GET_ERR'); ?></p>
  <?php
}