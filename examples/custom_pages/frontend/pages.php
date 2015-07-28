<?php

try {
  $pages = $utils->getfiles('pages/*.json');

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
}