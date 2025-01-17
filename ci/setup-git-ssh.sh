#!/bin/bash

ssh-keygen -t ed25519 -C "aaron@dentacloud.ai"
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519
# now add key to github
# https://docs.github.com/en/authentication/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account

read -p "add key to github and press enter to continue"

cd /var/www

sudo chown -R $USER:$USER /var/www
git clone git@github.com:Dentacloud-ai/brushamania-web-app.git
sudo chmod -R 777 /var/www/brushamania-web-app/storage
