# GSUI
This library is meant to help build administration panel interfaces as strings with
relative ease.

### SDK_VERSION
### __construct
### header
```php
echo $ui->header('Admin Page Title', array(
  // Navigation links
  array('label' => 'Page 1', 'href' => 'page1'),
  array('label' => 'Page 2', 'href' => 'page2'),
  array('label' => 'Page 3', 'href' => 'page3'),
));
```
### title
```php
echo $ui->title('Admin Panel Title');
```

### quicknav
```php
echo $ui->quicknav(array(
  array('label' => 'Page 1', 'href' => 'page1'),
  array('label' => 'Page 2', 'href' => 'page2'),
  array('label' => 'Page 3', 'href' => 'page3'),
));
```

### quicktab
```php
echo $ui->quicktab('tab-container', array(
  // Tabs
  array('label' => 'Tab 1'),
  array('label' => 'Tab 2'),
  array('label' => 'Tab 3'),
), array(
  // Content
  'Page 1',
  'Page 2',
  'Page 3',
));
```

### parag
```php
echo $ui->parag('A paragraph');
```
### section
```php
echo $ui->section(
  // Left section (can be given as an array)
  'Left section content',
  // Right section (can be given as an array)
  'Right section content',
);
```
### leftsec
### rightsec
### metawindow
```php
echo $ui->metawindow('Left content', 'Right content');
```

### table
```php
// Normal table
echo $ui->table(array(
  // Header
  'header' => array('Items', 'Year'),
  // Rows
  'rows' => array(
    array('Item 1', '2014'),
    array('Item 2', '2015'),
    array('Item 3', '2013')
  ),
));

// Editable table
echo $ui->table(array(
  'type' => 'edit',
  // Header
  'header' => array('Items', 'Year'),
  // Rows
  'rows' => array(
    array('Item 1', '2014'),
    array('Item 2', '2015'),
    array('Item 3', '2013')
  ),
));
```

### anchor
```php
// Cancel button
echo $ui->anchor('cancel', array(
  'label' => Cancel',
  'href' => 'http://.../',
));
```

### form
```php
echo $ui->form(array(
  'method' => 'post',
  'action' => 'path/to/script.php',
  'content' => 'Form content',
));
```

### input
```php
// Text field
echo $ui->input(array(
  'label' => 'Field',
  'name' => 'field',
  'value' => 'Initial value',
));

// Title text field
echo $ui->input(array(
  'type' => 'title',
  'name' => 'title',
  'value' => 'Your Title Here',
));

// Checkbox
echo $ui->input(array(
  'label' => 'Enable HTML Editor?',
  'type' => 'check',
  'name' => 'enableeditor',
  'value' => true,
));
```

### dropdown
```php
echo $ui->dropdown(array(
  // Container
  'name' => 'items',
), array(
  // Values
  array('value' => 'Item 1'),
  array('value' => 'Item 2'),
  array('value' => 'Item 3'),
));
```

### htmleditor
```php
echo $ui->htmleditor(array(
  'name' => 'content',
  'value' => 'Initial content for the editor',
  'config' => array(
    // CKEditor config
  ),
));
```

### codeeditor
```php
echo $ui->codeeditor(array(
  'name' => 'code',
  'value' => 'Your initial code',
  'config' => array(
    // CodeMirror config
  )
));
```

### submit
```php
echo $ui->submit(array(
  'name' => 'submit',
  'value' => 'Submit',
));
```

### submitline
### footer
### element
```php
echo $ui->element('div', array(
  'class' => 'class1 class2',
  'id' => 'yourdiv',
), 'Content');
```
