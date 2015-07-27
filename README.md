# \*EXPERIMENTAL\* GetSimple CMS Plugin SDK
The purpose of this project is to provide a library that is:

* **portable** *(easy to add to add/remove from a plugin)*
* **unified** *(has a consistent, intuitive and stable interface)*
* **backward/forward compatible** *(works with old and new GetSimple versions)*
* **well documented**

in order to simplify the typical problems that arise during [GetSimple plugin](http://get-simple.info/wiki/) development. These problems include (but are not limited to):

* Building data structures for your plugin
* Sanitizing user inputs and avoiding XSS vulnerabilities
* Remaking/copying methods that exist in the core
* Accessing site data such as site/current user settings
* Building UI elements consistent with administration panel between versions

# Getting Started
## From scratch
* Download the [Hello World](https://github.com/lokothodida/gs-hello-world/) plugin example
* Rename the `hello_world.php` file and `hello_world/` folder
* You now have a ready-built plugin with the SDK library included
* *NOTE: currently only the GSPlugin class is in the Hello World example. Download the other classes from here.*

## From an existing plugin
* Create a /lib/ directory in your plugin's folder
* Download the `gsutils.php`, `gsui.php` and `gsplugin.php` files from this repository and put them in the lib directory
* `include` those files before your plugin is registered
* You can now use these classes in your plugin

# TODO
* Full documentation for below methods

# Libraries
* [GSUtils](#gsutils)
* [GSUI](#gsui)
* [GSPlugin](#gsplugin)

## GSUtils
This library is meant to handle general utility-based problems in plugin development:

* Directory management
* File creation/manipulation
* String manipulation

### SDK_VERSION
### __construct
### mkdir
### rmdir
### mvdir
### copy
### mkfile
### putfile
### rmfile
### mvfile
### getfile
### getfiles
### fileexists
### iswritable
### print
### slug
### translit
### clean

## GSUI
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

## GSPlugin
This library is meant to ease plugin registration and registering the correct
hooks.

### SDK_VERSION
### __construct
Instantiate the plugin wrapper with data about the plugin.
```php
$plugin = new GSPlugin(array(
  'id'       => 'your_plugin',
  'version'  => '1.0',
  'author'   => 'You',
  'website'  => 'http://yourwebsite.com',
  'tab'      => 'plugin_page_tab',
  'lang'     => 'default_language',
));
```
### id
Get the plugin id (same as the one given in the constructor)
```php
echo 'The plugin id is ' . $plugin->id();
```
### author
Get the plugin author (same as one given in the constructor)
```php
echo 'The plugin author is ' . $plugin->author();
```

### version
Get the plugin version (same as one given in the constructor)
```php
echo 'The plugin version is ' . $plugin->version();
```

### tab
#### `tab()`
Get the plugin tab (same as one given in the constructor)
```php
echo 'The plugin tab is ' . $plugin->tab();
```

#### `tab($id, $label[, $action = null])`
Register a new top level tab for the plugin in the admin panel
```php
// Create new tab 'My Tab'
$plugin->tab('mytab', 'My Tab');

// Create 'My Other Tab' with the landing url
// load.php?id=your_plugin&myaction
$plugin->tab('myothertab', 'My Other Tab', 'myaction');
```

### sidebar
#### `sidebar($label[, $action, $visibility, $tab])`
Register a sidebar for the plugin. Defaults to the tab that you initialized the plugin with
```php
// Add a sidebar link
$plugin->sidebar('My Plugin Sidebar Link');
```

### hook
Register a plugin hook
#### `hook($name, $callback, $arguments)`

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_footer() {
  echo 'This will be output in the theme footer';
}

// ...

$plugin->hook('theme-footer', 'your_plugin_footer');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function footer() {
    echo 'This will be output in the theme footer';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->hook('theme-footer', array($yp, 'footer'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/footer.php
echo 'This will be output in the theme footer';
```

```php
// ...
$plugin->hook('theme-footer', 'frontend/footer.php');
```

### trigger
Trigger a plugin hook action. Use this to allow other plugin authors to run actions
when your plugin has done something.

```php
// After performing some action...

$plugin->trigger('your-hook-name');
```

### filter
Register a plugin filter

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_content($content) {
  $string = 'This will be prepended to the page content';
  return $string . $content;
}

$plugin->filter('content', 'your_plugin_content');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function content($content) {
    $string = 'This will be prepended to the page content';
    return $string . $content;
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->filter('content', array($yp, 'content'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/content.php
$content = $args[0];
$string = 'This will be prepended to the page content';
return $string . $content;
```

```php
// ...
$plugin->filter('content', 'frontend/content.php');
```

### script
Register a Javascript file to be loaded

##### `script($params)`

```php
// == PARAMETERS ==
// $params['id']      unique id for the script
// $params['src']     script source
// $params['baseurl'] base url for script; defaults to your plugin folder
// $params['version'] script version
// $params['where']   where to load: GSFRONT, GSBACK or GSBOTH
// $params['footer']  true to load script in footer
```

```php
// Loads your_plugin/js/script.js on the front and back end
$plugin->script(array(
  'id'    => 'your_plugin_script',
  'src'   => 'js/script.js',
  'where' => GSBOTH,
));
```

### style
Register a CSS sheet to be loaded

##### `style($params)`

```php
// == PARAMETERS ==
// $params['id']      unique id for the style
// $params['href']    style source
// $params['baseurl'] base url for style; defaults to your plugin folder
// $params['version'] style version
// $params['where']   where to load: GSFRONT, GSBACK or GSBOTH
// $params['media']   media type for style
```

```php
// Loads your_plugin/css/style.css on the front end
$plugin->style(array(
  'id' => 'your_plugin_style',
  'src' => 'css/style.css,
  'where' => GSFRONT,
));
```

### admin
Load the administration panel for your plugin
#### `admin($function)`
Execute a function when your plugin's admin page is accessed
```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_admin() {
  echo 'This is your admin panel';
}

$plugin->admin('your_plugin_admin');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  function admin() {
    echo 'This is your admin panel';
  }
}

// ...
$obj = new YourPlugin();
$plugin->admin(array($obj, 'admin'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/backend/admin.php
echo 'This is your admin panel';
```

```php
// ...
$plugin->admin('backend/admin.php');
```

#### `admin($url, $function)`
Execute the `$function` when the admin's url resembles the `$url`. `$url` can
be a normal string or a Regular Expression. If it is a regular expression, the
`$matches` will be passed as an array to the `$function`.

TODO: Expand on this

```php
// Load backend/create.php when in your_plugin&create
$plugin->admin('create', 'backend/create.php');

// Load backend/edit.php when in your_plugin&edit=[something]
$plugin->admin('/edit=.*/', 'backend/edit.php');
```

### index
Runs code on the front-end of your site. This is done to implement "custom page"
functionality (e.g. creating a blog plugin).

#### `index($url, $function)`
```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_index($page) {
  echo 'You are on the front page of your plugin!';
}

// ...
$plugin->index('your_plugin', 'your_plugin_index');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  function index($page) {
    echo 'You are on the front page of your plugin!';
  }

  // ...
}

// ...
$yp = new YourPlugin();
$plugin->index('your_plugin', array($yp, 'index'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/index.php
echo 'You are on the front page of your plugin!';
```

```php
// ...
$plugin->index('your_plugin', 'frontend/index.php');
```

### i18n
Returns and internationalized hash according to the languages you've defined
for your plugin (and if a hash doesn't exist, it will search for a built in one

```php
echo $plugin->i18n('PLUGIN_TITLE');
```

### autoload
Adds an autoloader to be registered when the plugin is initialized. Allows you
to `include` classes on fly.
#### `autoload($function)`
##### Global function

```php
// Includes classes in your_plugin/lib/*.php (in lower case)
function my_autoloader($class) {
  include(GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php');
}

// ...

$plugin->autoload('my_autoloader');
```

##### Class (object) method

```php
class YourPlugin {
  // ...

  // Includes classes in your_plugin/lib/*.php (in lower case)
  public function autoloader($class) {
    include(GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php');
  }

  // ...
}
// ...

$yp = new YourPlugin;
$plugin->autoload(array($yp, 'autoloader'));
```

##### Class (singleton) method

```php
class YourPlugin {
  // ...

  // Includes classes in your_plugin/lib/*.php (in lower case)
  public static function autoloader($class) {
    include(GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php');
  }

  // ...
}
// ...

$plugin->autoload('YourPlugin::autoloader'));
```

##### Script

```php
// your_plugin/autoloader.php
include(GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php');
```

```php
$plugin->autoload('autoloader.php'));
```

### init
Initializes plugin. Must be called in order for your plugin to be fully registered
and for the hooks/filters to be registered.

```php
// Finally:
$plugin->init();
```
