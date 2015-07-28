<?php

try {
  $slug = $matches[0];
  $file = 'pages/' . $slug . '.json';

  $page = $utils->getfile($file);

  $_index->title = $page['title'];
  ?>
  
  <?php echo $page['content']; ?>

  <?php

} catch (Exception $error) {
}