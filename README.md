# GetSimple (Plugin) Development Environment
This project is an integrated development environment for the GetSimple CMS.
Its aim is to make (plugin) development simpler by providing a pre-configured
GetSimple installation that is geared towards developers, and which anyone can
set up, regardless of what development machine they are using.

# Table of Contents
* [Features](#features)
* [Installation](#installation)
* [Usage](#usage)
* [Developing Plugins](#developing-plugins)
* [SDK Library](#sdk-library)
* [Documentation](#documentation)
* [Troubleshooting](#troubleshooting)
* [Thanks](#thanks)

## Features

* Documented example plugins
* A build procedure to deploy plugins
* Developer mode turned on

## Installation

1. Install [VirtualBox](https://www.virtualbox.org/)
2. Install [Vagrant](https://www.vagrantup.com/)
3. Install [Vagrant Host Manager](https://github.com/smdahlen/vagrant-hostmanager)
by running:

    ```
    $ vagrant plugin install vagrant-hostmanager
    ```

4. Clone this repository to any desired folder:

    ```
    $ git clone https://github.com/lokothodida/gs-plugin-sdk
    ```

## Usage

1. In the cloned folder, run `vagrant up`
2. Go to `getsimple.dev/` in your browser to see the development site. Log into
the admin panel at `getsimple.dev/admin/` with username `admin` and password `demo123`
3. [Develop your plugin!](#developing-plugins)
4. To turn off the VM, run `vagrant halt`. To destroy the VM, run `vagrant destroy`.

## Developing plugins

The best workflow with this development environment comes from having a
repository for your plugin. You can still benefit otherwise.

1. Copy one of the example plugins in the `plugins` directory and rename it to
your needs.
2. Code the plugin to your needs using the [SDK library](#sdk-library)
3. In the `build` folder, clone your plugin's repository.
4. Go to `Plugins` -> `Build Plugins`
5. By the name of your plugin, type the name of the repository folder and click
`Build`.
6. Your plugin will now packaged up with the SDK library and copied directly to
your repository.

This workflow allows you to develop in one development environment and easily
deploy the results to your own repositories.

## SDK Library

The SDK Library is a collection of classes designed to make plugin development
easier. These classes are:

* `GSPlugin` - for plugin registration, hooks, filters and admin panel
* `GSUtils` - for general utilities, such as creating/saving data, manipulating strings, etc...
* `GSUI` - for building UI elements consistent with the admin panel

## Documentation

Go [here](public/docs/) for documentation on the SDK library methods and development
using them. Each example plugin in this repository should be fully documented as
to its use-case and construction, so be sure to read the source code of the
examples.

## Troubleshooting

### Ubuntu/Debian
* If your terminal gives you this message:
    ```
    It appears your machine doesn't support NFS, or there is not an
    adapter to enable NFS on this machine for Vagrant. Please verify
    that `nfsd` is installed on your machine, and try again. If you're
    on Windows, NFS isn't supported. If the problem persists, please
    contact Vagrant support.
    ```
    Then make sure that you have the `nsf-common` installed:

    ```
    sudo apt-get install nfs-common
    ```

## Thanks
* [GetSimpleCMS](https://github.com/GetSimpleCMS/) for the great CMS
* [scotch-io](https://github.com/scotch-io/) for the [Scotch Box](https://github.com/scotch-io/scotch-box) Vagrant setup
