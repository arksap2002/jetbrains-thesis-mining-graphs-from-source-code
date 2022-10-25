#!/bin/bash

readarray -t filenames < repositories.txt

for filename in ${filenames[@]};
do
  git clone "https://github.com/$filename"
  ./processPhpFiles.sh
  rm -rf "${filename#*/}"

done
