<?php

// Building
if (!empty($_POST)) {
  include 'build.php';
}

$title = $ui->title($plugin->i18n('BUILD'));

$plugins = glob($plugin->path() . '/../*.php');

$rows = array();

foreach ($plugins as $filename) {
  $name = basename($filename, ".php");
  if ($name == $plugin->id()) continue;
  $rows[] = array(
    $name,
    $ui->input(array(
      'name' => 'plugin-' . $name,
      'placeholder' => $name . '/',
    ))
  );
}

$thead = array($plugin->i18n('PLUGIN_NAME'), $plugin->i18n('BUILD_DEST'));

$table = $ui->table(array(

), $thead, $rows);

$form = $ui->form(array(
  'method' => 'post',
  'content' => array($table, $ui->submit())
));

$desc = $ui->parag($plugin->i18n('BUILD_DESC'));

echo $title;
echo $desc;
echo $form;
