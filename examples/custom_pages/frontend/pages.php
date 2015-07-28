<?php

try {
  $pages = $utils->getfiles('pages/*.json');

  $_index->title = 'Pages';

  foreach ($pages as $file => $page) {
    $slug = basename($file, '.json');
    ?>
    
    <h3>
      <a href="<?php echo $utils->siteurl() . 'pages/' . $slug; ?>">
        <?php echo $page['title']; ?>
      </a>
    </h3>
    <?php
  }
} catch (Exception $error) {
  ?>
  <p><?php echo $plugin->i18n('PAGE_GET_ERR'); ?></p>
  <?php
}