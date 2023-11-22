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


3) Run database migration

```
php bin/console doctrine:migration:migrate

or using symfony cli

symfony console doctrine:migration:migrate
```


4) Start local server

```
symfony server:start
```


5) Run the worker to process the message queues

```
php bin/console messenger:consume async
```


6) Open browser

```
symfony open:local
```