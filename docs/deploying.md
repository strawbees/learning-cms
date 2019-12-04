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
