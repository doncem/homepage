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

* [jQuery](http://jquery.com/)
* [flot](http://code.google.com/p/flot/)
* [Sass](http://sass-lang.com/)
* [Memcached](http://www.memcached.org/) (be sure you have php5-memcached and not php5-memcache)

Link libraries
--------------

Either you have them installed somewhere and already is in include path or you must add to lib/ folder:

* xFrame/lib/addendum
* xFrame/lib/xframe
* twig/lib/Twig
* into lib/Doctrine folder, add as follows:
    * doctrine/lib/Doctrine/ORM
    * doctrine-common/lib/Doctrine/Common
    * doctrine-dbal/lib/Doctrine/DBAL

Versions of libraries and tools
----------------

All libraries I use for dev are straight out of master branches. So, not 'stable' releases

* xFrame addendum - 2.1
* Twig - 1.12.0
* Doctrine ORM - 2.4.0-DEV
* Doctrine Common - 2.4.0-DEV
* Doctrine DBAL - 2.4.0-DEV

All tools are installed/used from available repositories with stable releases

* jQuery - 1.8.3
* flot - 0.7
* Sass - 3.1.15
* Memcached - 1.4.14
