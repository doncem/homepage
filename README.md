About project
=============

An extremely simple looking page with loads of libraries to generate it

Libraries
---------

* [xFrame](https://github.com/linusnorton/xFrame)
* [Twig](http://twig.sensiolabs.org)
* [Doctrine2](http://www.doctrine-project.org/)

Tools
-----

* [backbone](https://github.com/documentcloud/backbone)
* [flot](https://github.com/flot/flot)
* [jQuery](http://jquery.com/)
* [jQuery Color Plugin](https://github.com/jquery/jquery-color)
* [Memcached](http://www.memcached.org/)
* [Sass](http://sass-lang.com/)
* [underscore](https://github.com/documentcloud/underscore)

Versions of libraries and tools
-------------------------------

All libraries I use for dev are straight out of master branches. So, not 'stable' releases

* Doctrine ORM - 2.4.0-DEV
* Doctrine Common - 2.4.0-DEV
* Doctrine DBAL - 2.4.0-DEV
* Twig - 1.12.4
* xFrame addendum - 2.1

All tools are installed/cloned from available repositories

* backbone - 1.0.0
* flot - 0.8.0
* jQuery - 1.9.1
* jQuery Color - 2.1.1
* Memcached - 1.4.14
* Sass - 3.1.15
* underscore - 1.4.4

Notes
-----------

* There is no collation for the `SET NAMES UTF-8` query. You should configure your MySQL server correctly. In your `my.cnf`, you have to add the lines `character-set-server=utf8` and `skip-character-set-client-handshake` at the end of the `[mysqld]` section.
* Section 'movies' will work only with PHP 5.4 because I'm using `JsonSerializable` interface
