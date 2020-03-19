# Learning CMS

This repository contains the source code of the Learning CMS. The CMS itself is a WordPress installation. It provides data to the clients via WordPress's built-in REST API or via a GraphQL endpoint.

## Local environment

```
$ docker swarm init
$ docker stack deploy -c stack.yml wordpress
```

(It takes a few seconds)

To tear down:
```
$ docker stack rm wordpress
$ docker system prune
```

If you are on a Linux machine, check [Portainer](https://www.portainer.io/installation/)
