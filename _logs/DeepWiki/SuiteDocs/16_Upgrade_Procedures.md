# Upgrade Procedures

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [content/8.x/_index.en.adoc](content/8.x/_index.en.adoc)
- [content/8.x/admin/Licensing.adoc](content/8.x/admin/Licensing.adoc)
- [content/8.x/admin/_index.en.adoc](content/8.x/admin/_index.en.adoc)
- [content/8.x/admin/_index.ru.adoc](content/8.x/admin/_index.ru.adoc)
- [content/8.x/admin/installation-guide/Downloading & Installing.adoc](content/8.x/admin/installation-guide/Downloading & Installing.adoc)
- [content/8.x/admin/installation-guide/Languages/install-a-new-language.adoc](content/8.x/admin/installation-guide/Languages/install-a-new-language.adoc)
- [content/8.x/admin/installation-guide/Languages/update-a-language-pack.adoc](content/8.x/admin/installation-guide/Languages/update-a-language-pack.adoc)
- [content/8.x/admin/installation-guide/Performance.en.adoc](content/8.x/admin/installation-guide/Performance.en.adoc)
- [content/8.x/admin/installation-guide/Uninstalling.adoc](content/8.x/admin/installation-guide/Uninstalling.adoc)
- [content/8.x/admin/releases/8.1/_index.en.adoc](content/8.x/admin/releases/8.1/_index.en.adoc)
- [content/8.x/admin/releases/8.2/_index.en.adoc](content/8.x/admin/releases/8.2/_index.en.adoc)
- [content/8.x/admin/releases/8.3/_index.en.adoc](content/8.x/admin/releases/8.3/_index.en.adoc)
- [content/8.x/admin/releases/8.4/_index.en.adoc](content/8.x/admin/releases/8.4/_index.en.adoc)
- [content/8.x/admin/releases/8.5/_index.en.adoc](content/8.x/admin/releases/8.5/_index.en.adoc)
- [content/8.x/admin/releases/8.6/_index.en.adoc](content/8.x/admin/releases/8.6/_index.en.adoc)
- [content/8.x/admin/releases/8.7/_index.en.adoc](content/8.x/admin/releases/8.7/_index.en.adoc)
- [content/8.x/admin/upgrading/general-info.adoc](content/8.x/admin/upgrading/general-info.adoc)
- [content/blog/larger-upgrades.adoc](content/blog/larger-upgrades.adoc)
- [i18n/es.toml](i18n/es.toml)
- [layouts/partials/home-cta1.html](layouts/partials/home-cta1.html)
- [layouts/partials/home-cta2.html](layouts/partials/home-cta2.html)
- [layouts/partials/home-cta3.html](layouts/partials/home-cta3.html)
- [layouts/partials/home-cta4.html](layouts/partials/home-cta4.html)
- [layouts/partials/last-blog-posts.html](layouts/partials/last-blog-posts.html)
- [layouts/partials/recently-edited-item.html](layouts/partials/recently-edited-item.html)
- [layouts/partials/recently-edited.html](layouts/partials/recently-edited.html)
- [layouts/partials/tags.html](layouts/partials/tags.html)
- [static/css/tags.css](static/css/tags.css)
- [static/images/en/8.x/admin/install-guide/suite-cli-install-options.png](static/images/en/8.x/admin/install-guide/suite-cli-install-options.png)
- [static/images/en/8.x/admin/release/portal-user-enable-buttons.gif](static/images/en/8.x/admin/release/portal-user-enable-buttons.gif)
- [static/images/en/8.x/admin/release/preinstall-page-re-styled.png](static/images/en/8.x/admin/release/preinstall-page-re-styled.png)
- [static/images/en/8.x/admin/release/release-notes-field-actions-example.gif](static/images/en/8.x/admin/release/release-notes-field-actions-example.gif)
- [themes/hugo-theme-learn/layouts/partials/summary-minus-toc.html](themes/hugo-theme-learn/layouts/partials/summary-minus-toc.html)
- [themes/hugo-theme-learn/layouts/partials/toc.html](themes/hugo-theme-learn/layouts/partials/toc.html)

</details>



This document covers the upgrade procedures for SuiteCRM installations, focusing on the technical processes, version compatibility requirements, and upgrade paths between different SuiteCRM versions. For initial installation procedures, see [Installation Process](#5.1). For post-upgrade authentication configuration, see [Authentication Configuration](#5.3).

## Purpose and Scope

The upgrade procedures encompass version-specific upgrade paths, system requirement changes, backward compatibility considerations, and the technical execution of upgrades for both SuiteCRM 7.x and 8.x series. This includes database migrations, file management, configuration updates, and validation processes.

## Version Compatibility and Upgrade Paths

### SuiteCRM Version Ecosystem

```mermaid
graph TD
    subgraph "Legacy 7.x Series"
        V78["7.8.x<br/>EOL"]
        V710["7.10.x<br/>Extended Support Ended"]
        V711["7.11.x<br/>Maintenance Mode"]
        V712["7.12.x<br/>Latest 7.x"]
        V713["7.13.x<br/>OAuth Email"]
        V714["7.14.x<br/>PHP 8.2 Support"]
    end
    
    subgraph "Modern 8.x Series"
        V80["8.0.x<br/>Initial Release"]
        V81["8.1.x<br/>Early Adopter"]
        V82["8.2.x<br/>Migration Entry Point"]
        V83["8.3.x<br/>Notifications & Subpanel Filters"]
        V84["8.4.x<br/>PHP 8.1+ Required"]
        V85["8.5.x<br/>Angular 16"]
        V86["8.6.x<br/>Language Selector"]
        V87["8.7.x<br/>Symfony 6.4"]
        V88["8.8.x<br/>Latest"]
    end
    
    subgraph "Upgrade Restrictions"
        STEP1["RC/8.0.x/8.1.x → 8.2.x"]
        STEP2["8.2.x → Current"]
        BLOCK["8.0.x-8.1.x cannot upgrade<br/>directly to 8.3.x+"]
    end
    
    V78 --> V710
    V710 --> V711
    V711 --> V712
    V712 --> V713
    V713 --> V714
    
    V714 -.->|"No Direct Path"| V82
    
    V80 --> V81
    V81 --> STEP1
    V80 --> STEP1
    STEP1 --> V82
    V82 --> STEP2
    STEP2 --> V88
    
    V82 --> V83
    V83 --> V84
    V84 --> V85
    V85 --> V86
    V86 --> V87
    V87 --> V88
    
    STEP1 --> BLOCK
    BLOCK --> V83
```

Sources: [content/8.x/admin/upgrading/general-info.adoc:30-79](), [content/8.x/admin/releases/8.4/_index.en.adoc:212-220](), [content/8.x/admin/releases/8.2/_index.en.adoc:326-348]()

### Upgrade Path Requirements

| Starting Version | Required Path | Documentation |
|------------------|---------------|---------------|
| RC/8.0.x/8.1.x → 8.3.x+ | Must upgrade to 8.2.x first | Mandatory step restriction |
| 8.2.x → Current | Direct upgrade supported | Standard procedure |
| Beta → Current | Beta → RC/8.0.x/8.1.x → 8.2.x → Current | Multi-step required |
| 7.12.x+ → 8.x | Migration procedure required | Uses migration tooling |

Sources: [content/8.x/admin/upgrading/general-info.adoc:40-44](), [content/8.x/admin/upgrading/general-info.adoc:68-78]()

## System Requirements and Breaking Changes

### Version-Specific System Requirements

```mermaid
graph LR
    subgraph "PHP Requirements"
        PHP74["PHP 7.4<br/>8.3.0+"]
        PHP81["PHP 8.1<br/>8.4.0+"]
        PHP81_87["PHP 8.1<br/>8.7.0+"]
    end
    
    subgraph "Framework Updates"
        SF6["Symfony 6.4<br/>8.7.0+"]
        AP3["Api Platform 3.2<br/>8.7.0+"]
        ANG16["Angular 16<br/>8.5.0+"]
        ANG18["Angular 18<br/>8.8.0+"]
    end
    
    subgraph "Development Tools"
        COMP2["Composer 2+<br/>8.7.0+"]
        NODE18["Node.js 18+<br/>8.5.0+"]
    end
    
    V83 --> PHP74
    V84 --> PHP81
    V87 --> PHP81_87
    V87 --> SF6
    V87 --> AP3
    V87 --> COMP2
    V85 --> ANG16
    V88 --> ANG18
```

Sources: [content/8.x/admin/releases/8.3/_index.en.adoc:105-111](), [content/8.x/admin/releases/8.4/_index.en.adoc:185-191](), [content/8.x/admin/releases/8.7/_index.en.adoc:96-105]()

### Critical Configuration Changes

| Version | Change Type | Impact | Required Action |
|---------|-------------|--------|-----------------|
| 8.4.0 | Extension Rename | `extensions/default` → `extensions/defaultExt` | Manual migration required |
| 8.4.0 | Display Logic | `displayType` → `displayLogic` | Update metadata |
| 8.7.0 | SAML Config | Configuration structure changed | Reconfigure SAML |
| 8.7.0 | APP_SECRET | Environment property required | Auto-generated on upgrade |

Sources: [content/8.x/admin/releases/8.4/_index.en.adoc:194-210](), [content/8.x/admin/releases/8.7/_index.en.adoc:106-161]()

## Upgrade Process Components

### Technical Upgrade Workflow

```mermaid
flowchart TD
    START["upgrade_start"]
    
    subgraph "Pre-Upgrade Phase"
        CHECK_SYS["system_requirements_check"]
        BACKUP_DB["database_backup"]
        BACKUP_FILES["files_backup"]
        CHECK_CUSTOM["customizations_audit"]
    end
    
    subgraph "Core Upgrade Process"
        DOWNLOAD["download_packages"]
        EXTRACT["extract_files"]
        REPLACE_CORE["replace_core_files"]
        PRESERVE_CUSTOM["preserve_custom_directory"]
        PRESERVE_UPLOADS["preserve_uploads_directory"]
    end
    
    subgraph "Database Migration"
        DB_SCHEMA["run_database_migrations"]
        CONFIG_UPDATE["update_config_php"]
        CACHE_CLEAR["clear_cache_directories"]
    end
    
    subgraph "Post-Upgrade Tasks"
        REPAIR_REBUILD["repair_and_rebuild"]
        PERMISSIONS["set_file_permissions"]
        VALIDATE["validation_tests"]
        CONFIG_CHECK["configuration_verification"]
    end
    
    START --> CHECK_SYS
    CHECK_SYS --> BACKUP_DB
    BACKUP_DB --> BACKUP_FILES
    BACKUP_FILES --> CHECK_CUSTOM
    
    CHECK_CUSTOM --> DOWNLOAD
    DOWNLOAD --> EXTRACT
    EXTRACT --> REPLACE_CORE
    REPLACE_CORE --> PRESERVE_CUSTOM
    PRESERVE_CUSTOM --> PRESERVE_UPLOADS
    
    PRESERVE_UPLOADS --> DB_SCHEMA
    DB_SCHEMA --> CONFIG_UPDATE
    CONFIG_UPDATE --> CACHE_CLEAR
    
    CACHE_CLEAR --> REPAIR_REBUILD
    REPAIR_REBUILD --> PERMISSIONS
    PERMISSIONS --> VALIDATE
    VALIDATE --> CONFIG_CHECK
```

Sources: [content/blog/larger-upgrades.adoc:115-188](), [content/8.x/admin/installation-guide/Downloading & Installing.adoc:52-64]()

### File System Components in Upgrade

```mermaid
graph TD
    subgraph "SuiteCRM Directory Structure"
        ROOT["suitecrm_root"]
        CORE["core/"]
        CUSTOM["custom/"]
        UPLOADS["uploads/"]
        CACHE["cache/"]
        CONFIG["config.php"]
        HTACCESS[".htaccess"]
    end
    
    subgraph "Upgrade Actions"
        REPLACE["replace_core_files"]
        PRESERVE["preserve_customizations"]
        MIGRATE["migrate_configurations"]
        CLEAR["clear_cache"]
        UPDATE_PERMS["update_permissions"]
    end
    
    subgraph "File Operations"
        BACKUP_OP["backup_operation"]
        EXTRACT_OP["extract_new_files"]
        MERGE_OP["merge_custom_files"]
        VALIDATE_OP["validate_file_structure"]
    end
    
    ROOT --> CORE
    ROOT --> CUSTOM
    ROOT --> UPLOADS
    ROOT --> CACHE
    ROOT --> CONFIG
    ROOT --> HTACCESS
    
    CORE --> REPLACE
    CUSTOM --> PRESERVE
    CONFIG --> MIGRATE
    CACHE --> CLEAR
    ROOT --> UPDATE_PERMS
    
    REPLACE --> BACKUP_OP
    PRESERVE --> MERGE_OP
    MIGRATE --> EXTRACT_OP
    CLEAR --> VALIDATE_OP
```

Sources: [content/blog/larger-upgrades.adoc:147-156](), [content/8.x/admin/installation-guide/Downloading & Installing.adoc:35-51]()

## Database Migration Procedures

### Database Upgrade Process

The database upgrade process involves schema updates, data migrations, and configuration changes. The core upgrade logic handles version-specific database modifications automatically.

**Key Database Operations:**
- Schema updates for new field types and table structures
- Data migration for configuration changes
- Index updates for performance improvements
- Foreign key constraint updates

**Critical Files:**
- Database upgrade scripts handle version-specific migrations
- Configuration updates modify `config.php` entries
- Cache clearing removes outdated metadata

Sources: [content/blog/larger-upgrades.adoc:133-145](), [content/8.x/admin/releases/8.2/_index.en.adoc:25-30]()

### Configuration Migration

Configuration changes between versions require specific migration steps:

```mermaid
graph LR
    subgraph "Config Sources"
        CONFIG_PHP["config.php"]
        ENV_FILES[".env files"]
        LEGACY_CONFIG["legacy_settings"]
    end
    
    subgraph "Migration Process"
        PARSE_CONFIG["parse_existing_config"]
        VALIDATE_SETTINGS["validate_settings"]
        APPLY_DEFAULTS["apply_new_defaults"]
        UPDATE_STRUCTURE["update_config_structure"]
    end
    
    subgraph "Output"
        NEW_CONFIG["updated_config.php"]
        NEW_ENV["updated_.env"]
        MIGRATION_LOG["migration_log"]
    end
    
    CONFIG_PHP --> PARSE_CONFIG
    ENV_FILES --> PARSE_CONFIG
    LEGACY_CONFIG --> PARSE_CONFIG
    
    PARSE_CONFIG --> VALIDATE_SETTINGS
    VALIDATE_SETTINGS --> APPLY_DEFAULTS
    APPLY_DEFAULTS --> UPDATE_STRUCTURE
    
    UPDATE_STRUCTURE --> NEW_CONFIG
    UPDATE_STRUCTURE --> NEW_ENV
    UPDATE_STRUCTURE --> MIGRATION_LOG
```

Sources: [content/8.x/admin/releases/8.7/_index.en.adoc:106-161](), [content/8.x/admin/releases/8.1/_index.en.adoc:156-167]()

## Version-Specific Upgrade Considerations

### 8.4.x Upgrade Requirements

**Breaking Changes:**
- `extensions/default` package renamed to `extensions/defaultExt`
- `displayType` logic moved to `displayLogic` metadata entry
- PHP 8.1+ requirement

**Migration Steps:**
1. Manual migration of custom extensions from `default` to `defaultExt`
2. Update metadata configurations using new `displayLogic` structure
3. Verify PHP version compatibility

Sources: [content/8.x/admin/releases/8.4/_index.en.adoc:194-210]()

### 8.7.x Platform Upgrade

**Major Framework Changes:**
- Symfony 6.4 upgrade
- Api Platform 3.2 migration
- Composer 2+ requirement
- APP_SECRET environment property required

**SAML Configuration Update:**
- New configuration structure with environment-based settings
- Migration from `Hslavich` to `Nbgrp` SAML dependency
- Updated authentication handlers

Sources: [content/8.x/admin/releases/8.7/_index.en.adoc:92-161]()

## Security and Validation

### Post-Upgrade Validation

**Critical Validation Points:**
- Database integrity verification
- File permission validation
- Configuration syntax checking
- Custom code compatibility testing
- API endpoint functionality verification

**Security Considerations:**
- File permission reset using `chmod 2755` for directories and `chmod 0644` for files
- Web server user ownership verification (`www-data` or `apache`)
- `.htaccess` configuration validation

Sources: [content/8.x/admin/installation-guide/Downloading & Installing.adoc:52-74](), [content/blog/larger-upgrades.adoc:155-173]()

### Testing and Rollback Procedures

**Testing Strategy:**
- Clone system testing before production upgrade
- Customization functionality verification
- Email configuration testing
- Theme and UI validation
- Third-party integration testing

**Rollback Preparation:**
- Complete database backup before upgrade
- File system snapshot or backup
- Configuration backup
- Documentation of custom modifications

Sources: [content/blog/larger-upgrades.adoc:117-132](), [content/blog/larger-upgrades.adoc:155-164]()