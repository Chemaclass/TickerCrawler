# StockTicker

[![Build Status](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Chemaclass/StockTicker/?branch=master)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg?style=flat-square)](https://php.net/)

This is an API to get a report/notification (via email and/or slack) of the latest news related to the 
Tinker (the Stock Symbol) that you are interested in based on a personal lookup that you can define yourself.

## Examples

You can see some real working examples in this [readme](example/README.md).

## Installation

A) Includes Docker + Composer dependencies: plug & play!

```bash
curl -sS https://raw.githubusercontent.com/Chemaclass/StockTicker/master/installation.sh > installation.sh \
  && bash installation.sh
```

B) As composer dependency:

```bash
composer require chemaclass/stock-ticker dev-master
```

## Contribute

### Set up the project

Set up the container and install the composer dependencies:

```bash
docker-compose up -d
docker-compose exec stock_ticker composer install

docker-compose exec stock_ticker example/crawl.php
docker-compose exec stock_ticker example/notify.php
```

You can go even go inside the docker container:

```bash
docker exec -ti -u dev stock_ticker bash
```

----------

More info about this scaffolding -> https://github.com/Chemaclass/PhpScaffolding
