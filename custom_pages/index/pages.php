<?php

// List of custom pages
// Get the pages
try {
  $pages = $utils->getFiles($plugin->id());

  // Display each page
  ?>

  <div class="pages">
  <?php foreach ($pages as $page) : ?>
    <div class="page">
      <!--title-->
      <h3><?php echo $page['title']; ?></h3>

      <!--excerpt (30 words)-->
      <?php echo $utils->excerpt($page['content'], 30); ?>
    </div>
  <?php endforeach; s?>
  </div>
  <?php
} catch (Exception $error) {
  // 404 error page
}