## About

This is a demo app showing how to setup Laravel, Sanctum and Vue

## Local Development

To build locally, use the following commands:

```bash
git clone git@github.com:shaunthornburgh/laravel-sanctum-vue-example.git
cd laravel-sanctum-vue-example
composer install
./vendor/bin/sail up -d
./vendor/bin/sail migrate --seed
./vendor/bin/sail npm run dev
```
