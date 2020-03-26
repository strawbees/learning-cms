# Learning CMS

This repository contains the source code of the Strawbees Learning CMS. The CMS itself is a WordPress installation. It provides data to the clients via WordPress's via built-in REST API.

**No front end will be rendered from this CMS, this is a strictly headless WordPress setup**.

## Installed Plugins

Make sure the plugins (`wp/wp-content/plugins`) are activated:

- [`amazon-s3-and-cloudfront`](https://github.com/deliciousbrains/wp-amazon-s3-and-cloudfront)

## Theme

The `wp/wp-content/themes/strawbees-learning` theme does a few things:

- Removes completely any rendered frontend from WordPress. You'll get a blank page.
- Filter out most types of blocks on Gutenberg editor, leaving only the basic components such as paragraph, heading, lists, images, etc.
- Register and load new custom block types to Gutenberg editor.
- Adds feature images to posts.
- Register menus.

## Local environment

```
$ docker swarm init
$ docker stack deploy -c stack.yml wordpress
```

This takes a few seconds and will create a folder called `db` where the SQL database will be stored. If the folder already exists, it will load the existing database.

To tear down:
```
$ docker stack rm wordpress
$ docker system prune
```

If you are on a Linux machine, check [Portainer](https://www.portainer.io/installation/)

Note: When your WordPress container starts it will mess up with `wp/wp-config.php` and `.httaccess`. Do not commit those changes. Discard the changes on those files with your favorite git ninja moves.

## Deploying

The application will default to settings the local/docker configuration if the correct environment variables are not set. This would allow deploying in most server setup.

The expected environment variables have the same name as the WordPress constants:

```
DB_NAME defaults to wp_learning
DB_USER defaults to root
DB_PASSWORD defaults to master_password
DB_HOST defaults to db:3306
S3_KEY
S3_SECRET
```

### Heroku

#### Setup

1. Install [cleardb](https://elements.heroku.com/addons/cleardb) addon
1. Install and login [heroku cli](https://devcenter.heroku.com/articles/heroku-cliheroku logs --tail)

There are a few files on `wp` folder that are specifically for the heroku setup:

- `composer.json`: Helps heroku detecting the PHP buildpack
- `Procfile`: Tells heroku how to start the application
- `apache.conf`: Tells heroku what are the apache configurations to use
- `.user.ini`: [Heroku PHP user configuration](https://devcenter.heroku.com/articles/custom-php-settings#php-runtime-settings)

#### Deploy

Heroku is expecting a repository with the app itself on the root so when pushing to heroku, push only the subtree of files from the `wp` folder:

```
git subtree push --prefix wp heroku master
```
