<?php

// Administration panel
// Data initialization
try {
  $utils->mkdir('somedir');
  $utils->mkfile('somedir/file', array(
    'title' => 'Test',
    'content' => 'Whatever',
  ));
  echo $ui->success('Yay!');
} catch(Exception $error) {
  if ($error->getMessage() == $utils::EXCEPTION_MKDIR) {
    $msg = 'MKDIR_FAIL';
  } else {
    $msg = 'MKFILE_FAIL';
  }

  echo $ui->error($i18n($msg));
}

// Displaying the admin panel
echo $ui->title('Your admin panel');
echo $ui->para('Some paragraph');

$route('test', 'admin/test.php');
$route('other', 'admin/other.php');