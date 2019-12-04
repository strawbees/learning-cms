# Creating stages
If you want to "fork" the existing site in order to create a new stage to test
new features (or whatever you want), you can do so by creating a new Lightsail
instance based on a snapshot of the site. Once the new instance is in place you
will need to attach it to a static IP (that you can then point to from a custom
domain), and then replace all references of the old host, to the new one.

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
