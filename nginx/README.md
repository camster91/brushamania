# Nginx setup

https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-lemp-on-ubuntu-18-04#step-4-configuring-laravel

sudo chown -R www-data:www-data /var/www/brushamania-web-app/storage
sudo chown -R www-data:www-data /var/www/brushamania-web-app/bootstrap/cache

sudo ln -s /etc/nginx/sites-available/brushamania.dentacloud.ai /etc/nginx/sites-enabled/
