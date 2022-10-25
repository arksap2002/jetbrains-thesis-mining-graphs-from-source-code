#!/bin/bash

readarray -t filenames <repositories.txt

index=1

for filename in ${filenames[@]}; do
  echo "Progress: $index/100"
  ((index++))
  git clone "https://github.com/$filename" 2>file.log
  ./processPhpFiles.sh
  rm -rf "${filename#*/}" 2>file.log

done
