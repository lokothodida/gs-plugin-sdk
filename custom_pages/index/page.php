<?php

// Display an individual page
// Get page slug from url
list ($slug) = $params;

// Get page
try {
  $file = $plugin->id() . $slug;
  $page = $utils->getFile($file);

  // Set the title
  $index->title($page['title']);

  // Display the page
  ?>
  <div class="page">
    <!--title-->
    <h3><?php echo $page['title']; ?></h3>

    <!--excerpt (30 words)-->
    <?php echo $utils->excerpt($page['content'], 30); ?>
  </div>
  <?
} catch (Exception $error) {
  // 404 error page
}

?>