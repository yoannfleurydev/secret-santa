# Secret Santa

Secret Santa is an application that you can host and run on you server to
do a secret santa with your family and friends.

This application is build on Silex, a micro-framework based on the awesome
framework Symfony. Check the
[composer.json](https://github.com/yoannfleurydev/secret-santa/blob/master/composer.json)
file to see all the dependencies.

## Requirements

* php
* php-zip (for composer)

## Installation

* Clone the repository, and run it using a web server with PHP.
* Run the SQL script located in the `db` directory. For this, you'll first need
  to create the database.

```shell
mysql -u <user> -p <password> <database> < db/secret_santa.sql
```