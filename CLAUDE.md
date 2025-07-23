# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

SuiteCRM is an open-source Customer Relationship Management (CRM) system, a fork of SugarCRM Community Edition. This is version 7.14.6 with a PHP-based architecture using an MVC pattern.

## Development Commands

### Composer Commands
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Optimize autoloader
composer dump-autoload -o
```

### Testing Commands
```bash
# Run all tests
vendor/bin/codecept run

# Run specific test suite
vendor/bin/codecept run unit
vendor/bin/codecept run acceptance
vendor/bin/codecept run api
vendor/bin/codecept run install

# Run with coverage
vendor/bin/codecept run --coverage --coverage-html

# Run a specific test
vendor/bin/codecept run tests/unit/YourTestCest.php
```

### Code Quality Tools
```bash
# PHP CS Fixer
vendor/bin/php-cs-fixer fix

# PHPStan static analysis
vendor/bin/phpstan analyse

# Run Rector for code upgrades
vendor/bin/rector process
```

### Robo Tasks
```bash
# Robo is available for task automation
vendor/bin/robo
```

## High-Level Architecture

### Core Structure

1. **MVC Pattern**: The application follows a Model-View-Controller pattern
   - Entry point: `index.php` → `include/MVC/SugarApplication.php`
   - Controllers: Located in `modules/*/controller.php` and managed by `ControllerFactory`
   - Views: Handled by `ViewFactory` and templates use Smarty 4
   - Models: SugarBeans in `data/SugarBean.php` and module-specific beans

2. **Module System**: Each functional area is a module in `modules/`
   - Core modules: Accounts, Contacts, Leads, Opportunities, Cases, etc.
   - Each module has its own MVC structure, metadata, and language files
   - Module beans extend `SugarBean` for database operations

3. **Database Layer**:
   - Abstraction through `DBManager` classes supporting MySQL, MSSQL, etc.
   - Relationship handling via `Link` and `Link2` classes
   - Metadata-driven field definitions in `vardefs.php`

4. **API Architecture**:
   - REST API v8 in `Api/V8/` using Slim Framework
   - Legacy SOAP services in `service/` and `soap/`
   - OAuth2 authentication support

5. **Security**:
   - ACL system for role-based permissions
   - Security Groups for team-based access control
   - CSRF protection and input sanitization

6. **Key Libraries**:
   - Smarty 4 for templating
   - HTMLPurifier for XSS prevention
   - PHPMailer for email handling
   - TCPDF for PDF generation
   - Elasticsearch for search functionality
   - Monolog for logging

### Important Patterns

1. **Entry Points**: Most requests go through `index.php`, but special entry points exist:
   - `json_server.php` for AJAX requests
   - `soap.php` for SOAP services
   - `cron.php` for scheduled tasks

2. **Customization Layer**: 
   - Customizations go in `custom/` directory
   - Extension framework in `custom/Extension/`
   - Module customizations mirror the module structure

3. **Dependency Injection**: Uses PSR-4 autoloading with namespaces:
   - `SuiteCRM\` for core library code
   - `SuiteCRM\Modules\` for module code
   - `Api\` for API code

4. **Configuration**:
   - Main config in `config.php` (generated during installation)
   - Module metadata in `modules/*/metadata/`
   - Field definitions in `modules/*/vardefs.php`

## Development Guidelines

1. **File Modifications**: Always check for existing customization patterns in the `custom/` directory before modifying core files

2. **Database Changes**: Use the Extension framework for vardefs modifications rather than direct database alterations

3. **Testing**: Write tests for new functionality, following existing patterns in `tests/`

4. **Module Development**: New modules should follow the existing module structure and extend appropriate base classes

5. **API Development**: New API endpoints should be added to the V8 API following RESTful principles

## Key Files and Directories

- `index.php` - Main entry point
- `include/` - Core framework code
- `modules/` - All CRM modules
- `custom/` - Customizations and extensions
- `Api/` - API implementations
- `data/` - Database abstraction layer
- `metadata/` - Relationship and field metadata
- `themes/` - UI themes
- `vendor/` - Composer dependencies
- `config.php` - Main configuration (generated)