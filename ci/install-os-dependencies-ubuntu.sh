#!/bin/bash

# this is used to install os-level dependencies into a fresh ubuntu box

# default bin is /usr/bin

# install php
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt -y install \
    software-properties-common \
    curl \
    git \
    php7.4 \
    php7.4-gd \
    php7.4-dom \
    php7.4-zip \
    php7.4-curl \
    php7.4-mbstring \
    php7.4-xml \
    php7.4-bcmath \
    php7.4-fpm \
    php7.4-mysql \
    nginx

# install composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/bin/composer

# install nodejs
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"                   # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion" # This loads nvm bash_completion
nvm install 16
nvm use 16
