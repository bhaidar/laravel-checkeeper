# Contributing to Laravel Checkeeper

Thank you for considering contributing to Laravel Checkeeper! This document outlines the process for contributing to the project.

## Code of Conduct

Please be respectful and professional in all interactions. We want to foster an inclusive and welcoming community.

## How to Contribute

### Reporting Bugs

If you find a bug, please open an issue on GitHub with:
- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- Laravel and PHP versions
- Any error messages or stack traces

### Suggesting Features

Feature requests are welcome! Please open an issue with:
- A clear description of the feature
- Use cases and benefits
- Any potential implementation ideas

### Pull Requests

1. **Fork the repository** and create a new branch from `main`
2. **Write tests** for any new functionality
3. **Follow Laravel conventions** and PSR-12 coding standards
4. **Keep PRs focused** - one feature or fix per PR
5. **Update documentation** if needed
6. **Run tests** before submitting: `composer test`

#### Branch Naming

- `feature/description` - New features
- `fix/description` - Bug fixes
- `docs/description` - Documentation updates

#### Commit Messages

- Use clear, descriptive commit messages
- Reference issues when applicable: `Fixes #123`
- Follow conventional commits format when possible

### Development Setup

```bash
# Clone your fork
git clone git@github.com:your-username/laravel-checkeeper.git
cd laravel-checkeeper

# Install dependencies
composer install

# Run tests
composer test
```

### Testing

All code must be covered by tests. We use Pest for testing:

```bash
# Run all tests
vendor/bin/pest

# Run specific test file
vendor/bin/pest tests/Feature/CheckResourceTest.php

# Run with coverage (requires xdebug)
vendor/bin/pest --coverage
```

### Coding Standards

- Follow PSR-12 coding standards
- Use typed properties and return types
- Write expressive, self-documenting code
- Add docblocks for complex methods
- Keep methods focused and concise

### Documentation

- Update README.md for user-facing changes
- Update INTEGRATION.md for integration examples
- Add docblocks to new public methods
- Update CHANGELOG.md following Keep a Changelog format

## Questions?

If you have questions about contributing, feel free to open an issue for discussion.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
