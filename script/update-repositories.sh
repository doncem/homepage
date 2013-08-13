#! /bin/bash

cd ../../source-libraries/

for entry in *
do
  if [ -d "$entry" ];then
    echo "--- RUNNING [$entry] UPDATE ---"
    cd $entry && git pull && git remote prune origin && cd ../
  fi
done

