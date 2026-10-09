# Krayin Social Login

<p align="center">
    <a href="https://packagist.org/packages/krayin/social-login"><img src="https://img.shields.io/packagist/v/krayin/social-login.svg" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/krayin/social-login"><img src="https://img.shields.io/packagist/dt/krayin/social-login.svg" alt="Total Downloads"></a>
    <a href="https://github.com/ASD-Team-sec/krayin-social-login/actions"><img src="https://github.com/ASD-Team-sec/krayin-social-login/workflows/tests/badge.svg" alt="Tests"></a>
    <a href="https://packagist.org/packages/krayin/social-login"><img src="https://img.shields.io/packagist/l/krayin/social-login.svg" alt="License"></a>
</p>

Add **social authentication** to your [Krayin CRM](https://github.com/krayin/laravel-crm). Let your sales team log in with Google, GitHub, LinkedIn, or Twitter — no more password resets.

## Features

- 🔐 One-click social login (Google, GitHub, LinkedIn, Twitter)
- 👤 Auto-create user accounts on first login
- ⚙️ Configurable default roles for new users
- 🎨 Pre-built Blade components for login buttons
- 📱 Mobile-responsive OAuth flow
- 🔄 Link existing accounts to social providers

## Requirements

- PHP 8.1+
- Laravel 10.x / 11.x / 12.x
- Krayin CRM 2.x

## Installation

```bash
composer require krayin/social-login
```

The service provider will be auto-discovered. Publish the config:

```bash
php artisan vendor:publish --tag=social-login-config
```

## Configuration

Add your OAuth credentials to `.env`:

```env
# Google
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/social/google/callback

# GitHub
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=${APP_URL}/auth/social/github/callback

# LinkedIn
LINKEDIN_CLIENT_ID=your-linkedin-client-id
LINKEDIN_CLIENT_SECRET=your-linkedin-client-secret
LINKEDIN_REDIRECT_URI=${APP_URL}/auth/social/linkedin/callback
```

Then add the providers to `config/services.php`:

```php
'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT_URI'),
],

'github' => [
    'client_id'     => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect'      => env('GITHUB_REDIRECT_URI'),
],
```

## Usage

### Blade Component

Add social login buttons to your login view:

```blade
<x-social-login::buttons />
```

Or include specific providers:

```blade
<x-social-login::buttons :providers="['google', 'github']" />
```

### Programmatic Access

```php
use Krayin\SocialLogin\Facades\SocialLogin;

// Get configured providers
$providers = SocialLogin::getProviders();

// Generate redirect URL
$url = SocialLogin::getRedirectUrl('google');
```

## Routes

The package registers the following routes:

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/auth/social/{provider}/redirect` | Redirects to OAuth provider |
| GET | `/auth/social/{provider}/callback` | Handles OAuth callback |

## Customization

### Default Role

Set the default role for auto-created users in `config/social-login.php`:

```php
'default_role' => 'sales', // sales, admin, marketing
```

### Auto Registration

Disable auto-registration to only allow existing users:

```php
'auto_register' => false,
```

## Testing

```bash
composer test
```

## Changelog

### v2.1.3
- Added Twitter/X OAuth support
- Fixed LinkedIn callback URL encoding

### v2.1.0
- Laravel 12 support
- Added Blade components

### v2.0.0
- Initial release for Krayin CRM 2.x

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover a security vulnerability, please email security@krayincrm.com instead of opening an issue.

## License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.
