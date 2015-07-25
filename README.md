# GetSimple CMS Plugin SDK
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
* Download the Hello World plugin example
* Rename the `hello_world.php` file and `hello_world/` folder
* You now have a ready-built plugin with the SDK library included

## From an existing plugin
* Create a /lib/ directory in your plugin's folder
* Download the `gsutils.php`, `gsui.php` and `gsplugin.php` files from this repository and put them in the lib directory
* `include` those files before your plugin is registered
* You can now use these classes in your plugin

# Included Libraries
## GSUtils

### mkdir
### rmdir
### mvdir
### copy
### mkfile
### rmfile
### mvfile
### getfile
### getfiles
### slug
### translit
### clean

## GSUI

### header
### title
### quicknav
### parag
### leftsec
### rightsec
### metawindow
### form
### input
### richtexteditor
### codeeditor
### submit
### submitline
### footer

## GSPlugin

### __construct
### id
### author
### version
### tab
### sidebar
### hook
### filter
### admin
### index
### init
