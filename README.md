# Overview

This is a test project for PDF Generator using DomPDf and Message Queue in Symfony 6.

# Project Setup Guide

## Prerequisites

 - PHP 8.1 or higher
 - MySQL 8.0 or higher
 - Nodejs
 - NPM
 - Symfony CLI 

## Setting Up the Project 

1) Install composer dependencies

```
composer install
```


2) Install frontend dependencies (alternatively yoo can use yarn) 

```
npm install
```



3) Copy .env.example to .env and setup the database connection url

```
cp .env.example .env
```


4) Run database migration

```
php bin/console doctrine:migration:migrate

or using symfony cli

symfony console doctrine:migration:migrate
```


5) Compile frontend

```
npm run watch

or

npm run dev

or

npm run build
```


6) Start local server

```
symfony server:start
```


7) Run the worker to process the message queues

```
php bin/console messenger:consume async
```


8) Open browser

```
symfony open:local
```