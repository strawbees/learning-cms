# Learning CMS

This repository contains the source code of the Strawbees Learning CMS. The CMS itself is a WordPress installation. It provides data to the clients via WordPress's via a GraphQL endpoint (or built-in REST API).

**No front end will be rendered from this CMS, this is a strictly headless WordPress setup**.

## Installed Plugins

Make sure these plugins (`wp/wp-content/plugins`) are activated:

- [`amazon-s3-and-cloudfront`](https://github.com/deliciousbrains/wp-amazon-s3-and-cloudfront)
- [`wp-graphql`](https://www.wpgraphql.com)
- [`wp-graphql-gutenberg`](https://github.com/pristas-peter/wp-graphql-gutenberg)
- [`wp-graphiql`](https://github.com/wp-graphql/wp-graphiql)

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
