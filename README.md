<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## API Auth (User Biasa)

Base URL: `/api/v1`

### 1. Register (email/password)

- **POST** `/auth/register`
- Body JSON:

```json
{
  "name": "Nama User",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 2. Login (email/password)

- **POST** `/auth/login`
- Body JSON:

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

### 3. Login SSO Google (ID Token)

- **POST** `/auth/google`
- Body JSON:

```json
{
  "id_token": "GOOGLE_ID_TOKEN_FROM_CLIENT"
}
```

### 4. Cek Profil Login

- **GET** `/auth/me`
- Header:
  - `Authorization: Bearer {token}`

### 5. Forgot Password

- **POST** `/auth/forgot-password`
- Body JSON:

```json
{
  "email": "user@example.com"
}
```

### 6. Reset Password

- **POST** `/auth/reset-password`
- Body JSON:

```json
{
  "token": "RESET_TOKEN_DARI_EMAIL",
  "email": "user@example.com",
  "password": "passwordBaru123",
  "password_confirmation": "passwordBaru123"
}
```

### 7. Logout

- **POST** `/auth/logout`
- Header:
  - `Authorization: Bearer {token}`

### Format Response Sukses

```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "plain_api_token",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Nama User",
      "email": "user@example.com",
      "avatar": null,
      "email_verified_at": null,
      "is_frozen": false,
      "created_at": "2026-03-02T10:00:00.000000Z"
    }
  }
}
```

### Environment untuk Google

Set di `.env`:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
