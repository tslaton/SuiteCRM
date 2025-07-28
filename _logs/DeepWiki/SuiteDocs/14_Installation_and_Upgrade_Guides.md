# Installation and Upgrade Guides

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.gitignore](.gitignore)
- [content/8.x/_index.en.adoc](content/8.x/_index.en.adoc)
- [content/8.x/admin/Licensing.adoc](content/8.x/admin/Licensing.adoc)
- [content/8.x/admin/_index.en.adoc](content/8.x/admin/_index.en.adoc)
- [content/8.x/admin/_index.ru.adoc](content/8.x/admin/_index.ru.adoc)
- [content/8.x/admin/configuration/LDAP-Configuration.ru.adoc](content/8.x/admin/configuration/LDAP-Configuration.ru.adoc)
- [content/8.x/admin/configuration/Login-Throttling-Configuration.ru.adoc](content/8.x/admin/configuration/Login-Throttling-Configuration.ru.adoc)
- [content/8.x/admin/configuration/SAML-Configuration.ru.adoc](content/8.x/admin/configuration/SAML-Configuration.ru.adoc)
- [content/8.x/admin/configuration/_index.ru.adoc](content/8.x/admin/configuration/_index.ru.adoc)
- [content/8.x/admin/installation-guide/Downloading & Installing.adoc](content/8.x/admin/installation-guide/Downloading & Installing.adoc)
- [content/8.x/admin/installation-guide/Downloading & Installing.ru.adoc](content/8.x/admin/installation-guide/Downloading & Installing.ru.adoc)
- [content/8.x/admin/installation-guide/Languages/install-a-new-language.adoc](content/8.x/admin/installation-guide/Languages/install-a-new-language.adoc)
- [content/8.x/admin/installation-guide/Languages/install-a-new-language.ru.adoc](content/8.x/admin/installation-guide/Languages/install-a-new-language.ru.adoc)
- [content/8.x/admin/installation-guide/Languages/update-a-language-pack.adoc](content/8.x/admin/installation-guide/Languages/update-a-language-pack.adoc)
- [content/8.x/admin/installation-guide/Languages/update-a-language-pack.ru.adoc](content/8.x/admin/installation-guide/Languages/update-a-language-pack.ru.adoc)
- [content/8.x/admin/installation-guide/Performance.en.adoc](content/8.x/admin/installation-guide/Performance.en.adoc)
- [content/8.x/admin/installation-guide/Uninstalling.adoc](content/8.x/admin/installation-guide/Uninstalling.adoc)
- [content/8.x/admin/installation-guide/Uninstalling.ru.adoc](content/8.x/admin/installation-guide/Uninstalling.ru.adoc)
- [content/8.x/admin/installation-guide/Upgrading.ru.adoc](content/8.x/admin/installation-guide/Upgrading.ru.adoc)
- [content/8.x/admin/installation-guide/running-the-cli-installer.ru.adoc](content/8.x/admin/installation-guide/running-the-cli-installer.ru.adoc)
- [content/8.x/admin/installation-guide/running-the-ui-installer.ru.adoc](content/8.x/admin/installation-guide/running-the-ui-installer.ru.adoc)
- [content/8.x/admin/installation-guide/webserver-setup-guide.ru.adoc](content/8.x/admin/installation-guide/webserver-setup-guide.ru.adoc)
- [content/admin/installation-guide/Downloading & Installing.adoc](content/admin/installation-guide/Downloading & Installing.adoc)
- [content/admin/installation-guide/Using the Upgrade Wizard.adoc](content/admin/installation-guide/Using the Upgrade Wizard.adoc)
- [static/images/en/8.x/admin/install-guide/suite-cli-install-options.png](static/images/en/8.x/admin/install-guide/suite-cli-install-options.png)

</details>



This document provides comprehensive installation and upgrade procedures for SuiteCRM, covering both the legacy 7.x series and the modern 8.x architecture. It includes detailed instructions for fresh installations, version upgrades, and post-installation configuration including authentication setup and performance optimization.

For detailed API documentation, see [API Documentation](#4). For customization and development guidance, see [Customization and Development](#6). For system administration tasks, see [Administration](#7).

## Installation Architecture Overview

SuiteCRM supports multiple installation methods depending on the version and deployment requirements:

**SuiteCRM Installation Methods Flow**
```mermaid
flowchart TD
    START["Installation Decision"]
    
    subgraph "Version Selection"
        V7["SuiteCRM 7.x<br/>Legacy Architecture"]
        V8["SuiteCRM 8.x<br/>Modern Architecture"]
    end
    
    subgraph "7.x Installation"
        WEB7["Web-based Installer<br/>install.php"]
        MANUAL7["Manual Installation<br/>File Copy + Wizard"]
        CONFIG7["config.php Configuration"]
    end
    
    subgraph "8.x Installation Methods"
        WEBUI8["Web UI Installer<br/>/#/install"]
        CLI8["CLI Installer<br/>bin/console suitecrm:app:install"]
        PREREQ8["Prerequisites Check<br/>Symfony Requirements"]
    end
    
    subgraph "Common Requirements"
        WEBSERVER["Web Server Setup<br/>Apache/Nginx + mod_rewrite"]
        DATABASE["Database Preparation<br/>MySQL/MariaDB"]
        PHP["PHP Configuration<br/>Extensions + php.ini"]
        PERMISSIONS["File Permissions<br/>chmod/chown"]
    end
    
    START --> V7
    START --> V8
    
    V7 --> WEB7
    V7 --> MANUAL7
    WEB7 --> CONFIG7
    MANUAL7 --> CONFIG7
    
    V8 --> WEBUI8
    V8 --> CLI8
    WEBUI8 --> PREREQ8
    CLI8 --> PREREQ8
    
    V7 --> WEBSERVER
    V8 --> WEBSERVER
    WEBSERVER --> DATABASE
    DATABASE --> PHP
    PHP --> PERMISSIONS
```

Sources: [content/8.x/admin/installation-guide/Downloading & Installing.adoc:83-88](), [content/admin/installation-guide/Downloading & Installing.adoc:95-107](), [content/8.x/admin/installation-guide/running-the-ui-installer.ru.adoc:36-38]()

## SuiteCRM 8.x Installation Process

### Prerequisites and Web Server Setup

SuiteCRM 8.x requires specific server configuration before installation:

| Component | Requirement | Configuration File |
|-----------|-------------|-------------------|
| PHP Extensions | `cli`, `curl`, `intl`, `json`, `gd`, `mbstring`, `mysqli`, `pdo_mysql`, `openssl`, `soap`, `xml`, `zip` | `php.ini` |
| Apache Module | `mod_rewrite` enabled | `httpd.conf` or `.htaccess` |
| Error Reporting | `E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING` | `php.ini` |
| Document Root | Points to `public/` directory | `vhost` configuration |

The web server setup involves configuring the document root to point to the `public/` directory for security:

```apache
<VirtualHost *:80>
    DocumentRoot /<path-to-suite>/public
    <Directory /<path-to-suite>/public>
        AllowOverride All
        Order Allow,Deny
        Allow from All
    </Directory>
</VirtualHost>
```

Sources: [content/8.x/admin/installation-guide/webserver-setup-guide.ru.adoc:36-53](), [content/8.x/admin/installation-guide/webserver-setup-guide.ru.adoc:66-80]()

### File Permissions Setup

After extracting SuiteCRM files, specific permissions must be set:

```bash
find . -type d -not -perm 2755 -exec chmod 2755 {} \;
find . -type f -not -perm 0644 -exec chmod 0644 {} \;
find . ! -user www-data -exec chown www-data:www-data {} \;
chmod +x bin/console
```

Sources: [content/8.x/admin/installation-guide/Downloading & Installing.adoc:58-64](), [content/8.x/admin/installation-guide/running-the-cli-installer.ru.adoc:75-81]()

### Installation Methods

**SuiteCRM 8.x Installation Options**
```mermaid
flowchart LR
    subgraph "CLI Installation"
        CLI_CMD["bin/console suitecrm:app:install"]
        CLI_INTERACTIVE["Interactive Mode<br/>Prompts for parameters"]
        CLI_PARAMS["Parameter Mode<br/>-u -p -U -P -H -N -Z -S -d"]
    end
    
    subgraph "Web UI Installation"
        WEB_URL["/#/install"]
        WEB_LICENSE["License Agreement"]
        WEB_CONFIG["Configuration Form"]
        WEB_VALIDATION["System Validation"]
    end
    
    subgraph "Installation Parameters"
        ADMIN_USER["admin_username"]
        ADMIN_PASS["admin_password"] 
        DB_USER["db_user"]
        DB_PASS["db_password"]
        DB_HOST["db_host"]
        DB_NAME["db_name"]
        DB_PORT["db_port"]
        SITE_URL["site_url"]
        DEMO_DATA["demo_data"]
    end
    
    CLI_CMD --> CLI_INTERACTIVE
    CLI_CMD --> CLI_PARAMS
    
    WEB_URL --> WEB_LICENSE
    WEB_LICENSE --> WEB_CONFIG
    WEB_CONFIG --> WEB_VALIDATION
    
    CLI_PARAMS --> ADMIN_USER
    WEB_CONFIG --> ADMIN_USER
    ADMIN_USER --> ADMIN_PASS
    ADMIN_PASS --> DB_USER
    DB_USER --> DB_PASS
    DB_PASS --> DB_HOST
    DB_HOST --> DB_NAME
    DB_NAME --> DB_PORT
    DB_PORT --> SITE_URL
    SITE_URL --> DEMO_DATA
```

Sources: [content/8.x/admin/installation-guide/running-the-cli-installer.ru.adoc:34-67](), [content/8.x/admin/installation-guide/running-the-ui-installer.ru.adoc:50-85]()

## SuiteCRM 7.x Installation Process

### Legacy Installation Wizard

SuiteCRM 7.x uses a traditional PHP-based installation wizard accessible at `install.php`:

**7.x Installation Flow**
```mermaid
flowchart TD
    EXTRACT["Extract SuiteCRM Files"]
    PERMS["Set File Permissions<br/>chmod 755, 775"]
    WIZARD["Access install.php"]
    
    subgraph "Installation Wizard Steps"
        WELCOME["Welcome Page"]
        LICENSE["License Agreement"]
        SYSCHECK["System Requirements Check"]
        DBTYPE["Database Type Selection"]
        DBCONFIG["Database Configuration"]
        SITECONFIG["Site Configuration"]
        CONFIRM["Confirm Settings"]
        INSTALL["Installation Process"]
        COMPLETE["Installation Complete"]
    end
    
    subgraph "Installation Types"
        TYPICAL["Typical Installation<br/>Standard Settings"]
        CUSTOM["Custom Installation<br/>Advanced Options"]
    end
    
    EXTRACT --> PERMS
    PERMS --> WIZARD
    WIZARD --> WELCOME
    WELCOME --> LICENSE
    LICENSE --> SYSCHECK
    SYSCHECK --> TYPICAL
    SYSCHECK --> CUSTOM
    TYPICAL --> DBTYPE
    CUSTOM --> DBTYPE
    DBTYPE --> DBCONFIG
    DBCONFIG --> SITECONFIG
    SITECONFIG --> CONFIRM
    CONFIRM --> INSTALL
    INSTALL --> COMPLETE
```

Sources: [content/admin/installation-guide/Downloading & Installing.adoc:110-184](), [content/admin/installation-guide/Downloading & Installing.adoc:186-257]()

## Upgrade Procedures

### SuiteCRM 8.x Upgrade Process

SuiteCRM 8.x uses console commands for upgrades without requiring special upgrade packages:

**8.x Upgrade Workflow**
```mermaid
flowchart TD
    BACKUP["Create System Backup<br/>Files + Database"]
    COMPAT["Check Compatibility Matrix"]
    ENV_CHECK["Verify APP_ENV=prod<br/>.env.local"]
    
    subgraph "Version Constraints"
        V80_81["8.0.x → 8.1.x<br/>Direct Upgrade"]
        V82_PLUS["8.2.x and Later<br/>Standard Process"]
        V80_83["8.0.x → 8.3.x+<br/>Must go through 8.2.x"]
    end
    
    subgraph "Upgrade Commands"
        DOWNLOAD["Download Release Package"]
        EXTRACT["Extract to tmp/package/upgrade/"]
        UPGRADE_CMD["bin/console suitecrm:app:upgrade -t VERSION"]
        FINALIZE_CMD["bin/console suitecrm:app:upgrade-finalize -t VERSION"]
    end
    
    subgraph "Metadata Merge Modes"
        KEEP_MODE["keep: Preserve existing"]
        MERGE_MODE["merge: Combine metadata"] 
        OVERRIDE_MODE["override: Replace all"]
    end
    
    BACKUP --> COMPAT
    COMPAT --> ENV_CHECK
    ENV_CHECK --> V80_81
    ENV_CHECK --> V82_PLUS
    ENV_CHECK --> V80_83
    
    V82_PLUS --> DOWNLOAD
    DOWNLOAD --> EXTRACT
    EXTRACT --> UPGRADE_CMD
    UPGRADE_CMD --> FINALIZE_CMD
    
    FINALIZE_CMD --> KEEP_MODE
    FINALIZE_CMD --> MERGE_MODE
    FINALIZE_CMD --> OVERRIDE_MODE
```

Example upgrade commands:
```bash
./bin/console suitecrm:app:upgrade -t SuiteCRM-8.3.0
./bin/console suitecrm:app:upgrade-finalize -t SuiteCRM-8.3.0 -m merge
```

Sources: [content/8.x/admin/installation-guide/Upgrading.ru.adoc:78-89](), [content/8.x/admin/installation-guide/Upgrading.ru.adoc:139-195]()

### SuiteCRM 7.x Upgrade Wizard

The 7.x series uses a web-based Upgrade Wizard for version transitions:

**7.x Upgrade Process**
```mermaid
flowchart TD
    ADMIN_LOGIN["Login as Administrator"]
    UPGRADE_WIZARD["Access Upgrade Wizard<br/>Admin Panel"]
    
    subgraph "Upgrade Wizard Steps"
        SYSTEM_CHECK["System Checks"]
        UPLOAD["Upload Upgrade Package"]
        PREFLIGHT["Preflight Check<br/>Schema Differences"]
        COMMIT["Commit Upgrade"]
        LAYOUT_MERGE["Layout Confirmation<br/>Three-way Merge"]
        COMPLETE["Upgrade Complete"]
    end
    
    subgraph "Post-Upgrade Tasks"
        REPAIR["Repair and Rebuild<br/>Relationships + Extensions"]
        MANUAL_MERGE["Manual File Merging<br/>Skipped Files"]
        LOG_CHECK["Check upgradeWizard.log"]
    end
    
    ADMIN_LOGIN --> UPGRADE_WIZARD
    UPGRADE_WIZARD --> SYSTEM_CHECK
    SYSTEM_CHECK --> UPLOAD
    UPLOAD --> PREFLIGHT
    PREFLIGHT --> COMMIT
    COMMIT --> LAYOUT_MERGE
    LAYOUT_MERGE --> COMPLETE
    COMPLETE --> REPAIR
    REPAIR --> MANUAL_MERGE
    MANUAL_MERGE --> LOG_CHECK
```

Sources: [content/admin/installation-guide/Using the Upgrade Wizard.adoc:16-98]()

## Authentication Configuration

### SAML Configuration

SuiteCRM 8.x supports SAML authentication using OneloginSamlBundle:

**SAML Authentication Flow**
```mermaid
flowchart LR
    subgraph "Configuration Files"
        ENV_LOCAL[".env.local<br/>AUTH_TYPE=saml"]
        SAML_CONFIG["extensions/custom/config/packages/<br/>hslavich_onelogin_saml.yaml"]
        SECRETS["Symfony Secrets Vault<br/>Encrypted Keys"]
    end
    
    subgraph "SAML Components" 
        IDP["Identity Provider<br/>entityId, singleSignOnService"]
        SP["Service Provider<br/>SuiteCRM Instance"]
        CERTIFICATES["X.509 Certificates<br/>IDP + SP"]
    end
    
    subgraph "User Management"
        AUTO_CREATE["SAML_AUTO_CREATE=enabled"]
        ATTR_MAP["Attribute Mapping<br/>SAML → SuiteCRM Fields"]
        EXTERNAL_AUTH["external_auth_only Flag"]
    end
    
    ENV_LOCAL --> SAML_CONFIG
    SAML_CONFIG --> IDP
    SAML_CONFIG --> SP
    SAML_CONFIG --> CERTIFICATES
    CERTIFICATES --> SECRETS
    
    IDP --> AUTO_CREATE
    SP --> ATTR_MAP
    ATTR_MAP --> EXTERNAL_AUTH
```

Key configuration parameters:
- `SAML_USERNAME_ATTRIBUTE`: Maps SAML attribute to SuiteCRM username
- `SAML_USE_ATTRIBUTE_FRIENDLY_NAME`: Uses friendly names from SAML request

Sources: [content/8.x/admin/configuration/SAML-Configuration.ru.adoc:45-50](), [content/8.x/admin/configuration/SAML-Configuration.ru.adoc:90-191]()

### LDAP Configuration

LDAP authentication integrates with Symfony Security components:

| Parameter | Description | Example |
|-----------|-------------|---------|
| `LDAP_HOST` | LDAP server hostname | `ldap.company.com` |
| `LDAP_PORT` | Server port | `389` (standard) |
| `LDAP_ENCRYPTION` | Connection security | `tls`, `ssl`, `none` |
| `LDAP_DN_STRING` | Distinguished Name pattern | `cn={username},dc=example,dc=org` |
| `LDAP_QUERY_STRING` | User search query | Custom search filter |

Sources: [content/8.x/admin/configuration/LDAP-Configuration.ru.adoc:44-98](), [content/8.x/admin/configuration/LDAP-Configuration.ru.adoc:120-206]()

## Language Pack Installation

### Language Pack Management

SuiteCRM supports multiple languages through installable language packs:

**Language Pack Installation Process**
```mermaid
flowchart TD
    DOWNLOAD["Download Language Pack<br/>ZIP File"]
    ADMIN_PANEL["Access Admin Panel<br/>Module Loader"]
    
    subgraph "Installation Steps"
        UPLOAD["Upload Language Pack"]
        INSTALL["Install Package"]
        REPAIR["Quick Repair and Rebuild"]
        LOGOUT["Logout from System"]
        LOGIN["Login with New Language"]
    end
    
    subgraph "Update Process"
        UNINSTALL["Uninstall Old Pack"]
        REPAIR_OLD["Quick Repair"]
        INSTALL_NEW["Install New Pack"] 
        REPAIR_NEW["Final Repair"]
    end
    
    DOWNLOAD --> ADMIN_PANEL
    ADMIN_PANEL --> UPLOAD
    UPLOAD --> INSTALL
    INSTALL --> REPAIR
    REPAIR --> LOGOUT
    LOGOUT --> LOGIN
    
    ADMIN_PANEL --> UNINSTALL
    UNINSTALL --> REPAIR_OLD
    REPAIR_OLD --> INSTALL_NEW
    INSTALL_NEW --> REPAIR_NEW
```

Sources: [content/8.x/admin/installation-guide/Languages/install-a-new-language.ru.adoc:42-54](), [content/8.x/admin/installation-guide/Languages/update-a-language-pack.ru.adoc:10-17]()

## Performance Configuration

### Production Optimization

For production deployments, SuiteCRM 8.x supports several performance enhancements:

**Performance Configuration Stack**
```mermaid
flowchart TD
    subgraph "PHP Optimization"
        OPCACHE["OPCache Configuration<br/>opcache.enable=1"]
        APCU["APCu Cache<br/>Memory Cache"]
        MEMORY["Memory Limits<br/>php.ini"]
    end
    
    subgraph "Symfony Configuration"
        APP_ENV["APP_ENV=prod<br/>.env.local"]
        CACHE_CLEAR["bin/console cache:clear"]
        METADATA_CACHE["API Platform Metadata Cache"]
    end
    
    subgraph "Web Server"
        MOD_REWRITE["mod_rewrite Enable"]
        COMPRESSION["GZIP Compression"]
        STATIC_CACHE["Static File Caching"]
    end
    
    OPCACHE --> APP_ENV
    APCU --> CACHE_CLEAR
    MEMORY --> METADATA_CACHE
    
    APP_ENV --> MOD_REWRITE
    CACHE_CLEAR --> COMPRESSION
    METADATA_CACHE --> STATIC_CACHE
```

Example OPCache configuration for `php.ini`:
```ini
[opcache]
zend_extension=opcache.so
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

Sources: [content/8.x/admin/installation-guide/Performance.en.adoc:6-32](), [content/8.x/admin/installation-guide/Performance.en.adoc:42-60]()

## Troubleshooting and Log Files

### Common Installation Issues

| Issue | Location | Solution |
|-------|----------|----------|
| Permission errors | File system | Re-run `chmod`/`chown` commands |
| Database connection | `.env.local` | Verify credentials and host |
| Session ID conflicts | `php.ini` | Set `session.name=PHPSESSID` |
| Cache issues | `cache/` directory | Run `bin/console cache:clear` |

### Log File Locations

SuiteCRM 8.x maintains several log files for troubleshooting:

- `logs/upgrade.log`: Upgrade process logs
- `public/legacy/upgradeWizard.log`: Legacy upgrade logs  
- `logs/<app-env>/<app-env>.log`: Main application logs
- `public/legacy/suitecrm.log`: Legacy application logs
- `logs/auth.log`: Authentication process logs

Sources: [content/8.x/admin/installation-guide/Upgrading.ru.adoc:291-314](), [content/8.x/admin/configuration/SAML-Configuration.ru.adoc:422-436]()