# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

SuiteCRM is an open-source Customer Relationship Management (CRM) system, a fork of SugarCRM Community Edition. This is version 7.14.6 with a PHP-based architecture using an MVC pattern. It provides comprehensive functionality for managing customer relationships, including core business modules (e.g., Users, Emails, Campaigns, Contacts), advanced reporting, workflow automation, and integrations with external services like Elasticsearch and Google Calendar. The system is extensible through modules, customizations, and a robust API.

Key insights from documentation:
- Built on a layered architecture: Presentation (themes/templates), Application (MVC), Business Logic (SugarBean ORM), and Data (database abstraction).
- Supports responsive UI via the SuiteP theme with variants (Day, Dawn, Dusk, Night).
- Includes features like inline editing, advanced search, campaign management, and OAuth2-based API.

## Docker Development Environment

For development, use the Docker setup defined in `docker-compose.yml` instead of direct installations on your host machine. This provides a consistent environment with PHP 7.4, Apache, MySQL 8.0, and phpMyAdmin. The setup mounts the project directory into the container for live code changes.

### Setup Instructions
1. Ensure Docker and Docker Compose are installed.
2. Run `docker-compose up -d` to start the services.
3. Access SuiteCRM at `http://localhost:8080` (initial installation may be required via the web interface).
4. Access phpMyAdmin at `http://localhost:8081` (login: suitecrm/suitecrm).

### Running Commands in Docker
Most development commands (e.g., Composer, testing) should be executed inside the `suitecrm-web` container using `docker-compose exec`. Example:
```bash
docker-compose exec web composer install
```

To access a shell in the container:
```bash
docker-compose exec web bash
```

Stop the services with `docker-compose down`.

Note: The web container automatically installs PHP extensions and Composer on startup. For testing that requires a browser (e.g., acceptance tests), you may need to configure Codeception to use the Docker host's URL (`http://host.docker.internal:8080` from inside the container, or adjust accordingly).

## Development Commands

Use the Docker prefix for all commands (e.g., `docker-compose exec web <command>`).

### Composer Commands
```bash
# Install dependencies
docker-compose exec web composer install

# Update dependencies
docker-compose exec web composer update

# Optimize autoloader
docker-compose exec web composer dump-autoload -o
```

### Testing Commands
```bash
# Run all tests
docker-compose exec web vendor/bin/codecept run

# Run specific test suite
docker-compose exec web vendor/bin/codecept run unit
docker-compose exec web vendor/bin/codecept run acceptance
docker-compose exec web vendor/bin/codecept run api
docker-compose exec web vendor/bin/codecept run install

# Run with coverage
docker-compose exec web vendor/bin/codecept run --coverage --coverage-html

# Run a specific test
docker-compose exec web vendor/bin/codecept run tests/unit/YourTestCest.php
```

### Code Quality Tools
```bash
# PHP CS Fixer
docker-compose exec web vendor/bin/php-cs-fixer fix

# PHPStan static analysis
docker-compose exec web vendor/bin/phpstan analyse

# Run Rector for code upgrades
docker-compose exec web vendor/bin/rector process
```

### Robo Tasks
```bash
# Robo is available for task automation
docker-compose exec web vendor/bin/robo
```

### Additional Commands from Documentation
```bash
# Run Elasticsearch-specific commands (for integration testing; ensure Elasticsearch is running externally or add to docker-compose)
docker-compose exec web vendor/bin/robo elastic:index
docker-compose exec web vendor/bin/robo elastic:search "query"
docker-compose exec web vendor/bin/robo elastic:rm-index

# Optimize for development (clear cache, enable debug mode)
# Adjust config.php: 'debug' => true; (edit via phpMyAdmin or container shell)
```

## High-Level Architecture

### Core Structure

1. **MVC Pattern**: The application follows a Model-View-Controller pattern
   - Entry point: `index.php` → `include/MVC/SugarApplication.php` (manages request lifecycle, authentication, ACL filtering, theme/language loading).
   - Controllers: Located in `modules/*/controller.php` and managed by `ControllerFactory`. Handle business logic and delegate to views.
   - Views: Handled by `ViewFactory` and templates use Smarty 4 (wrapped in `Sugar_Smarty`). Support types like ViewDetail, ViewEdit, ViewList.
   - Models: SugarBeans in `data/SugarBean.php` and module-specific beans (extend SugarBean for ORM, relationships, CRUD operations).

2. **Module System**: Each functional area is a module in `modules/`
   - Core modules: Accounts, Contacts, Leads, Opportunities, Cases, Emails, Campaigns, Users, Reports (AOR_Reports), etc.
   - Each module has its own MVC structure, metadata (e.g., vardefs.php for fields, searchdefs.php for search forms), and language files.
   - Module beans extend `SugarBean` for database operations, with templates like Basic, Person, Company for inheritance.

3. **Database Layer**:
   - Abstraction through `DBManager` classes supporting MySQL, MSSQL, etc.
   - Relationship handling via `Link` and `Link2` classes (one-to-many, many-to-many support).
   - Metadata-driven field definitions in `vardefs.php` (includes custom fields via DynamicField).
   - Hooks for lifecycle events (before_save, after_save, etc.).

4. **API Architecture**:
   - REST API v8 in `Api/V8/` using Slim Framework (JSON API compliant, OAuth2 authentication).
   - Legacy SOAP services in `service/` and `soap/`.
   - V8 endpoints for modules, users, metadata, relationships.

5. **Security**:
   - ACL system for role-based permissions.
   - Security Groups for team-based access control.
   - CSRF protection and input sanitization.
   - User authentication with two-factor support.
   - Email opt-out and privacy compliance features.

6. **Key Libraries** (Expanded from Documentation):
   - Smarty 4 for templating (with Sugar_Smarty wrapper).
   - HTMLPurifier for XSS prevention.
   - PHPMailer for email handling (outbound with SMTP/OAuth).
   - TCPDF for PDF generation in reports.
   - Elasticsearch for advanced search and indexing.
   - Monolog for logging.
   - Google Client Library for Calendar integration (OAuth2-based sync).
   - RGraph/Chart.js/pChart for report visualizations.

7. **Additional Insights from Documentation**:
   - **User Management**: Handles authentication, preferences, and email integration (e.g., SugarEmailAddress for multi-address support).
   - **Email System**: Comprehensive inbound/outbound processing with IMAP/POP3 support and queue management via EmailMan.
   - **Campaign System**: Manages email marketing with target lists, templates, and tracking.
   - **Reporting**: AOR_Reports for custom reports, charts, and exports (CSV/PDF).
   - **Integrations**: Elasticsearch for search, Google Calendar for sync, external OAuth for emails.

### Important Patterns

1. **Entry Points**: Most requests go through `index.php`, but special entry points exist:
   - `json_server.php` for AJAX requests.
   - `soap.php` for SOAP services.
   - `cron.php` for scheduled tasks (e.g., pollMonitoredInboxes, runElasticSearchIndexerScheduler).
   - `Api/index.php` for REST API v8.

2. **Customization Layer**: 
   - Customizations go in `custom/` directory.
   - Extension framework in `custom/Extension/` (e.g., for vardefs, language packs).
   - Module customizations mirror the module structure (e.g., `custom/modules/Accounts/`).
   - Use installer hooks for custom installation logic.

3. **Dependency Injection**: Uses PSR-4 autoloading with namespaces:
   - `SuiteCRM\` for core library code (e.g., Search, Utility).
   - `SuiteCRM\Modules\` for module code.
   - `Api\` for API code (V8 uses Slim container for DI).

4. **Configuration**:
   - Main config in `config.php` (generated during installation, use Administration Panel for updates).
   - Module metadata in `modules/*/metadata/` (e.g., listviewdefs.php, detailviewdefs.php).
   - Field definitions in `modules/*/vardefs.php`.
   - User preferences stored in `user_preferences` table (via UserPreference class).
   - Global config via `$sugar_config` array (e.g., search settings, email configs).

5. **Additional Patterns from Documentation**:
   - **Logic Hooks**: Extend functionality at lifecycle points (e.g., before_save, after_save in SugarBean).
   - **Template Inheritance**: Views and templates support custom overrides in `custom/themes/`.
   - **Scheduled Tasks**: Use Scheduler module for cron jobs (e.g., email polling, Elasticsearch indexing).
   - **Responsive UI**: SuiteP theme handles breakpoints (x-small: 750px, small: 768px, etc.).
   - **Testing**: Integrates Codeception with custom drivers (WebDriver for acceptance tests).

## Development Guidelines

1. **File Modifications**: Always check for existing customization patterns in the `custom/` directory before modifying core files.

2. **Database Changes**: Use the Extension framework for vardefs modifications rather than direct database alterations. Avoid direct SQL; use SugarBean methods.

3. **Testing**: Write tests for new functionality, following existing patterns in `tests/`. Use AcceptanceTester helpers (e.g., loginAsAdmin(), dontSeeErrors()). Run tests inside the Docker container.

4. **Module Development**: New modules should follow the existing module structure and extend appropriate base classes (e.g., SugarBean). Add metadata files and language packs.

5. **API Development**: New API endpoints should be added to the V8 API following RESTful principles (use JsonApi responses, OAuth2 auth).

6. **Additional Guidelines from Documentation**:
   - Use inline editing patterns for UI (see inlineEditing.js and SugarWidget for subpanels).
   - For reports/charts, extend AOR_Reports and integrate with RGraph/Chart.js.
   - Ensure theme compatibility (test with SuiteP variants).
   - Follow PSR standards for new code; use PHPStan for analysis.

## Key Files and Directories

- `index.php` - Main entry point.
- `include/` - Core framework code (MVC, SugarBean, utilities).
- `modules/` - All CRM modules (e.g., Users, Emails, Campaigns, AOR_Reports).
- `custom/` - Customizations and extensions.
- `Api/` - REST API implementations (V8 with Slim).
- `data/` - Database abstraction layer (SugarBean.php).
- `metadata/` - Relationship and field metadata (e.g., email_addressesMetaData.php).
- `themes/` - UI themes (SuiteP primary with CSS/JS/templates).
- `vendor/` - Composer dependencies.
- `config.php` - Main configuration (generated).
- `tests/` - Testing suites (unit, acceptance, API).
- `lib/` - Core libraries (e.g., Search/ElasticSearch, Utility/BeanJsonSerializer).
