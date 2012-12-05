Desk.com client
===============

A php 5.3+ wrapper for the [Desk.com API](http://dev.desk.com/)

[![Build Status](https://travis-ci.org/99designs/php-desk.png)](https://travis-ci.org/99designs/php-desk)

Installation
------------

To add php-desk to a project, the easiest way is via [composer](http://getcomposer.com):

```json
{
    "require": {
        "99designs/php-desk": "dev-master"
    }
}
```

Usage
-----

Before you use the API, you need an access token and other credentials. Your token can be found under your client application's details in `Admin — General Settings — API Applications`.

```php
<?php

$desk = new \Desk(
  YOUR_DESK_SUBDOMAIN,
  YOUR_CONSUMER_KEY,
  YOUR_CONSUMER_SECRET,
  YOUR_OAUTH_TOKEN,
  YOUR_OAUTH_TOKEN_SECRET
);

// TODO: describe API calls here

```

Copyright
---------

Copyright (c) 2012 99designs See [LICENSE](https://github.com/99designs/php-desk/blob/master/LICENSE) for details.

