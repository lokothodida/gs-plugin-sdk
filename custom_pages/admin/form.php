<?php

// Form for creating/editing a page
// Encapsulated so that the same code is used for both actions
// Assumes there is a $page array with data about the currnt page
// The fields are empty if it is a new page being created 
$fields = array();

// Title field
$fields['title'] = $ui->input(array(
  'name' => 'title',
  'value' => $page['title'],
));

// Content field
$fields['content'] = $ui->richTextarea(array(
  'name' => 'content',
  'value' => $page['content'],
));

// Submit (and cancel) button
$fields['submit'] = $ui->submit($plugin->i18n('BTN_SAVECHANGES'), $plugin->i18n('CANCEL'));

// Wrap up the fields and button in a form
$form = $ui->form(array('method' => $post, 'content' => $fields));