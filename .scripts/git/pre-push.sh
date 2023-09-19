#!/bin/sh
exec < /dev/tty

# Stash un-committed files including un-indexed
git stash -q -u

# PHP-CS-FIXER
echo 'Running pint...'
docker-compose run --rm --no-deps api vendor/bin/pint --test --quiet
if [ $? -ne 0 ]; then
  docker-compose run --rm --no-deps api vendor/bin/pint && \
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
docker-compose run --rm --no-deps api vendor/bin/phpstan --xdebug --no-progress --memory-limit=1G
RESULT=$?

# Pop back
git stash pop -q

[ $RESULT -ne 0 ] && exit 1
echo 'passed'
