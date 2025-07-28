# Development & Testing

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.travis.yml](.travis.yml)
- [README.md](README.md)
- [composer.json](composer.json)
- [composer.lock](composer.lock)
- [files.md5](files.md5)
- [include/utils.php](include/utils.php)
- [modules/Import/tpls/last.tpl](modules/Import/tpls/last.tpl)
- [modules/Import/tpls/listview.tpl](modules/Import/tpls/listview.tpl)
- [php_version.php](php_version.php)
- [suitecrm_version.php](suitecrm_version.php)
- [tests/SuiteCRM/Test/Driver/PhpBrowserDriver.php](tests/SuiteCRM/Test/Driver/PhpBrowserDriver.php)
- [tests/SuiteCRM/Test/Driver/WebDriver.php](tests/SuiteCRM/Test/Driver/WebDriver.php)
- [tests/_envs/travis-ci-hub.yml](tests/_envs/travis-ci-hub.yml)
- [tests/_support/AcceptanceTester.php](tests/_support/AcceptanceTester.php)
- [themes/SuiteP/css/Dawn/style.css](themes/SuiteP/css/Dawn/style.css)
- [themes/SuiteP/css/Dawn/variables.scss](themes/SuiteP/css/Dawn/variables.scss)
- [themes/SuiteP/css/Day/style.css](themes/SuiteP/css/Day/style.css)
- [themes/SuiteP/css/Day/variables.scss](themes/SuiteP/css/Day/variables.scss)
- [themes/SuiteP/css/Dusk/style.css](themes/SuiteP/css/Dusk/style.css)
- [themes/SuiteP/css/Dusk/variables.scss](themes/SuiteP/css/Dusk/variables.scss)
- [themes/SuiteP/css/Night/style.css](themes/SuiteP/css/Night/style.css)
- [themes/SuiteP/css/Night/variables.scss](themes/SuiteP/css/Night/variables.scss)
- [themes/SuiteP/css/suitep-base/editview.scss](themes/SuiteP/css/suitep-base/editview.scss)
- [themes/SuiteP/css/suitep-base/listview.scss](themes/SuiteP/css/suitep-base/listview.scss)
- [themes/SuiteP/css/suitep-base/navbar.scss](themes/SuiteP/css/suitep-base/navbar.scss)
- [travis.php.ini](travis.php.ini)

</details>



This section covers the development infrastructure, testing frameworks, and continuous integration systems that support SuiteCRM development. This includes automated testing suites, CI/CD pipelines, build tools, and development workflow processes.

For information about the API testing framework, see [API Architecture](#6.2). For details about installation and system configuration, see [Installation System](#5.1).

## CI/CD Pipeline Architecture

SuiteCRM uses Travis CI for continuous integration with a comprehensive pipeline that includes multiple test suites and validation stages.

```mermaid
flowchart TD
    GIT["Git Push/PR"] --> TRAVIS["Travis CI Pipeline"]
    
    TRAVIS --> MATRIX["Build Matrix"]
    MATRIX --> PHP74["PHP 7.4 / MySQL 5.7"]
    MATRIX --> COMPOSER_VALIDATE["composer validate"]
    MATRIX --> COVERAGE["code coverage"]
    
    PHP74 --> SETUP["Environment Setup"]
    SETUP --> CHROME["Chrome Driver Setup"]
    SETUP --> MYSQL_SETUP["MySQL Database Setup"]
    SETUP --> APACHE["Apache/PHP-FPM Setup"]
    
    MYSQL_SETUP --> DB_CREATE["automated_tests DB"]
    APACHE --> COMPOSER_INSTALL["composer install"]
    COMPOSER_INSTALL --> CODECEPT_BUILD["codecept build"]
    
    CODECEPT_BUILD --> INSTALL_TEST["Installation Wizard Test"]
    INSTALL_TEST --> UNIT_TESTS["PHPUnit Tests"]
    UNIT_TESTS --> API_DATA["OAuth2 Demo Data"]
    API_DATA --> API_TESTS["API Functional Tests"]
    API_TESTS --> ACCEPTANCE["Acceptance Tests"]
    
    COVERAGE --> CODECOV["Codecov Upload"]
    ACCEPTANCE --> LOGS["Error Log Collection"]
```

**Travis CI Configuration Structure**

Sources: [.travis.yml:1-115]()

## Testing Framework Architecture

SuiteCRM uses Codeception as the primary testing framework with custom drivers and helper classes for web application testing.

```mermaid
classDiagram
    class AcceptanceTester {
        +login(username, password)
        +loginAsAdmin()
        +logout()
        +dontSeeMissingLabels()
        +dontSeeErrors()
        +navigateToPage(module, action, record)
        +getFaker()
    }
    
    class WebDriver {
        +getInstanceURL()
        +getAdminUser() 
        +getAdminPassword()
        +waitForElementVisible()
        +waitForElementNotVisible()
        +fillField()
        +click()
    }
    
    class PhpBrowserDriver {
        +browserstack configurations
        +local testing setup
    }
    
    class TravisEnvironment {
        +url: "http://localhost/"
        +port: 9515
        +browser: chrome
        +chromeOptions: headless
    }
    
    AcceptanceTester --> WebDriver : uses
    AcceptanceTester --> PhpBrowserDriver : alternative
    WebDriver --> TravisEnvironment : configured by
    
    class CodeceptionConfig {
        +modules: enabled
        +environments: travis-ci-hub
        +support: _generated
    }
```

**Test Infrastructure Components**

Sources: [tests/_support/AcceptanceTester.php:1-113](), [tests/SuiteCRM/Test/Driver/WebDriver.php:1-67](), [tests/_envs/travis-ci-hub.yml:1-18]()

## Build System and Development Tools

The development workflow is managed through Composer for dependency management and Robo for build automation.

```mermaid
flowchart LR
    subgraph "Development Dependencies"
        CODECEPTION["codeception/codeception ^4.1"]
        PHPUNIT["phpunit/phpunit ^9.5"]
        FAKER["fakerphp/faker ^1.14"]
        MOCKERY["mockery/mockery ^1.1.0"]
        PHPSTAN["phpstan/phpstan ^1.10"]
        ROBO["consolidation/robo ^3.0"]
    end
    
    subgraph "Production Dependencies"
        ELASTICSEARCH["elasticsearch/elasticsearch ^7.13"]
        SLIM["slim/slim ^3.8"]
        PHPMAILER["phpmailer/phpmailer ^6.0"]
        SMARTY["smarty/smarty ^4"]
    end
    
    subgraph "Build Tools"
        COMPOSER_JSON["composer.json"]
        ROBO_FILE["RoboFile.php"]
        TRAVIS_CONFIG[".travis.yml"]
    end
    
    COMPOSER_JSON --> CODECEPTION
    COMPOSER_JSON --> PHPUNIT
    COMPOSER_JSON --> ELASTICSEARCH
    COMPOSER_JSON --> SLIM
    
    ROBO_FILE --> BUILD_TASKS["Build Automation Tasks"]
    TRAVIS_CONFIG --> CI_PIPELINE["Continuous Integration"]
    
    BUILD_TASKS --> CODE_COVERAGE["Code Coverage Reports"]
    CI_PIPELINE --> AUTOMATED_TESTING["Automated Test Execution"]
```

**Dependency and Build Configuration**

Sources: [composer.json:1-153](), [RoboFile.php:1](), [.travis.yml:69-71]()

## Test Environment Configuration

The testing infrastructure supports multiple environments with specific configurations for different testing scenarios.

| Environment | Purpose | Configuration |
|-------------|---------|---------------|
| `travis-ci-hub` | CI/CD Pipeline | Headless Chrome, localhost URL, port 9515 |
| Local Development | Developer Testing | Custom WebDriver configurations |
| BrowserStack | Cross-browser Testing | Remote browser automation |

**Travis CI Environment Setup**

The CI environment includes comprehensive setup for web application testing:

- **Database Setup**: Creates `automated_tests` database with UTF8MB4 collation
- **Web Server**: Apache with PHP-FPM and mod_rewrite enabled
- **Browser Testing**: Headless Chrome with ChromeDriver on port 9515
- **Test Data**: OAuth2 demo data and test users loaded from SQL files

Sources: [.travis.yml:48-71](), [tests/_envs/travis-ci-hub.yml:1-18]()

## Testing Workflow Process

```mermaid
sequenceDiagram
    participant DEV as "Developer"
    participant GIT as "Git Repository"
    participant TRAVIS as "Travis CI"
    participant CHROME as "Chrome Driver"
    participant MYSQL as "MySQL Database"
    participant CODECEPT as "Codeception"
    
    DEV->>GIT: Push code changes
    GIT->>TRAVIS: Trigger CI pipeline
    
    TRAVIS->>TRAVIS: PHP lint check on changed files
    TRAVIS->>MYSQL: Create automated_tests database
    TRAVIS->>CHROME: Start ChromeDriver on port 9515
    TRAVIS->>TRAVIS: Install composer dependencies
    
    TRAVIS->>CODECEPT: Build test suites
    CODECEPT->>TRAVIS: Run installation wizard tests
    CODECEPT->>TRAVIS: Execute PHPUnit tests
    
    TRAVIS->>MYSQL: Load OAuth2 demo data
    TRAVIS->>MYSQL: Load demo users
    
    CODECEPT->>CHROME: Run API functional tests
    CODECEPT->>CHROME: Run acceptance tests
    
    TRAVIS->>TRAVIS: Collect error logs
    TRAVIS->>TRAVIS: Upload coverage reports
```

**Testing Types and Coverage**

1. **Installation Tests**: Verify the setup wizard functionality
2. **Unit Tests**: PHPUnit-based component testing
3. **API Tests**: Functional testing of V8 API endpoints with OAuth2
4. **Acceptance Tests**: End-to-end browser automation tests
5. **Code Coverage**: Comprehensive coverage reporting with Codecov integration

Sources: [.travis.yml:73-98](), [tests/_support/AcceptanceTester.php:60-89]()

## Development Tools Integration

The development environment provides several tools for code quality and testing:

**Static Analysis Tools**
- `phpstan/phpstan` for static code analysis
- `friendsofphp/php-cs-fixer` for code style enforcement
- `rector/rector` for automated code refactoring

**Testing Utilities**
- `mikey179/vfsstream` for virtual filesystem testing
- `jeroendesloovere/vcard` for vCard handling tests
- `flow/jsonpath` for JSON data manipulation in tests

**Build and Automation**
- `consolidation/robo` provides task automation capabilities
- Composer scripts handle post-install tasks and dependency management
- Travis CI matrix builds ensure compatibility across different PHP versions

Sources: [composer.json:79-97](), [composer.json:130-134]()