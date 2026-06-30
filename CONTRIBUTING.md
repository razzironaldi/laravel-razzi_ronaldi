# Contributing Guide

## Setup Project

composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate

## Branch

Gunakan branch:
- main
- develop
- feature/*

Aturan:
- main untuk versi final
- develop untuk gabungan fitur
- feature/* untuk pengerjaan fitur

## Commit Message

Contoh:
feat: create product module
test: add product feature test
fix: resolve validation error
docs: update API documentation
ci: add GitHub Actions workflow

## Pull Request

Setiap fitur masuk ke develop melalui Pull Request.

## Quality Check

Sebelum Pull Request, jalankan:

php artisan test
vendor/bin/pint --test
vendor/bin/phpstan analyse --memory-limit=1G