# plateformekartoukloud

Epitech Berlin Capstone Project 2018-2019


## Process to deploy the project

```bash
$ composer install -vvv
$ ./scrit/genDb
```

## KartoVmJson

You can feed the db with some kartoVmJson files. Place them in `./jsonData/a_dir`. Then run `sf app:kartokloud:update`.

## Front dep

Use of [node](https://nodejs.org/en/download/) and [yarn](http://symfony.com/doc/current/frontend/encore/installation.html) for the front.

Front source files are located in the `asset/` sub directories.

Run `yarn install` to update the fron dep.

Run `./node_modules/.bin/encore dev --watch` to run the watch command.

## TODO

[Todo](./TODO)
