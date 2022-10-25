#!/bin/bash

for i in $(find . -name "*.php" -type f); do
  if [ "$i" != "./main.php" ]; then
      php main.php "$i"
  fi
done
