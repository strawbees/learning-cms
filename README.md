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

## 2. Copy the Bitnami password (used for MySQL)
Since the Bitnami Ngnix image already has MySQL built in, you will need to grab
the password, to use on the remote environment variables in order to connect to
the database. To access the password, SSH into the server and type:
```shell
cat bitnami_application_password
```
Keep that password handy, you will need it later!

## 3. Setup Authentication & Authorisation
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
# switch to the deploy user
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

## 4. Prepare files on the server
In the following instructions, we are assuming that the name of our app is
`learning-cms`. We are also assuming the app domain will be
`learning-cms-stage.strawbees.com` (in the `nginx-vhosts.conf`). If you are
using these instructions for another app, change it accordingly (do a "search
and replace")!

### 4.1- Ngnix config
Scaffold the app directories, with the correct permissions:
```shell
# @remote
# change to root
sudo su
mkdir /opt/bitnami/apps/learning-cms
mkdir /opt/bitnami/apps/learning-cms/htdocs/
mkdir /opt/bitnami/apps/learning-cms/conf
chown -R deploy:daemon /opt/bitnami/apps/learning-cms/htdocs/
chmod -R g+w /opt/bitnami/apps/learning-cms/htdocs/
```
Create the app's configuration files:
```shell
# @remote
# change to root
sudo su

# nginx-prefix.conf
echo '' > /opt/bitnami/apps/learning-cms/conf/nginx-prefix.conf

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
' > /opt/bitnami/apps/learning-cms/conf/nginx-app.conf

# nginx-vhosts.conf (https commented out for now)
echo 'server {
    listen    80;
    root "/opt/bitnami/apps/learning-cms/htdocs/current/web/";
    server_name  learning-cms-stage.strawbees.com;
    include "/opt/bitnami/apps/learning-cms/conf/nginx-app.conf";
}
#server {
#    listen    443 ssl;
#    root   "/opt/bitnami/apps/learning-cms/htdocs/current/web/";
#    server_name  learning-cms-stage.strawbees.com;
#    ssl_certificate  "/opt/bitnami/apps/learning-cms/conf/certs/server.crt";
#    ssl_certificate_key  "/opt/bitnami/apps/learning-cms/conf/certs/server.key";
#    ssl_session_cache    shared:SSL:1m;
#    ssl_session_timeout  5m;
#    ssl_ciphers  HIGH:!aNULL:!MD5;
#    ssl_prefer_server_ciphers  on;
#    include "/opt/bitnami/apps/learning-cms/conf/nginx-app.conf";
#}
' >  /opt/bitnami/apps/learning-cms/conf/nginx-vhosts.conf
```
Modify the existing "bitnami-apps" configs, so that they point to our newly
created app:
```shell
# @remote
# change to root
sudo su

# bitnami-apps-prefix.conf
echo 'include "/opt/bitnami/apps/learning-cms/conf/nginx-prefix.conf";' >> /opt/bitnami/nginx/conf/bitnami/bitnami-apps-prefix.conf

# bitnami-apps-vhosts.conf
echo 'include "/opt/bitnami/apps/learning-cms/conf/nginx-vhosts.conf";' >> /opt/bitnami/nginx/conf/bitnami/bitnami-apps-vhosts.conf
```
Restart Ngnix:
```shell
# @remote
sudo /opt/bitnami/ctlscript.sh restart nginx
```
### 4.2. Prepare for Capistrano
Since we already know we will use Capistrano for deployment, and which files it
needs, create the `.env` file that will be consumed by Wordpress. Note that you
will need the Bitnami Application password genreated at step 2. Also genereate
fresh salts at https://roots.io/salts
```shell
# @remote
# change user to deploy
su - deploy
mkdir /opt/bitnami/apps/learning-cms/htdocs/shared
echo 'DB_NAME=wordpress
DB_USER=root
# database password is the same as the Bitnami application password
DB_PASSWORD=BITNAMI_APPLICATION_PASSWORD
DB_PREFIX=wp_

WP_ENV=development
WP_HOME=http://learning-cms-stage.strawbees.com
WP_SITEURL=${WP_HOME}/wp

# generate at https://roots.io/salts.html
AUTH_KEY=GENERATE_ME
SECURE_AUTH_KEY=GENERATE_ME
LOGGED_IN_KEY=GENERATE_ME
NONCE_KEY=GENERATE_ME
AUTH_SALT=GENERATE_ME
SECURE_AUTH_SALT=GENERATE_ME
LOGGED_IN_SALT=GENERATE_ME
NONCE_SALT=GENERATE_ME
' > /opt/bitnami/apps/learning-cms/htdocs/shared/.env
```


## 5. Snapshot the instance!
You really don't want to have to redo all of the steps above, so this is a good
moment to take a snapshot of your instance, so you can rollback to this exact
point! Do you via the Lightsail dashboard.
# Local development

# Deploying
## Local machine requirements
* Ruby
    * https://bundler.io/ (require sudo, a bit painful to install)
* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
