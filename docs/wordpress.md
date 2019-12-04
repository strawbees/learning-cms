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
