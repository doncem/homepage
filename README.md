About project
=============

An extremely simple looking page with loads of libraries to generate it

Libraries
---------

* [xFrame](https://github.com/linusnorton/xFrame)
* [Twig](http://twig.sensiolabs.org)
* [Doctrine2](http://www.doctrine-project.org/)
* [Gearman Job Server](http://gearman.org/gearman)

Tools
-----

* [backbone](https://github.com/documentcloud/backbone)
* [flot](https://github.com/flot/flot)
* [jQuery](http://jquery.com/)
* [jQuery Color Plugin](https://github.com/jquery/jquery-color)
* [Memcached](http://www.memcached.org/)
* [Sass](http://sass-lang.com/)
* [Twitter Bootstrap v3](http://getbootstrap.com/)
* [underscore](https://github.com/documentcloud/underscore)

Versions of libraries and tools
-------------------------------

All libraries I use for dev are straight out of master branches (except Gearman). So, may not be 'stable' releases
All tools are installed/cloned from available repositories

Notes
-----------

* There is no collation for the `SET NAMES UTF-8` query. You should configure your MySQL server correctly. If you are lazy or don't know how to configure, you can simply follow this:
    * in your `my.cnf`, you have to add the lines `character-set-server=utf8` and `skip-character-set-client-handshake` at the end of the `[mysqld]` section.
* Section 'movies' and 'jukebox' in 'experiments' will work only with PHP 5.4 because I'm using `JsonSerializable` interface
* If you have your vhost configured with ALIASes of doc/ and report/ folders - you'll be able to see them within same domain :)

Phing
-----------

* `phing init` - should link all libraries
    * make sure you have cloned https://github.com/doncem/vendors.git in parent directory
* `phing document` - create default documentation into doc/ folder
* `phing` - should pass all tests and create coverage inside report/ folder
    * it also creates a static website inside static/ folder - because it runs test for deployment script as well ;)
