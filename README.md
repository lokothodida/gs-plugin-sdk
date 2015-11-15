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

```php
// == CONSTRUCTOR PARAMETERS ==
// $params['basepath']  base path for operations; default is GSDATAOTHERPATH
```

```php
$utils = new GSUtils(array(
  // TODO
));
```

### mkdir
Makes a directory and initializes it with an htaccess file. Throws an exception
if the directory could not be made. `$htaccess` defaults to "Deny from all" -
otherwise pass a string to it with the desired file contents.

#### `mkdir($path[, $mode = 0755, $recursive = true, $htaccess = true])`

```php
try {
  $utils->mkdir('yourdir');
} catch (Exception $error) {
  echo 'Could not create directory';
}
```

### rmdir
Removes a directory. If `$force` is set to true, all
files and directories inside will be removed. Throws an exception if there was
an error deleting the directory.

#### `rmdir($path[, $force = false])`

```php
try {
  $utils->rmdir('yourdir', true);
} catch (Exception $error) {
  echo 'Error removing directory';
}
```

### mvdir
Moves a directory

```php
try {
  $utils->mvdir('yourdir', 'anotherdir');
} catch (Exception $error) {
  echo 'Could not move directory';
}
```

### copy
Copy a directory. Throws exception if there is an error copying the directory.

```php
try {
  $utils->copy('yourdir', 'anotherdir');
} catch (Exception $error) {
  echo 'Could not copy directory';
}
```

### mkfile
Makes a file.

#### `mkfile($jsonfile, $data)`
```php
try {
  $utils->mkfile('yourdir/data.json', array(
    'key1' => 'value1',
    'key2' => 'value2',
  ));
} catch (Exception $error) {
  echo 'Error creating file';
}
```

#### `mkfile($otherfile, $data)`

### putfile

Writes contents to the file (same signature as mkfile). Throws exception if there
was an error writing to the file.

```php
try {
  $utils->putfile('yourdir/data.json', array(
    'key1' => 'value1',
    'key2' => 'value2',
  ));
} catch (Exception $error) {
  echo 'Error putting file contents';
}
```

### rmfile
Deletes a file.

```php
try {
  $utils->rmfile('yourdir/data.json');
} catch (Exception $error) {
  echo 'Error deleting file';
}
```

### mvfile
Moves a file.

```php
try {
  $utils->rmfile('yourdir/data.json', 'somedir/data.json');
} catch (Exception $error) {
  echo 'Error moving file';
}
```

### getfile
Gets file contents. Parses the contents if the file is a JSON.

```php
try {
  $file = $utils->getfile('yourdir/data.json');
  echo $file['key1'];
} catch (Exception $error) {
  echo 'Error getting file';
}
```

### getfiles
Gets array of file contents. Parses the contents if the file is a JSON.

```php
try {
  $files = $utils->getfiles('yourdir/*.json');
  foreach ($files as $filename => $data) {
    // ...
  }
} catch (Exception $error) {
  echo 'Error getting files';
}
```

### fileexists
Returns `true` iff the file exists.

```php
if ($utils->fileexists('yourdir/data.json')) {
  // ...
}
```

### iswriteable
Returns true iff the file is writeable

```php
if ($utils->iswriteable('yourdir/data.json')) {
  // ...
}
```

### print
Pretty-prints variables.

```php
echo $utils->print($var1, $var2, $var3);
```

### slug
Slugify a string.

```php
$slug = $utils->slug('SOMEthing HErE'); // something-here
```

### translit
Transliterate a string.

```php
```

### clean
Sanitize a string.

```php
```

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
  'type' => 'text',
  'name' => 'title',
  'value' => 'Your Title Here',
));

// Checkbox
echo $ui->input(array(
  'label' => 'Enable HTML Editor?',
  'type' => 'checkbox',
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

### template
Templating for `{placeholders}`. Takes an HTML string and an `array` mapping
placeholder names to values and returns the HTML string with placeholders
replaced.

```php
echo $ui->template($html, array(
  'username'    => 'Jim',
  'registered'  => 'July 2020',
  'age'         => '106',
), 'Content');
```

## GSPlugin
This class is designed to ease plugin, hook, and filter registration.

### SDK_VERSION
Current SDK version. Use this to check if another version of the SDK has been
loaded.

```php
if (GSPlugin::SDK_VERSION <= 0.1) {
  // ...
}
```

### __construct
Instantiate the plugin wrapper with data about the plugin.

#### `__construct($params)`

```php
// == CONSTRUCTOR PARAMETERS ==
// $params['id']      (string)         plugin id (normally the plugin folder)
// $params['author']  (string)         plugin author
// $params['version'] (string|num)     plugin version
// $params['website'] (string)         author website
// $params['tab']     (string)         main plugin tab
// $params['lang']    (string)         default language
// $params['deps']    (array (string)) plugin ids of plugins that need to be
//                                     installed for this plugin to load
```

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


### init
Initializes plugin. Must be called at the end in order for all of your elements
to be registered.

```php
// Finally:
$plugin->init();
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

### website
Get the plugin website (same as one given in the constructor)

```php
echo 'The plugin website is ' . $plugin->website();
```

### path
Get the canonical path to your plugin's folder

```php
include $plugin->path() . 'somefile.php';
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
// your_plugin&myaction
$plugin->tab('myothertab', 'My Other Tab', 'myaction');
```

### sidebar
#### `sidebar($label[, $action, $visibility = true, $tab])`
Register a sidebar for the plugin. Defaults to the tab that you initialized the plugin with.

```php
// Add a sidebar link
$plugin->sidebar('My Plugin');

// Add sidebar link to url 'your_plugin&view'
$plugin->sidebar('View Items', 'view');

// Add sidebar link to url 'your_plugin&create' that only appears when inside
// your plugin
$plugin->sidebar('Create Item', 'create', false);

// Add sidebar link that appears on the 'plugins' tab
$plugin->sidebar('About Your Plugin', 'about', true, 'plugins');
```

### hook
Register a plugin [hook](http://get-simple.info/wiki/plugins:hooks_filters).

#### `hook($name, $callback[, $callbackargs])`

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

// Registration file:
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
Register a plugin [filter](http://get-simple.info/wiki/plugins:hooks_filters).

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

// Registration file:
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
#### `admin($callback)`
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
  // ...

  public function admin() {
    echo 'This is your admin panel';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->admin(array($yp, 'admin'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/backend/admin.php
echo 'This is your admin panel';

// In your registration file:
$plugin->admin('backend/admin.php');
```

#### `admin($url, $callback)`
Execute the `$callback` when the admin's url resembles the `$url`.

* The `$callback` takes an `(array) $exports` parameter, which has a collection of
variables that are imported into the scope of your function.
* `$url` can be a normal string or a Regular Expression. If it is a RegEx, the
`matches` will be be available as an array on `$exports`.

    ```php
    // == EXPORTS PARAMETERS ==
    // $exports['plugin']   (GSPlugin) your plugin instance
    // $exports['matches']  (array)    matches made on $url (if it was a RegEx)
    ```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function createPage($exports) {
    echo 'This is the create page';
  }

  public function editPage($exports) {
    $edit = $exports['matches'][0];
    echo 'This is the edit page for ' . $edit;
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->admin('create',    array($yp, 'createPage'));
$plugin->admin('/edit=.*/', array($yp, 'editPage'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/backend/create.php
echo 'This is the create page';

// your_plguin/backend/edit.php
$edit = $exports['matches'][0];
echo 'This is the edit page for ' . $edit;

// Registration file:
$plugin->admin('create',    'backend/create.php');
$plugin->admin('/edit=.*/', 'backend/edit.php');
```

### index
Runs code on the front-end of your site. This is done to implement "custom page"
functionality (e.g. creating a blog plugin).

#### `index($url, $callback)`

* The `$callback` takes an `(array) $exports` parameter, which has a collection of
variables that are imported into the scope of your function.
* `$url` can be a normal string or a Regular Expression. If it is a RegEx, the
`matches` will be be available as an array on `$exports`.

    ```php
    // == EXPORTS PARAMETERS ==
    // $exports['plugin']   (GSPlugin) your plugin instance
    // $exports['matches']  (array)    matches made on $url (if it was a RegEx)
    // $exports['page']     (object)   object with data about the current page
    ```

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_index_page($exports) {
  echo 'You are on the front page of your plugin!';
}

function your_plugin_foo_page($exports) {
  $bar = $exports['matches'][0];
  echo 'You are on the foo/' . $bar . ' page of your plugin!';
}

// ...
$plugin->index('your-plugin', 'your_plugin_index_page');
$plugin->index('your-plugin', 'your_plugin_foo_page');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function indexPage($exports) {
    // Page title
    $page = $exports['page'];
    $page->title = 'Your Plugin Main Page';

    // Page contents
    echo 'You are on the front page of your plugin!';
  }

  public function fooPage($exports) {
    // Page title
    $page = $exports['page'];
    $page->title = 'Your Plugin Foo Page';

    // Page contents
    $bar = $exports['matches'][0];
    echo 'You are on the foo/' . $bar . ' page of your plugin!';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->index('your-plugin',           array($yp, 'indexPage'));
$plugin->index('/your-plugin/foo/(.*)', array($yp, 'fooPage'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/index.php
// Page title
$page = $exports['page'];
$page->title = 'Your Plugin Main Page';

// Page contents
echo 'You are on the front page of your plugin!';

// your_plugin/frontend/foo.php
// Page title
$page = $exports['page'];
$page->title = 'Your Plugin Foo Page';

// Page contents
$bar = $exports['matches'][0];
echo 'You are on the foo/' . $bar . ' page of your plugin!';

// ...
// In your plugin registration file:
$plugin->index('your-plugin',           'frontend/index.php');
$plugin->index('/your-plugin/foo/(.*)', 'frontend/foo.php');
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
#### `autoload($callback)`

```php
// == GLOBAL FUNCTION EXAMPLE ==
// Includes classes in your_plugin/lib/*.php (in lower case)
function your_plugin_lib($class) {
  include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';
}

// ...

$plugin->autoload('your_plugin_lib');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function loadLib($class) {
    include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->autoload(array($yp, 'loadLib'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/loadlib.php
include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';

// In your plugin registration file:
$plugin->autoload('loadlib.php'));
```
