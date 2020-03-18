# Learning CMS

This repository contains the source code of the Learning CMS. The CMS itself is a WordPress installation. It provides data to the clients via WordPress's built-in REST API or via a GraphQL endpoint.

## Local environment

```
$ docker swarm init
$ docker stack deploy -c stack.yml wordpress
```

To tear down:
```
$ docker stack rm wordpress
$ docker system prune
```
