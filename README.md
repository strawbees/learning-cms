# Learning CMS
This repository contains the source code of the Learning CMS application. It was
designed (or more importantly, *documented*) to run on a
[LEMP stack](https://aws.amazon.com/marketplace/pp/B00NPHKI3Y) provisioned by
[AWS Lightsail](https://lightsail.aws.amazon.com/).

The CMS itself is a WordPress installation. It provides data to the clients via
WordPress's built-in REST API or via a GraphQL endpoint.

All business logic is in the theme `learning-cms-theme` - assisted by a few
plugins, most notably [WPGraphQL](https://www.wpgraphql.com/) and
[ACFPro](https://www.advancedcustomfields.com/pro/).

The file structure is based on the
[Bedrock WordPress boilerplate](https://roots.io/bedrock/), that allows themes,
plugins and WordPress itself to be under control.

## Documentation
See the `docs` directory on the root of this repository.
