#! /bin/bash
#
# I wrote it purely for my own usage.
# The main reason I'm using external libraries this way
# is because of the different accessibility to servers/desktops that I use.
# And yes, I know, it's quite a rubbish
#

workplace=`pwd`
libs=`pwd`/source-libraries
links=`pwd`/combine-libraries
cd ../../
mkdir source-libraries
cd source-libraries

echo "--- CLONING REPOSITORIES ---"
while read -r line
do
   echo "--- $line ---"
   git clone $line
done < $libs
