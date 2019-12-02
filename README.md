# Server Setup
## 1. Create a Nginx server on Lightsail
* Login to https://lightsail.aws.amazon.com/ > "Create an instance"
* Region: `us-east-1a`
* Platform: `Linux/Unix`
* Apps + OS: `Nginx` (from Bitnami)
* Select the SSH key pair. This will be the *root* key, that can do anything on
the instance. If this is the first time you are provisioning this project,
create a new one (eg. `learning-cms-root`), download it, and keep it safe with
the owner of the project. If this key has already been issued and is in safe
hands, just selected it from the list.
  - If you have just created the key, place it in the `~/.ssh` directory and
  harden it's permissions: `chmod 600 ~/.ssh/learning-cms-root.pem`
* Pick the suitable instance plan
* Identify your instance properly, eg. `learning-cms-stage`
* Add a "Key-only" tag with the Identify above.
* Create the instance!
* After the instance has booted, head to the "Networking" session and create a
static IP and attache it to the instance. If you already have an IP associated
with this service, attach it instead and skip the step below.
* Add the IP as a `A` record to the domain/subdomain you want to associate with
this server.
|name                             |type|value        |tty|
|---------------------------------|----|-------------|---|
|learning-cms-stage.strawbees.com.|A   |x.xxx.xxx.xxx|360|

## 2. Install dependencies
#### Composer
```shell
# @remote
# change to root
sudo su
php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
```
#### WP-CLI
```shell
# @remote
# change to root
sudo su
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
```
## 3. Add bin paths to `/etc/environment`
When our deploy system connects to the server via SSH, it will do it via a
non-interactive shell, meaning that `~/.bashrc` or `~./.profile` won't be loaded
and the $PATH variable is likely to not have the location of some important
programs we need during deploy (php, composer, mysql, etc).

To mitigate that, we will included some known paths into `/etc/environment`,
since that will certainly be loaded.
```shell
# @remote
# change to root
sudo su
vim /etc/environment
```

Replace this line:
```
PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/game"
```
With:
```
PATH="/opt/bitnami/varnish/bin:/opt/bitnami/sqlite/bin:/opt/bitnami/nginx/sbin:/opt/bitnami/php/bin:/opt/bitnami/mysql/bin:/opt/bitnami/common/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/game"
```

## 4. Setup a MySQL database and user
Since the Bitnami Ngnix image already has MySQL built in, with a user (`root`)
and a password (created when the machine is first instanced), you will need to
first grab this password, so you can log into the server and create the user and
the database that will be used by our application.

To access the password, SSH into the server and type:
```shell
cat /home/bitnami/bitnami_application_password
```
With that password in hand (will refer to it as `BITNAMI_APPLICATION_PASSWORD`,
in this document), let's connect to the database server:
```shell
# @remote
# change to root
sudo su

# connect to the server (note there is no space after '-p')
mysql -u root -pBITNAMI_APPLICATION_PASSWORD
```
We know we are in the server, if now the command line is prefixed by `mysql>`.
Now, we just type into the commands bellow to create the database and the user.
We will create a database with the name `appdb`, a user with the name
`appdbuser` and a secure password that should be generated (we will refer to it
as `GENERATED_DB_PASSWORD`, keep it handy, since we will need to use it in the
environment variables of the application):

```sql
create database appdb;
create user 'appdbuser'@'127.0.0.1' identified by 'GENERATED_DB_PASSWORD';
grant all privileges on appdb.* TO 'appdbuser'@'127.0.0.1';
exit
```
## 5. Setup Authentication & Authorisation
Good to read [this](https://capistranorb.com/documentation/getting-started/authentication-and-authorisation/)
first, but I will try to summarise it here.

SSH into the remote instance (via the browser directly on Lightsail or with your
own client, with the root ssh key created in the first step):

```shell
ssh -i ~/.ssh/learning-cms-root.pem bitnami@learning-cms-stage.strawbees.com
```

Create a deploy user:
```shell
# @remote
# change to root
sudo su
# create deploy user (if prompted by password, type anything; skip user details)
adduser deploy
# disable the password
passwd -l deploy
# change to deploy
su - deploy
```
Since the deploy user will need to be able to restart the server as well as
change some file permissions and ownerships, we need to add it to
`/etc/sudoers`.

To do that, you first open the sudoers file:
```shell
# @remote
# change to root
sudo su
# open sudoers
export VISUAL=vim; visudo
```
Then add the following lines:
```
deploy ALL=(ALL:ALL) NOPASSWD: /opt/bitnami/ctlscript.sh
deploy ALL=(ALL:ALL) NOPASSWD: /bin/chmod
deploy ALL=(ALL:ALL) NOPASSWD: /bin/chown
deploy ALL=(ALL:ALL) NOPASSWD: /usr/bin/find
```
Now on your local machine. Assuming you have your own *private* SSH key pair
already generated (if not follow the "1.1 SSH keys from workstation to servers",
on the link above), then copy the *public* key to your clipboard:
Just print the key to the terminal and copy manually (make sure to NOT have any
newline character copied in the end!):
```shell
# @localhost
cat ~/.ssh/id_rsa.pub
```
Back in the remote instance, add your private key to the
`~/.ssh/authorized_keys` file. You should do this for every developer.
```shell
# @remote
# change to deploy
su - deploy
# create the .ssh folder
cd ~
mkdir .ssh
# paste your public key (pay attention, as it is a long string)
echo "ssh-rsa jccXJ..../w== user@email.com" >> .ssh/authorized_keys
# fix permissions
chmod 700 .ssh
chmod 600 .ssh/authorized_keys
```
**Remember:** This needs to be done on every server you want to use, you can use
the same key for each one, but only one key per developer is recommended.
*Private* keys are named as such for a reason!

If we did all that correctly, we should now be able to do something like this:
```shell
# @localhost
ssh deploy@learning-cms-stage.strawbees.com 'hostname; uptime'
```
That should happen without having to enter a passphrase for your SSH key, or
prompting you for an SSH password (which the deploy user doesn’t have anyway).

Verify that this works for all of your servers, and put your private key
somewhere safe. If you’re working with multiple team members, it often pays to
collect everyone’s public keys, indeed if your team is already using SSH keys
to access Github, you can reach any user’s SSH keys at the following URL:
* https://github.com/theirusername.keys
This can make getting user’s keys onto servers much easier, as you can simply
`curl`/`wget` each user’s key into the authorised keys file on the server
directly from Github!

## 6. Prepare files on the server
In the following instructions, we are assuming that the name of our app is
`wordpress`. We are also assuming the app domain will be
`learning-cms-stage.strawbees.com` (in the `nginx-vhosts.conf`). If you are
using these instructions for another app, change it accordingly (do a "search
and replace")!

### 6.1. Ngnix config
Scaffold the app directories, with the correct permissions:
```shell
# @remote
# change to root
sudo su
mkdir /opt/bitnami/apps/wordpress
mkdir /opt/bitnami/apps/wordpress/htdocs/
mkdir /opt/bitnami/apps/wordpress/conf
chown -R deploy:daemon /opt/bitnami/apps/wordpress/htdocs/
chmod -R g+w /opt/bitnami/apps/wordpress/htdocs/
```
Create the app's configuration files:
```shell
# @remote
# change to root
sudo su

# nginx-prefix.conf
echo '' > /opt/bitnami/apps/wordpress/conf/nginx-prefix.conf

# nginx-app.conf
echo 'index index.php;

location = /favicon.ico {
  log_not_found off;
  access_log off;
}

location = /robots.txt {
  allow all;
  log_not_found off;
  access_log off;
}

location / {
  try_files $uri $uri/ /index.php?q=$uri&$args;
}

if (!-e $request_filename)
{
  rewrite ^/(.+)$ /index.php?q=$1 last;
}

location ~ \.php$ {
  fastcgi_split_path_info ^(.+\.php)(/.+)$;
  fastcgi_read_timeout 300;
  fastcgi_pass unix:/opt/bitnami/php/var/run/www.sock;
  fastcgi_index index.php;
  fastcgi_param  SCRIPT_FILENAME $request_filename;
  include fastcgi_params;
}

include /opt/bitnami/apps/wordpress/htdocs/current/web/nginx[.]conf;
' > /opt/bitnami/apps/wordpress/conf/nginx-app.conf

# nginx-vhosts.conf
echo 'server {
    listen    80;
    root "/opt/bitnami/apps/wordpress/htdocs/current/web/";
    server_name  learning-cms-stage.strawbees.com;
    include "/opt/bitnami/apps/wordpress/conf/nginx-app.conf";
}
server {
    listen    443 ssl;
    root   "/opt/bitnami/apps/wordpress/htdocs/current/web/";
    server_name  learning-cms-stage.strawbees.com;
    ssl_certificate  "/opt/bitnami/apps/wordpress/conf/server.crt";
    ssl_certificate_key  "/opt/bitnami/apps/wordpress/conf/server.key";
    ssl_session_cache    shared:SSL:1m;
    ssl_session_timeout  5m;
    ssl_ciphers  HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers  on;
    include "/opt/bitnami/apps/wordpress/conf/nginx-app.conf";
}
' >  /opt/bitnami/apps/wordpress/conf/nginx-vhosts.conf
```
Modify the existing "bitnami-apps" configs, so that they point to our newly
created app:
```shell
# @remote
# change to root
sudo su

# bitnami-apps-prefix.conf
echo 'include "/opt/bitnami/apps/wordpress/conf/nginx-prefix.conf";' >> /opt/bitnami/nginx/conf/bitnami/bitnami-apps-prefix.conf

# bitnami-apps-vhosts.conf
echo 'include "/opt/bitnami/apps/wordpress/conf/nginx-vhosts.conf";' >> /opt/bitnami/nginx/conf/bitnami/bitnami-apps-vhosts.conf
```
Restart Ngnix:
```shell
# @remote
sudo /opt/bitnami/ctlscript.sh restart nginx
```
### 6.2. Prepare for Capistrano
Since we already know we will use Capistrano for deployment, and which files it
needs, create the `.env` and the `web/ngnix.conf` files that will be consumed by
Wordpress. Note that you will need the Bitnami Application password generated at
step 2. Also generate fresh salts at https://roots.io/salts.
```shell
# @remote
# change user to deploy
su - deploy
mkdir /opt/bitnami/apps/wordpress/htdocs/shared
echo 'DB_NAME=appdb
DB_USER=appdbuser
# The password generated at the step "Setup a MySQL database and user"
DB_PASSWORD=GENERATED_DB_PASSWORD
# Keep the DB_HOST here, even if it is the default! Do not use "localhost"!
DB_HOST=127.0.0.1
DB_PREFIX=wp_

WP_ENV=development
WP_HOME=https://learning-cms-stage.strawbees.com
WP_SITEURL=${WP_HOME}/wp

# wordpress plugins
PLUGIN_ACF_KEY=YOUR_KEY

# generate at https://roots.io/salts.html
AUTH_KEY=GENERATE_ME
SECURE_AUTH_KEY=GENERATE_ME
LOGGED_IN_KEY=GENERATE_ME
NONCE_KEY=GENERATE_ME
AUTH_SALT=GENERATE_ME
SECURE_AUTH_SALT=GENERATE_ME
LOGGED_IN_SALT=GENERATE_ME
NONCE_SALT=GENERATE_ME
' > /opt/bitnami/apps/wordpress/htdocs/shared/.env
mkdir /opt/bitnami/apps/wordpress/htdocs/shared/web
touch /opt/bitnami/apps/wordpress/htdocs/shared/ngnix.conf
```

## 7. SSL Certificates
Boiled down from [Bitnami's guide](https://docs.bitnami.com/aws/apps/mattermost/administration/generate-configure-certificate-letsencrypt/). Maybe a good idea to check that link too!

*NOTE: If you duplicate this server (eg. to make a test/staging version), you
will need to re-run the steps below (from 7.2) with the correct domain.*


### 7.1. Install the Lego client
```shell
# @remote
# change to root
sudo su

# download and install lego
cd /tmp
curl -Ls https://api.github.com/repos/xenolf/lego/releases/latest | grep browser_download_url | grep linux_amd64 | cut -d '"' -f 4 | wget -i -
tar xf lego_v3.1.0_linux_amd64.tar.gz
mkdir -p /opt/bitnami/letsencrypt
mv lego /opt/bitnami/letsencrypt/lego
```
### 7.2. Generate a Let’s Encrypt certificate
Turn off all Bitnami services:
```shell
# @remote
sudo /opt/bitnami/ctlscript.sh stop
```
Request a new certificate for your domain as below, both with and without the
www prefix. Remember to replace the `DOMAIN` placeholder with your actual domain
name, and the `EMAIL-ADDRESS` placeholder with your email address.

You can use more than one domain (for example, DOMAIN and www.DOMAIN) by
specifying the --domains option as many times as the number of domains you want
to specify.

```shell
# @remote
sudo /opt/bitnami/letsencrypt/lego --tls --email="EMAIL-ADDRESS" --domains="DOMAIN" --domains="www.DOMAIN" --path="/opt/bitnami/letsencrypt" run
```

### 7.3. Configure server to use the certificates
```shell
# @remote
# change to root
sudo su

# delete any previous certificates
rm -rf /opt/bitnami/apps/wordpress/conf/server.key
rm -rf /opt/bitnami/apps/wordpress/conf/server.crt

# symlink the certificates to the correct location
ln -sf /opt/bitnami/letsencrypt/certificates/DOMAIN.key /opt/bitnami/apps/wordpress/conf/server.key
ln -sf /opt/bitnami/letsencrypt/certificates/DOMAIN.crt /opt/bitnami/apps/wordpress/conf/server.crt
chown root:root /opt/bitnami/nginx/conf/server*
chmod 600 /opt/bitnami/nginx/conf/server*

# restart the services
/opt/bitnami/ctlscript.sh start
```

### 7.4. Renew certificates
To automatically renew your certificates before they expire, write a script to
perform the tasks and schedule a cron job to run the script periodically. To do
this:

Create a script at /opt/bitnami/letsencrypt/scripts/renew-certificate.sh
```shell
# @remote
# change to root
sudo su
mkdir -p /opt/bitnami/letsencrypt/scripts
touch /opt/bitnami/letsencrypt/scripts/renew-certificate.sh
chmod +x /opt/bitnami/letsencrypt/scripts/renew-certificate.sh
vim /opt/bitnami/letsencrypt/scripts/renew-certificate.sh
```

Enter the following content into the script and save it. Remember to replace the
`DOMAIN` placeholder with your actual domain name, and the `EMAIL-ADDRESS`
placeholder with your email address.
```bash
#!/bin/bash

sudo /opt/bitnami/ctlscript.sh stop nginx
sudo /opt/bitnami/letsencrypt/lego --tls --email="EMAIL-ADDRESS" --domains="DOMAIN" --path="/opt/bitnami/letsencrypt" renew --days 90
sudo /opt/bitnami/ctlscript.sh start nginx
```
Execute the following command to open the crontab editor:
```shell
# @remote
sudo crontab -e
```
Add the following lines to the crontab file and save it:
```
0 0 1 * * /opt/bitnami/letsencrypt/scripts/renew-certificate.sh 2> /dev/null
```
## 8. Snapshot the instance!
You really don't want to have to redo all of the steps above, so this is a good
moment to take a snapshot of your instance, so you can rollback to this exact
point! Do you via the Lightsail dashboard.

# Creating a new stage

## 1. Snapshot the instance
In the Lightsail dashboard, create a new snapshot of the instance you would like
to fork (or use an existing snapshot).

## 2. Create a new instance from the snapshot
Once you have the snapshot, click on it's menu (3 vertical dots) > "Create new
instance". Make sure to name the instance properly, eg.`learning-cms-fork`.

Once the new instance is created, you can delete the snapshot.

## 3. Attach the instance to a static IP
In Lightsail, create a new static IP and attach it to the newly created
instance.

If you wish to run this stage from a custom domain, use the static IP as the
A-Record on your DNS.

## 4. Update the vhosts
Once you know the new HOST (the custom domain, or just the static IP, if you
are not using a domain) you will need to ssh into it and update the vhosts.

The new instance has the SSH key as the source one, so you will use the same
process to connect to it. Just make sure to use the correct HOST. For example:
```shell
ssh -i ~/.ssh/learning-cms-root.pem bitnami@learning-cms-fork.strawbees.com
```
Open the vhosts file and update the entries for `server_name`, for both servers,
the one port 80 and the one on port 443.

```shell
# @remote
# open the vhosts file, update both `server_name`entries
sudo vim /opt/bitnami/apps/wordpress/conf/nginx-vhosts.conf
```
Restart the server, so that the new vhost kicks in.

```shell
# @remote
sudo /opt/bitnami/ctlscript.sh restart
```

## 5. Update the SSL certificate
Since you are using a new domain, you will need to update the certificate.
Do the steps from the guide "Server Setup > SSL Certificates".

## 6. Update WP_HOME in the .env file
Open the `.env` file and update the `WP_HOME` entry to the new HOST.
```shell
# @remote
# change to root
sudo su
# change to deploy
su - deploy
# open the .env file and replace the WP_HOME entry
vim /opt/bitnami/apps/wordpress/htdocs/current/.env
```

## 7. Search and replace the new HOST on the Wordpress database
Rearch and replace the database for entries pointing to the old HOST.
```shell
# @remote
# change to root
sudo su
# change to deploy
su - deploy
# cd to the wordpress installation
cd /opt/bitnami/apps/wordpress/htdocs/current
# run the "search replace" command
wp search-replace OLD_HOST NEW_HOST
```


# Managing Wordpress

## W3 Total Cache plugin
If you are using W3 Total Cache plugin, you will also need to set the var
`WP_CACHE=true` on the `.env` file. If you disable the plugin, set `WP_CACHE=`
(empty).

Usually the plugin does that by itself by directly modifying `wp-config.php`,
but since we hardened the permissions (done during the Capistrano deploy), the
plugin won't be able to do that.

### Restarting Ngnix
If you change the W3 Total Cache settings, it will likely require you to restart
Ngnix. To do that run:
```shell
# @localhost
bundle exec cap staging deploy:restart
```

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

# Deploying
## From local machine
### Requirements
* Ruby
    * https://bundler.io/ (require sudo, a bit painful to install)
* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

### 1. Push to git
The server will fetch the application from git, so make sure all changes are pushed!
### 2. Run Capistrano
```shell
# @localhost
bundle exec cap staging deploy
```
## From CI
Add key to appveyor https://www.appveyor.com/docs/how-to/private-git-sub-modules/

# Pushing and Pulling data (Databases and uploads)
You can easily push the local database and uploads to remote and vice versa
using Capistrano.

However be aware that it will use the database credentials in the `.env` file,
which probably will fail since those credentials are for connecting *inside*
docker, in the container managed by Lando. So, in order to be able to connect
to the local database, you will first need to figure out what is the port that
the database service is being *forwarded* to, then change the `DB_HOST` variable
in `.env` so that it points to that specific port and host. **You will always
need to make this check, as every time docker starts, a new port will be used.
Let this extra step be a warning to the fact that managing databases is risky
business!**

To figure out the port run the command, in the root of the project:
```shell
lando info
```
Then look for the `database` service, and find the `external_connection` host
and port. It should look something like this:
```
service: 'database',
external_connection: {
  host: 'localhost',
  port: 'XXXXX'
},
```
With the external host and port in hand, you will have to update the `.env` file
so that it matches it. So open the `.env` and make sure you have a line like
this:
```
DB_HOST=localhost:XXXXX
```
Ok, so now we can use Capistrano to sync the local and the remote server.

**ATTENTION! When you are finished with your syncing business, make sure to
return your `.env` file to it's original `DB_HOST=database`**

## Sync localhost to remote
### Database (with backup)
```shell
# @localhost
bundle exec cap staging wpcli:db:push
```
Backups will be saved to `config/backup`.
### Uploads:
```shell
# @localhost
bundle exec cap staging wpcli:uploads:rsync:push
```
## Sync remote to localhost
### Database
```shell
# @localhost
bundle exec cap staging wpcli:db:pull
```
### Uploads:
```shell
# @localhost
bundle exec cap staging wpcli:uploads:rsync:pull
```
## Backup remote database
```shell
# @localhost
bundle exec cap staging wpcli:db:backup:remote
```
Backups will be saved to `config/backup`.
