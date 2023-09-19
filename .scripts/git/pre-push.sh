#!/bin/sh
exec < /dev/tty

# Stash un-committed files including un-indexed
git stash -q -u

# PHP-CS-FIXER
echo 'Running pint...'
./vendor/bin/sail pint --test --quiet
if [ $? -ne 0 ]; then
  ./vendor/bin/sail pint && \
    git add . && \
    git commit -m "Apply code style changes"

    # Pop back
    git stash pop -q

    echo "\n\033[35m!!Run \`git push\` again. Code style fixed!!\n"
    exit 1
else
  echo 'passed'
fi

# PHP STAN
echo 'Running phpstan...'
./vendor/bin/phpstan --no-progress --memory-limit=1G
RESULT=$?

# Pop back
git stash pop -q

[ $RESULT -ne 0 ] && exit 1
echo 'passed'
