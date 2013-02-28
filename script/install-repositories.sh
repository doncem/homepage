#! /bin/bash
libs=`pwd`/source-libraries
cd ../../
mkdir source-libraries
cd source-libraries

while read -r line
do
   echo "--- CLONING [$line] REPOSITORY ---"
   git clone $line
done < $libs
