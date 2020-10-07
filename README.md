# Laravel-Vue Mentalstack Test Project

## Features

- Laravel 7
- Vue + VueRouter + Vuex + Vuetify
- Admin/client segregation with auth checking

Todo:
- Pages with dynamic import
- Register, email verification and password reset + personal cabinet
- Authentication with JWT
- Socialite integration
- Adaptive markup

## Installation

- Clone this repo
- Edit `.env` and set your database connection details
- (When installed via git clone or download, run `php artisan key:generate`)
- `php artisan migrate`
- `npm install`

## Usage

#### Development

```bash
# build and watch
npm run watch - for client bundle build 
npm run admin-watch - for admin bundle build 
```

#### Production

```bash
npm run prod admin-prod
```
