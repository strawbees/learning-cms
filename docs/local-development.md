# Local development
## Dependencies
* Docker
* Lando (https://lando.dev/)
* WP CLI (https://wp-cli.org/#installing)
* MySQL: `brew install mysql`

## 1. Create `.env`
```
DB_NAME=wordpres
DB_USER=wordpress
DB_PASSWORD=wordpress
DB_HOST=database
DB_PREFIX=wp_

WP_ENV=development
WP_HOME=http://learning-cms.lndo.site
WP_SITEURL=${WP_HOME}/wp
```
*NOTE*: If you are using W3 Total Cache plugin, you will also need to set the
var `WP_CACHE=true` on the `.env` file.

## 2. Using Lando
To start the server:
```shell
# @localhost
lando start
```
The website will be avaiable at `http://learning-cms.lndo.site`

To stop:
```shell
# @localhost
lando stop
```
