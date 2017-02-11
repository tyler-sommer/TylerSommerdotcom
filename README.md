Tyler Sommer dot com
====================

The blog engine that powers my personal website.

Uses [VeonikBlogBundle](https://github.com/veonik/VeonikBlogBundle) for most blogging capabilities.


Installation
------------

Clone the repository:

```bash
git clone https://github.com/tyler-sommer/TylerSommerdotcom.git
cd TylerSommerdotcom
```

Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and then customize it:

```bash
cp app/config/parameters.yml.dist app/config/parameters.yml
vi app/config/parameters.yml
```

Install dependencies using Composer:

```bash
composer install -o
```

Create the database and schema:

```bash
app/console doctrine:database:create
app/console doctrine:schema:create
```

Load fixture data:

```bash
app/console doctrine:fixtures:load
```


You're all set! Use the default credentials to login:

```
Username: admin
Password: password
```
