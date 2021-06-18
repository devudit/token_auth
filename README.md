# Token Auth

This is a simple module for understanding authentication providers in drupal.

**This module will do the following things.**

* Add a token filed to user entity which automatically generated.
* Add a token_auth authentication provider which we can apply to routes using module.routing.yml and the REST resource using backend interface.
* Define a REST Resource for exposing a node by node id (for testing the token_auth authentication provider).

## Screenshots
#### Backend Config.
![Backend Config](https://raw.githubusercontent.com/devudit/token_auth/main/assets/images/screenshot#1.png)
#### Rest End Point & Exposing content through endpoint.
![Rest End Point & Exposing content through endpoint](https://raw.githubusercontent.com/devudit/token_auth/main/assets/images/screenshot#2.png)
