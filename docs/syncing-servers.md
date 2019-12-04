# Syncing servers (database and uploads)
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
