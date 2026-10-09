# Changelog

All notable changes to `krayin/social-login` will be documented in this file.

## [2.1.3] - 2024-11-15
### Added
- Twitter/X OAuth 2.0 support
- `social_login_url()` helper function

### Fixed
- LinkedIn callback URL encoding issue on some server configs
- Session handling for stateless API routes

## [2.1.0] - 2024-09-20
### Added
- Laravel 12.x compatibility
- Blade components (`<x-social-login::buttons />`)
- Configurable button styles

### Changed
- Minimum PHP version bumped to 8.1

## [2.0.0] - 2024-06-01
### Added
- Initial release
- Google, GitHub, LinkedIn OAuth support
- Auto user registration
- Role-based access configuration
- Krayin CRM 2.x compatibility
