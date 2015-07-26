<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSUI')) return;

class GSUI {
  // == PROPERTIES ==
  protected $defaults = array(
    'ckeditor' => array(),
    'codemirror' => array()
  );

  // == PUBLIC METHODS ==
  // Constructor
  public function __construct($params = array()) {
    // TODO: Fix ckeditor defaults (lang and toolbar)
    $this->defaults['ckeditor'] = array(
      'skin' => 'getsimple',
      'forcePasteAsPlainText' => true,
      'language' => 'en',
      'defaultLanguage' => 'en',
      'uiColor' => '#FFFFFF',
      'height' => '500px',
      'baseHref' => $GLOBALS['SITEURL'],
      'tabSpaces' => 10,
      'filebrowserBrowseUrl' => 'filebrowser.php?type=all',
      'filebrowserImageBrowseUrl' => 'filebrowser.php?type=images',
      'filebrowserWindowWidth' => '730',
      'filebrowserWindowHeight' => '500',
      'toolbar' => 'basic',
    );

    $this->defaults['codemirror'] = array(
      'lineNumbers' => true,
      'matchBrackets' => true,
      'indentUnit' => 4,
      'indentWithTabs' => true,
      'enterMode' => 'keep',
      'mode' => 'application/x-httpd-php',
      'tabMode' => "shift",
      'theme' => 'default',
    );
  }

  // Header (title + quicknav)
  public function header($title, $quicknav) {
    return $this->title($title, true) . "\n" . $this->quicknav($quicknav);
  }

  // Title
  public function title($content, $floated = false) {
    return $this->element('h3', array(
      'class' => $floated ? 'floated' : null,
    ), $content);
  }

  // Paragraph
  public function parag($content) {
    return $this->element('p', array(), $content);
  }

  // Quick navigation
  public function quicknav($nav) {
    $links = array();
    $count = count($nav);
    $default = array(
      'href' => '',
      'class' => '',
    );

    // Loop through $nav in reverse order
    for ($i = $count-1; $i >= 0; $i--) {
      $link = array_merge($default, $nav[$i]);
      $label = $link['label'];
      unset($link['label']);
      $links[] = $this->element('a', $link, $label);
    }
    
    return $this->element('div', array(
      'class' => 'edit-nav clearfix',
    ), $links);
  }

  // Quick tabs
  public function quicktab($attrs, $nav, $contents) {
    if (!is_array($attrs)) {
      $attrs = array('id' => $attrs);
    }

    $quicknav   = $this->quicknav($nav);
    $containers = array();

    foreach ($contents as $content) {
      $containers[] = $this->element('div', array('class' => 'page'), $content);
    }

    $containers = implode("\n", $containers);
    $script = '
      <script>
        $(function() {
          var $tabcontainer = $(' . json_encode('#' . $attrs['id']) . ');
          var $tabs = $tabcontainer.find(".edit-nav a");
          var $pages = $tabcontainer.find(".page");
          var totalPages = $pages.length;
          
          // Hide all pages (except the first)
          $pages.slice(1).hide();

          // Bind click event to quicknav
          $tabs.each(function(i, tab) {
            var $tab = $(tab);
            $tab.click(function() {
              $tabs.removeClass("current");
              $tab.addClass("current");
              $pages.hide();
              var idx = (totalPages - 1) - i;
              $($pages[idx]).show();
              return false;
            });
          });
        });
      </script>
    ';

    $wrapper = $this->element('div', $attrs, array($quicknav, $containers, $script));
    return $wrapper;
  }

  // Section
  public function section($left, $right) {
    return implode("\n", array(
      $this->leftsec($left),
      $this->rightsec($right),
      $this->element('div', array('class' => 'clear')),
    ));
  }

  // Left section
  public function leftsec($content) {
    return $this->element('div', array('class' => 'leftsec'), $content);
  }

  // Right section
  public function rightsec($content) {
    return $this->element('div', array('class' => 'rightsec'), $content);
  }

  // Metawindow
  public function metawindow($left, $right) {
    return $this->element('div', array('id' => 'metadata_window'), array(
      $this->element('div', array('class' => 'leftopt'),  $left),
      $this->element('div', array('class' => 'rightopt'), $right),
      $this->element('div', array('class' => 'clear')),
    ));
  }

  // Table
  public function table($attrs, $header, $rows) {
    $heads = $this->tr('th', array(), $header);
    $thead = $this->element('thead', array(), $heads);

    // Format rows
    $body = array();
    foreach ($rows as $row) {
      if (is_array($row)) {
        $body[] = $this->tr('td', array(), $row);
      } else {
        $body[] = (string) $row;
      }
    }

    $tbody = $this->element('tbody', array(), $body);

    return $this->element('table', $attrs, array($thead, $tbody));
  }

  // Table row
  public function tr($type = 'td', $attrs, $cols) {
    $cells = array();

    foreach ($cols as $col) {
      $cells[] = $this->element($type, array(), $col);
    }

    return $this->element('tr', $attrs, $cells);
  }

  // Input
  public function input($params) {
    // Defaults
    $params = array_merge(array(
      'type' => 'text',
      'class' => '',
    ), $params);

    // Label
    if (isset($params['label'])) {
      $label = $this->element('label', array(), $params['label']);
      unset($params['label']);
    } else {
      $label = null;
    }

    // Type
    if ($params['type'] == 'title') {
      $params['class'] .= ' text title';
      unset($params['type']);
    } elseif($params['type'] == 'hidden') {
      // ...
    } elseif ($params['type'] == 'checkbox') {
      if (!empty($params['value'])) $params['checked'] = true;
    } else {
      $params['class'] .= ' text';
      unset($params['type']);
    }

    $field = $this->element('input', $params);

    return $this->parag(array($label, $field));
  }

  // Dropdown list
  public function dropdown($attrs, $values) {
    $options = array();

    $attrs = array_merge(array(
      'class' => 'text',
    ), $attrs);
    
    foreach ($values as $value) {
      if (!isset($value['label'])) {
        $value['label'] = $value['value'];
      }
      $label = $value['label'];
      unset($value['label']);
      $options[] = $this->element('option', $value, $label);
    }

    $select = $this->element('select', $attrs, $options);

    if (isset($attrs['label'])) {
      $label = $this->element('label', array(), $attrs['label']);
    } else {
      $label = '';
    }

    return $this->parag(array($label, $select));
  }

  // Textarea
  public function textarea($attrs, $content) {
    return $this->element('textarea', $attrs, $content);
  }

  // HTML Editor (CKEditor)
  public function htmleditor($params) {
    $params = array_merge(array(
      'name' => 'post-content',
      'config' => array(),
      'force' => true,
    ), $params);

    $params['config'] = array_merge($this->defaults['ckeditor'], $params['config']);

    $attrs = $params;
    if (!isset($attrs['id'])) {
      $attrs['id'] = $attrs['name'];
    }

    unset($attrs['config']);

    $textarea = $this->textarea($attrs, $params['value']);

    if ($params['force']) {
      $ckeditor = $this->script(array('src' => 'template/js/ckeditor/ckeditor.js'));
    } else {
      $ckeditor = null;
    }

    $script = $this->script('
      CKEDITOR.replace(' . json_encode($attrs['name']) . ', ' . json_encode($params['config']) .');
    ');

    return implode("\n", array($this->parag($textarea), $ckeditor, $script));
  }

  // Code Editor (CodeMirror)
  public function codeeditor($params) {
    $params = array_merge(array(
      'name' => 'code',
      'config' => array(),
      'force' => true,
    ), $params);

    $params['config'] = array_merge($this->defaults['codemirror'], $params['config']);

    $attrs = $params;
    if (!isset($attrs['id'])) {
      $attrs['id'] = $attrs['name'];
    }

    unset($attrs['config']);

    $textarea = $this->textarea($attrs, $params['value']);

    $script = $this->script('
      CodeMirror.fromTextArea(document.getElementById(' . json_encode($attrs['name']) . '), ' . json_encode($params['config']) . ');
    ');

    return implode("\n", array($this->parag($textarea), $script));
  }

  // Form
  public function form($params) {
    $params = array_merge(array(
      'method' => null,
      'action' => null,
      'content' => null,
    ), $params);

    $content = $params['content'];
    unset($params['content']);

    return $this->element('form', $params, $content);
  }

  // Script
  public function script($contents) {
    $defaults = array(
      'type' => 'text/javascript',
    );
    if (is_string($contents)) {
      $script = $this->element('script', $defaults, $contents);
    } else {
      $attrs = array_merge($defaults, $contents);
      $script = $this->element('script', $attrs);
    }

    return $script;
  }

  // HTML element
  public function element($tag, $attrs = array(), $content = ' ') {
    $element = '<' . $tag . ' ';

    foreach ($attrs as $attr => $val) {
      $element .= $attr . '="' . $val . '" ';
    }

    if (!$content) {
      $element .= ' />';
    } else {
      if (is_array($content)) {
        $content = implode("\n", $content);
      } else {
        $content = (string) $content;
      }
      $element .= '>' . $content . '</' . $tag . '>';
    }

    return $element;
  }
}
