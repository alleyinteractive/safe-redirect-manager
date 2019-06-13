Setting up a complete Largo dev environment
===========================================

**This recipe is really old. It may not suit your needs.**

This recipe will walk you through setting up a fresh WordPress install on a Vagrant Virtualbox machine with INN's deploy tools and Largo installed.

We'll walk you through the overall setup of the WordPress directory, and then we'll walk you through setting up Largo and its development requirements.

Software to install first
-------------------------

From `INN's computer setup guide <https://github.com/INN/docs/blob/master/staffing/onboarding/os-x-setup.md#command-line-utilities>`_, install the following software:

- git
- homebrew
- wp-cli
- virtualenv and virtualenvwrapper
- Vagrant
- npm and grunt-cli
- xgettext and msgmerge (only needed for rebuilding translation files and releasing)

If you're on OSX, you will also want to install Homebrew, to assist in the installation of the above.

Once you have all that set up, you're ready to install Largo and WordPress inside a virtual machine!

Setting up Largo and WordPress
------------------------------

1. Follow the instructions in `INN/docs for creating an umbrella repository at <https://github.com/INN/docs/blob/master/projects/largo/umbrella-setup.md>`_. This provides a few options, which are updated separately from Largo.

2. Now, to setting up Largo. ::

	cd wp-content/themes/largo

3. You're going to have to install some things first.

4. First, install the Python dependencies.

	We use a few Python libraries for this project, including `Fabric <https://www.fabfile.org>`_ which powers the INN deploy-tools to elegantly run `many common but complex tasks <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_. In the `OS X setup guide <https://github.com/INN/docs/blob/master/staffing/onboarding/os-x-setup.md>`_, you should have installed Python virtualenv and virtualenvwrapper.

	Make sure you tell virtualenvwrapper where the umbrella is. ::

		export WORKON_HOME=~/largo-umbrella
		mkdir -p $WORKON_HOME
		source /usr/local/bin/virtualenvwrapper.sh


	You should add that last line to your .bashrc or your .bash_profile.

	Now we can create a virtual environment and install the required Python libraries: ::

		mkvirtualenv largo-umbrella --no-site-packages
		workon largo-umbrella
		pip install -r requirements.txt

5. Now, the NodeJS dependencies.

	If this command fails, make sure you're in the ``largo`` directory. ::

		npm install

6. Our API docs/function reference uses doxphp to generate documentation based on the comments embedded in Largo's source code. You'll need to install doxphp to generate API docs.

	- Installation process with git: ::
        1. ``git clone https://github.com/avalanche123/doxphp.git`` to someplace in your filesystem
        2. add ``doxphp/bin/`` to your ``$PATH`` by adding ``export PATH=$PATH:/path/to/doxphp/bin`` to your ``.bashrc`` or similar file

	The last step may require you to use sudo.

All done? Log into WordPress and start poking around. Remember to take Vagrant snapshots when you get things working how you like the. You'll probably want to take one after you add some posts and configure your menus for testing purposes. If you want to log into the vagrant box, it's as easy as ``vagrant ssh``.

You have installed:

	- INN's deploy tools
	- the Largo theme
	- Grunt and the nodejs packages we use to handle a bunch of things
	- pip, virtualenv, a largo-umbrella virtualenv, sphinx, and everything needed to rebuild the documentation
	- doxphp and dpxphp2sphinx
	- WordPress on a dev environment of your choice.

Some notes about deploy-tools and Fabric
----------------------------------------

The full list of supported commands can be found in `the deploy-tools documentation <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_.

Most fabric commands take the form of ::

	fab <environment> <branch> <action>
	fab <action that defines its own environment>:arguments

Every command in `the list of commands <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_ is prefixed with ``fab``.

If you recieve an error when running your command, make sure that you have run ``workon largo-umbrella``, or the name of the Python virtualenv you are using. When run, ``workon`` will prefix your prompt: ::

	you@computer:~$ workon largo
	(largo-umbrella)you@computer:~$

To exit the virtualenv, you can use the command ``deactivate``.

Many commands in the deploy tools can now be done with `wp-cli <https://wp-cli.org/>`_.
