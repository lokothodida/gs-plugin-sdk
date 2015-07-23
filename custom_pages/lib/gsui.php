<?php

if (!class_exists('GSUI')) { 

class GSUI {
  // == PROPERTIES ==
  protected $inputProperties = array('name', 'value', 'placeholder', 'disabled', 'hidden', 'type');
  
  // == PUBLIC METHODS ==
  // == STATIC UI ==
  // Title
  public function title($title, $float = false) {
    $floated = $float ? ' class="floated"' : null;
    return '<h3' . $floated . '>' . $title . '</h3>';
  }

  // Quick/editnav
  public function quickNav(array $nav) {
    $quicknav = '<div class="edit-nav clearfix">';
    $links = '';

    foreach ($nav as $link) {
      $links = '<a href="' . $link['url'] . '">' . $link['label'] . '</a>' . $links;
    }
    
    return $quicknav .= $links . '</div>';
  }

  // Left section
  public function leftSection($elements) {
    return $this->lrSection('leftsec', $elements);
  }
  
  // Right section
  public function rightSection($elements) {
    return $this->lrSection('rightsec', $elements);
  }

  // Both sections
  public function bothSections($leftElements, $rightElements) {
    $html = '';
    $html .= $this->leftSection($leftElements);
    $html .= $this->rightSection($rightElements);
    $html .= $this->clear();
    return $html;
  }
  
  // Clear
  public function clear() {
    return '<div class="clear"></div>';
  }

  // Table
  public function table($header, $rows) {
    $table = '<table class="edittable">';

    // Header
    $thead = '<thead><tr>';
    foreach ($header as $column) {
      $thead .= '<th>' . $column . '</th>';
    }
    $thead .= '</tr></thead>';
    $table .= $thead;

    // rows
    $tbody = '<tbody>';
    foreach ($rows as $row) {
      $tbody .= '<tr>';
      foreach ($header as $i => $column) {
        $tbody .= '<td>' . $row[$i] . '</td>';
      }
      $tbody .= '</tr>';
    }
    $tbody .= '</tbody>';
    
    $table .= $tbody . '</table>';
    
    return $table;
  }
  
  // Success message
  public function success($msg, $undo = null) {
    return $this->errorMsg('success', $msg, $undo);
  }
  
  // Error message
  public function error($msg, $undo = null) {
    return $this->errorMsg('error', $msg, $undo);
  }
  
  // == FORM ELEMENTS ==
  // Text input
  public function input(array $properties) {
    // Build the input
    $input = '<input class="text" ';

    foreach ($this->inputProperties as $property) {
      if (isset($properties[$property])) {
        $input .= $property . '="' . $properties[$property] . '"';
      }
    }

    $input .= '/>';

    // Build a label
    $label = '';
    if (isset($properties['label'])) {
      $label = '<label>' . $properties['label'] . '</label>';
    }
    
    // Wrap up in a paragraph
    $html = $label . $input;
    if (!isset($properties['type']) || $properties['type'] != 'hidden') {
      $html = '<p>' . $html . '</p>';
    }
    
    return $html;
  }
  
  // Rich text area
  public function richTextarea() {
    
  }

  // Submit line
  public function submitLine($submit = array(), $or = null, $cancel = array()) {
    $submitLine = '<p id="submit_line">';

    $submitLine .= $this->submit($submit);
    $submitLine .= $or ? $or : '&nbsp;&nbsp;' . i18n_r('OR') . '&nbsp;&nbsp;';
    $submitLine .= $this->cancel($cancel);
    
    return $submitLine;
  }

  // Submit
  public function submit(array $properties = array()) {
    if (!isset($properties['label'])) {
      $properties['label'] = i18n_r('BTN_SAVECHANGES');
    }

    if (!isset($properties['name'])) {
      $properties['name'] = 'submitted';
    }

    $input = '<input class="submit" type="submit" name="' . $properties['name'] .'" value="' . $properties['label'] . '">';
    $html = '<span>' . $input . '</span>';

    return $html;
  }

  // Cancel
  public function cancel(array $properties = array()) {
    if (!isset($properties['label'])) {
      $properties['label'] = i18n_r('CANCEL');
    }

    if (!isset($properties['url'])) {
      $properties['url'] = '';
    }

    $a = '<a class="cancel" href="' . $properties['url'] .'">' . $properties['label'] . '</a>';

    return $a;
  }

  // Redirect url
  public function redirect($url) {
  }
  
  // Rename url
  public function renameUrl($url) {
    return '
      <script>
        var title = document.getElementsByTagName("title")[0].innerHTML;
        window.history.pushState({}, title, ' . json_encode($url) . ');
      </script>
    ';
  }
  
  // Confirmation dialog
  public function confirm() {
  }
  
  // PROTECTED METHODS
  protected function lrSection($direction, $elements) {
    if (!is_array($elements)) {
      $elements = array($elements);
    }

    $sec = '<div class="' . $direction . '">';
    
    foreach ($elements as $element) {
      $sec .= $element;
    }
    
    $sec .= '</div>';

    return $sec;
  }

  // Error messages
  protected function errorMsg($status, $msg, $undo = null) {
    $isSuccess = $status == 'success';
    return '
      <script type="text/javascript">
        $(function() {
          $("div.bodycontent").before(\'<div class="' . ($isSuccess ? 'updated' : 'error') . '" style="display:block;">\'+
          ' . json_encode($msg) . ' + \'</div>\');
          $(".updated, .error").fadeOut(500).fadeIn(500);
        });
      </script>';
  }
}

}