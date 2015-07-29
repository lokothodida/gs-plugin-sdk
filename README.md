# \*EXPERIMENTAL\* GetSimple (Plugin) Development Environment
This project is an integrated development environment for the GetSimple CMS.
Its aim is to make (plugin) development simpler by providing a pre-configured
GetSimple installation that is geared towards developers, and which anyone can
set up easily regardless of what development machine they are using.

The environment comes with:

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

4. Clone this repository:

    ```
    $ git clone https://github.com/lokothodida/gs-plugin-sdk
    ```

5. In the cloned folder, run `vagrant up`
6. Go to `getsimple.dev/` to see the development site. Log into the admin
panel at `getsimple.dev/admin/` with username `admin` and password `demo123`
7. To turn off the server, run `vagrant halt`
