# Brushamania web app

## Useful scripts and the order to run them:

1. `ci/install-os-dependencies-ubuntu.sh` (once per machine)
2. `ci/install-app-dependencies.sh` (every time you `git pull` or change a dependency in `package.json` or `composer.json`)
3. `ci/build-assets.sh` for prod or `ci/watch-assets.sh` for dev
4. `ci/restart-nginx.sh` required when pulling a new prod code change

## Set up a dev environment

1. Install git and clone the repo
2. `cp .env.dev-example .env` and fill out missing information:
    - Database credentials can be retrieved from DigitalOcean
    - Mailgun credentials can be retrieved from Mailgun
3. Run the scripts to install the dependencies:
    - `ci/install-os-dependencies-ubuntu.sh`
    - `ci/install-app-dependencies.sh`
4. In two terminals, run `ci/watch-assets.sh` and `php artisan serve`

When making a backend change, you must restart `php artisan serve` for the change to take effect.
When making a frontend change, you only need to refresh the page after the webpack build is finished.

## Set up a production environment

1. Install git and clone the repo
2. `cp .env.prod-example .env` and fill out missing information:
    - Database credentials can be retrieved from DigitalOcean
    - Mailgun credentials can be retrieved from Mailgun
3. Run all the above scripts

## Deploy a new production version

1. Build the assets `ci/build-assets.sh`
2. Commit and push
3. SSH into the server and pull the new version:

```
ssh app.brushamania.ca
cd /var/www/brushamania-web-app
git fetch
git reset --hard origin/main
ci/install-app-dependencies.sh
ci/restart-nginx.sh
```

If you don't want to type this every time, use ~/deploy.sh

## Vendors

-   Mail sending: Mailgun - credentials in IT keyring (aaron@dentacloud.ai)
-   DNS configuration: Cloudflare - Cameron Ashley owns this, but it will be trasferred to Dentacloud eventually
-   Server and SSL: AWS - credentials in IT keyring (aws+BrushamaniaProduction@dentacloud.ai)
-   Database: DigitalOcean - credentials in IT keyring (aaron@dentacloud.ai)
-   Recaptcha: Google Cloud Platform - credentials private (aaron@verones.io)

## Other notes

-   The database is shared between dev and prod. It is the `edms` database instance and the `brushamania-dev` database on Dentacloud's DigitalOcean account
-   The server is set up in aws (aws+BrushamaniaProduction@dentacloud.ai)
-   The server is configured with git on Aaron's github account
-   Apparently the production build breaks vue's reactivity (probably the previous developers' misconfigurations) so we actually deploy the development build.

## Changelog of what Dentacloud has fixed since taking ownership

-   SSL was not correctly configured before
    -   Fixed mixed content errors from incorrect URL config in forms
    -   Set up SSL termination and HTTP-HTTPS redirect on AWS load balancer
-   Vue was not imported correctly
    -   Changed to ES6 import in app.js
-   Moved Mailgun to our account
-   Moved google recaptcha to our account

## Issues that have not been fixed

-   Cannot deploy production build (breaks reactivity)