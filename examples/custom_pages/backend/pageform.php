<?php

$header = $ui->header($plugin->i18n($title), array(
  // Quicknav link to 'view'
  array('label' => $plugin->i18n('BACK'), 'href' => $utils->adminurl())
));

$form = $ui->form(array(
  'method' => 'post',
  'action' => $action,
  'content' => array(
    // Page title
    $ui->input(array(
      'name' => 'title',
      'value' => $page['title'],
      'type' => 'title',
    )),
    $ui->input(array(
      'name' => 'credate',
      'value' => $page['credate'],
      'type' => 'hidden',
    )),
    $ui->input(array(
      'name' => 'slug',
      'value' => $page['slug'],
      'type' => 'hidden',
    )),
    // Page content
    
    $ui->htmleditor(array(
      'name' => 'content',
      'value' => $page['content'],
    )),
    
    $ui->submit(),
  ),
));

echo $header;
echo $form;