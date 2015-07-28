<?php

try {
  if (!empty($_POST['slug'])) {
    $slug = $_POST['slug'];
  } else {
    $slug = $utils->slug($_POST['title']);
  }

  $filename = 'pages/' . $slug . '.json';

  $data = array(
    'title' => $_POST['title'],
    'credate' => $_POST['credate'],
    'content' => $_POST['content'],
  );

  if ($type == 'create') {
    $utils->mkfile($filename, $data);
  } else {
    $utils->putfile($filename, $data);
  }
  $msg = $plugin->i18n('PAGE_SAVE_SUCC');
  echo $ui->success($msg);
} catch (Exception $error) {
  $msg = $plugin->i18n('PAGE_SAVE_ERR');
  echo $ui->error($msg);
}