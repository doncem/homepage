#! /bin/bash

echo "--- LINKING EXTERNAL LIBRARIES ---"
cd ../
mkdir lib
cd lib
mkdir Doctrine
cd Doctrine
ln -s ../../vendor/doctrine/lib/Doctrine/ORM/ ORM
ln -s ../../vendor/doctrine-common/lib/Doctrine/Common/ Common
ln -s ../../vendor/doctrine-dbal/lib/Doctrine/DBAL/ DBAL
cd ../
ln -s ../vendor/twig/lib/Twig/ Twig
ln -s ../vendor/xFrame/lib/xframe/ xframe
ln -s ../vendor/xFrame/lib/addendum/ addendum
cd ../vedor/doctrine-common/lib/Doctrine/Common/
ln -s ../../../../doctrine-annotations/lib/Doctrine/Common/Annotations/ Annotations
ln -s ../../../../doctrine-cache/lib/Doctrine/Common/Cache/ Cache
ln -s ../../../../doctrine-collections/lib/Doctrine/Common/Collections/ Collections
ln -s ../../../../doctrine-lexer/lib/Doctrine/Common/Lexer/ Lexer
