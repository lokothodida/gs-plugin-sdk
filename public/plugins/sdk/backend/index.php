<?php

// Building
if (!empty($_POST)) {
  include 'build.php';
}

$title = $ui->title($plugin->i18n('PLUGIN_SIDEBAR'));

$plugins = glob($plugin->path() . '/../*.php');

$rows = array();
$ignore = include 'ignore.php';

foreach ($plugins as $filename) {
  $name = basename($filename, ".php");
  if (in_array($name, $ignore)) continue;
  $rows[] = array(
    $name,
    $ui->input(array(
      'name' => $name,
      'placeholder' => $name . '/',
    ))
  );
}

$thead = array($plugin->i18n('PLUGIN_NAME'), $plugin->i18n('BUILD_DEST'));

$table = $ui->table(array(

), $thead, $rows);

$form = $ui->form(array(
  'method' => 'post',
  'content' => array(
    $table,
    $ui->submit(array(
      'value' => $plugin->i18n('BUILD'),
    ))
  )
));

$desc = $ui->parag($plugin->i18n('BUILD_DESC'));

echo $title;
echo $desc;
echo $form;
