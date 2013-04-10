#! /bin/bash

echo "--- LINKING EXTERNAL LIBRARIES ---"
cd ../../
mkdir libraries
cd libraries
mkdir Doctrine
cd Doctrine
ln -s ../../source-libraries/doctrine/lib/Doctrine/ORM/ ORM
ln -s ../../source-libraries/doctrine-common/lib/Doctrine/Common/ Common
ln -s ../../source-libraries/doctrine-dbal/lib/Doctrine/DBAL/ DBAL
cd ../
ln -s ../source-libraries/backbone/backbone.js backbone.js
ln -s ../source-libraries/jquery-color/jquery.color.js jquery.color.js
ln -s ../source-libraries/jquery-flot/jquery.flot.js jquery.flot.js
ln -s ../source-libraries/underscore/underscore.js underscore.js
ln -s ../source-libraries/twig/lib/Twig/ Twig
ln -s ../source-libraries/xFrame/lib/xframe/ xframe
ln -s ../source-libraries/xFrame/lib/addendum/ addendum
cd ../source-libraries/doctrine-common/lib/Doctrine/Common/
ln -s ../../../../doctrine-annotations/lib/Doctrine/Common/Annotations/ Annotations
ln -s ../../../../doctrine-cache/lib/Doctrine/Common/Cache/ Cache
ln -s ../../../../doctrine-collections/lib/Doctrine/Common/Collections/ Collections
ln -s ../../../../doctrine-lexer/lib/Doctrine/Common/Lexer/ Lexer
