Tyler Sommer dot com
====================

My personal website and blog.


Installation
------------

Clone the repository:

```
git clone https://github.com/tyler-sommer/TylerSommerdotcom.git
```

Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and then customize it:

```
cp app/config/parameters.yml.dist app/config/parameters.yml
vi app/config/parameters.yml
```

Install dependencies using Composer:

```
composer install -o
```

Create the database and schema:

```
app/console doctrine:database:create
app/console doctrine:schema:create
```

Load fixture data:

```
app/console doctrine:fixtures:load
```


You're all set! Use the default credentials to login:

Username: admin
Password: password
