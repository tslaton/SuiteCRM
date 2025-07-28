# Testing & CI/CD

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.travis.yml](.travis.yml)
- [php_version.php](php_version.php)
- [tests/SuiteCRM/Test/Driver/PhpBrowserDriver.php](tests/SuiteCRM/Test/Driver/PhpBrowserDriver.php)
- [tests/SuiteCRM/Test/Driver/WebDriver.php](tests/SuiteCRM/Test/Driver/WebDriver.php)
- [tests/_envs/travis-ci-hub.yml](tests/_envs/travis-ci-hub.yml)
- [tests/_support/AcceptanceTester.php](tests/_support/AcceptanceTester.php)
- [travis.php.ini](travis.php.ini)

</details>



## Purpose and Scope

This document covers SuiteCRM's automated testing infrastructure and continuous integration/continuous deployment (CI/CD) processes. It explains the Travis CI configuration, Codeception testing framework, test execution pipeline, and code coverage reporting system. 

For information about the installation system that is tested as part of the CI pipeline, see [Installation System](#5.1). For details about the API architecture that includes API testing, see [API Architecture](#6.2).

## CI/CD Pipeline Architecture

SuiteCRM uses Travis CI as its primary continuous integration platform, orchestrating multiple test suites and validation steps across different environments.

### Travis CI Pipeline Flow

```mermaid
flowchart TD
    trigger["GitHub Push/PR"] --> matrix["Travis CI Matrix"]
    
    matrix --> php74["PHP 7.4 / MySQL 5.7"]
    matrix --> composer["Composer Validate"]
    matrix --> coverage["Code Coverage"]
    
    php74 --> setup_env["Environment Setup"]
    setup_env --> mysql_setup["MySQL Database Setup"]
    setup_env --> chrome_setup["Chrome Driver Installation"]
    setup_env --> apache_setup["Apache Configuration"]
    
    mysql_setup --> install_test["Installation Tests"]
    chrome_setup --> install_test
    apache_setup --> install_test
    
    install_test --> unit_tests["PHPUnit Tests"]
    unit_tests --> oauth_setup["OAuth2 Demo Data"]
    oauth_setup --> api_tests["API Functional Tests"]
    api_tests --> acceptance_tests["Acceptance Tests"]
    
    composer --> composer_install["composer install"]
    composer_install --> composer_validate["composer validate"]
    
    coverage --> codecept_install["Codeception Install Tests"]
    codecept_install --> robo_coverage["Robo Code Coverage"]
    robo_coverage --> codecov_upload["Codecov Upload"]
    
    acceptance_tests --> cleanup["Cleanup & Logging"]
    composer_validate --> cleanup
    codecov_upload --> cleanup
```

**Sources:** [.travis.yml:1-115]()

### Environment Configuration Matrix

The CI pipeline supports multiple testing environments and validation steps:

| Test Type | PHP Version | Services | Purpose |
|-----------|-------------|----------|---------|
| Main Tests | 7.4 | MySQL 5.7, Elasticsearch, Chrome | Full test suite execution |
| Composer Validation | 7.4 | None | Dependency validation |
| Code Coverage | 7.4 | MySQL 5.7, Elasticsearch, Chrome | Coverage reporting |

**Sources:** [.travis.yml:4-19]()

## Testing Framework Architecture

SuiteCRM uses the Codeception testing framework with custom drivers and support classes to handle different types of testing scenarios.

### Codeception Framework Structure

```mermaid
classDiagram
    class AcceptanceTester {
        +getFaker() Generator
        +login(username, password)
        +loginAsAdmin()
        +logout()
        +dontSeeMissingLabels()
        +dontSeeErrors()
        +visitPage(module, action, record)
    }
    
    class WebDriver {
        +initialWindowSize()
        +waitForElementVisible(element, timeout)
        +waitForElementNotVisible(element, timeout)
        +waitForText(text, timeout, selector)
    }
    
    class PhpBrowserDriver {
        +_initialize()
    }
    
    class CodeceptionWebDriver {
        <<external>>
    }
    
    class CodeceptionPhpBrowser {
        <<external>>
    }
    
    class CodeceptionActor {
        <<external>>
    }
    
    AcceptanceTester --|> CodeceptionActor
    WebDriver --|> CodeceptionWebDriver
    PhpBrowserDriver --|> CodeceptionPhpBrowser
    
    AcceptanceTester --> WebDriver : uses
    WebDriver --> ChromeDriver : controls
    
    class ChromeDriver {
        <<external>>
        port: 9515
        binary: "/usr/bin/google-chrome-stable"
        args: "--headless --disable-gpu"
    }
```

**Sources:** [tests/_support/AcceptanceTester.php:1-126](), [tests/SuiteCRM/Test/Driver/WebDriver.php:1-95](), [tests/SuiteCRM/Test/Driver/PhpBrowserDriver.php:1-57]()

### Test Environment Configuration

The testing framework supports different browser environments through configuration:

```mermaid
graph TB
    subgraph "Test Environment Config"
        travis_config["travis-ci-hub.yml"]
        webdriver_config["WebDriver Configuration"]
        chrome_config["Chrome Options"]
    end
    
    subgraph "Browser Automation"
        chrome_binary["/usr/bin/google-chrome-stable"]
        chromedriver["ChromeDriver Port 9515"]
        headless_mode["Headless Mode"]
    end
    
    subgraph "Test Execution"
        acceptance_tests["Acceptance Tests"]
        webdriver_tests["WebDriver Tests"]
        browser_tests["Browser Tests"]
    end
    
    travis_config --> webdriver_config
    webdriver_config --> chrome_config
    chrome_config --> chrome_binary
    chrome_config --> chromedriver
    chrome_config --> headless_mode
    
    chromedriver --> acceptance_tests
    headless_mode --> webdriver_tests
    chrome_binary --> browser_tests
```

**Sources:** [tests/_envs/travis-ci-hub.yml:1-18]()

## Test Types and Execution

The CI pipeline executes multiple test types in a specific sequence to ensure comprehensive validation.

### Test Execution Flow

| Phase | Test Type | Framework | Command | Purpose |
|-------|-----------|-----------|---------|---------|
| 1 | Installation | Codeception | `codecept run install` | Validates installation wizard |
| 2 | Unit Tests | PHPUnit | `phpunit --configuration tests/phpunit.xml.dist` | Unit test execution |
| 3 | API Tests | Codeception | `codecept run tests/api/V8/` | API functionality validation |
| 4 | Acceptance Tests | Codeception | `codecept run acceptance` | End-to-end user scenarios |

**Sources:** [.travis.yml:74-98]()

### AcceptanceTester Helper Methods

The `AcceptanceTester` class provides specialized methods for common testing scenarios:

#### Authentication Methods
- `login($username, $password)` - Generic login functionality
- `loginAsAdmin()` - Admin user login using configured credentials
- `logout()` - User logout through menu navigation

#### Validation Methods
- `dontSeeMissingLabels()` - Ensures no untranslated labels (LBL_ prefixes)
- `dontSeeErrors()` - Checks for PHP warnings, notices, and errors

#### Navigation Methods
- `visitPage($module, $action, $record = null)` - Navigate to specific SuiteCRM pages

**Sources:** [tests/_support/AcceptanceTester.php:60-124]()

## Environment Setup and Configuration

### PHP Version Requirements

SuiteCRM defines specific PHP version requirements for different environments:

```php
define('SUITECRM_PHP_MIN_VERSION', '7.4.0');
define('SUITECRM_PHP_REC_VERSION', '7.4.0');
```

**Sources:** [php_version.php:7-10]()

### Travis CI Environment Variables

The CI environment uses specific configuration variables:

| Variable | Value | Purpose |
|----------|-------|---------|
| `INSTANCE_URL` | `http://localhost` | Base URL for testing |
| `DATABASE_DRIVER` | `MYSQL` | Database type |
| `DATABASE_NAME` | `automated_tests` | Test database name |
| `DATABASE_HOST` | `localhost` | Database host |
| `DATABASE_USER` | `automated_tests` | Database user |
| `DATABASE_PASSWORD` | `automated_tests` | Database password |
| `INSTANCE_ADMIN_USER` | `admin` | Default admin username |
| `INSTANCE_ADMIN_PASSWORD` | `admin1` | Default admin password |

**Sources:** [.travis.yml:31-32]()

### Apache and PHP-FPM Configuration

The CI pipeline configures Apache with PHP-FPM for web server testing:

```mermaid
graph LR
    subgraph "Web Server Setup"
        apache["Apache 2"]
        phpfpm["PHP-FPM"]
        fastcgi["FastCGI Module"]
    end
    
    subgraph "Configuration Files"
        vhost["travis-ci-apache"]
        phpini["travis.php.ini"]
        fpmconf["php-fpm.conf"]
    end
    
    subgraph "Test Execution"
        chromedriver["ChromeDriver"]
        codeception["Codeception Tests"]
        webdriver["WebDriver"]
    end
    
    vhost --> apache
    phpini --> phpfpm
    fpmconf --> phpfpm
    fastcgi --> apache
    
    apache --> chromedriver
    phpfpm --> codeception
    chromedriver --> webdriver
```

**Sources:** [.travis.yml:52-68]()

## Code Coverage and Reporting

### Coverage Collection Process

The code coverage pipeline uses multiple tools for comprehensive coverage reporting:

```mermaid
sequenceDiagram
    participant CI as "Travis CI"
    participant Codecept as "Codeception"
    participant Robo as "Robo Task Runner" 
    participant Codecov as "Codecov Service"
    
    CI->>Codecept: Run install tests with coverage
    Codecept->>CI: Generate coverage data
    CI->>Robo: Execute code:coverage task
    Robo->>CI: Process coverage reports
    CI->>Codecov: Upload coverage.xml
    Codecov->>CI: Generate coverage reports
```

The coverage collection command sequence:
1. `./vendor/bin/codecept run install --env travis-ci-hub -f --ext DotReporter`
2. `./vendor/bin/robo code:coverage --ci`
3. `bash <(curl -s https://codecov.io/bash) -f tests/_output/coverage.xml`

**Sources:** [.travis.yml:19]()

### PHP Configuration for Testing

Travis CI uses a custom PHP configuration to optimize testing performance:

```ini
memory_limit = -1
display_errors = On
log_errors = On
trace_errors = On
error_log = error.log
```

**Sources:** [travis.php.ini:1-6]()

## Branch-Based CI Execution

The CI pipeline executes on specific branches to ensure proper testing coverage:

- `master` - Production branch
- `develop` - Development branch  
- `hotfix.*` - Hotfix branches
- `feature.*` - Feature branches
- `fix.*` - Bug fix branches
- `staging.*` - Staging branches

**Sources:** [.travis.yml:107-115]()