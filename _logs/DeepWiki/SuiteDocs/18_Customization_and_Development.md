# Customization and Development

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [content/8.x/admin/Compatibility Matrix.adoc](content/8.x/admin/Compatibility Matrix.adoc)
- [content/8.x/developer/developer-getting-started.adoc](content/8.x/developer/developer-getting-started.adoc)
- [content/8.x/developer/extensions/backend/record-mappers/_index.en.adoc](content/8.x/developer/extensions/backend/record-mappers/_index.en.adoc)
- [content/8.x/developer/extensions/frontend/88x-fe-extensions-setup.adoc](content/8.x/developer/extensions/frontend/88x-fe-extensions-setup.adoc)
- [content/8.x/developer/extensions/frontend/examples/add-charts-extension.adoc](content/8.x/developer/extensions/frontend/examples/add-charts-extension.adoc)
- [content/8.x/developer/extensions/frontend/examples/add-sidebar-widget.adoc](content/8.x/developer/extensions/frontend/examples/add-sidebar-widget.adoc)
- [content/8.x/developer/extensions/frontend/migration/_index.en.adoc](content/8.x/developer/extensions/frontend/migration/_index.en.adoc)
- [content/8.x/developer/extensions/frontend/older/8x-fe-extensions-getting-started.adoc](content/8.x/developer/extensions/frontend/older/8x-fe-extensions-getting-started.adoc)
- [content/8.x/developer/extensions/frontend/older/8x-fe-extensions-setup.adoc](content/8.x/developer/extensions/frontend/older/8x-fe-extensions-setup.adoc)
- [content/8.x/developer/extensions/frontend/older/_index.en.adoc](content/8.x/developer/extensions/frontend/older/_index.en.adoc)
- [content/8.x/developer/installation-guide/backend-end-installation-guide.adoc](content/8.x/developer/installation-guide/backend-end-installation-guide.adoc)
- [content/blog/Customizing-Subthemes.adoc](content/blog/Customizing-Subthemes.adoc)
- [content/blog/ListView-conditional-formatting.adoc](content/blog/ListView-conditional-formatting.adoc)
- [content/community/contributing-to-docs/guidelines.adoc](content/community/contributing-to-docs/guidelines.adoc)
- [content/community/contributing-to-docs/translate.adoc](content/community/contributing-to-docs/translate.adoc)
- [content/developer/Best Practices.adoc](content/developer/Best Practices.adoc)
- [content/developer/Config.adoc](content/developer/Config.adoc)
- [content/developer/Controllers.adoc](content/developer/Controllers.adoc)
- [content/developer/Entry Points.adoc](content/developer/Entry Points.adoc)
- [content/developer/Extension Framework.adoc](content/developer/Extension Framework.adoc)
- [content/developer/Language Strings.adoc](content/developer/Language Strings.adoc)
- [content/developer/Logging.adoc](content/developer/Logging.adoc)
- [content/developer/Logic Hooks.adoc](content/developer/Logic Hooks.adoc)
- [content/developer/Metadata.adoc](content/developer/Metadata.adoc)
- [content/developer/Module Installer.adoc](content/developer/Module Installer.adoc)
- [content/developer/Scheduled Tasks.adoc](content/developer/Scheduled Tasks.adoc)
- [content/developer/Vardefs.adoc](content/developer/Vardefs.adoc)
- [static/images/en/developer/Admin-OAuth2Clients-2.png](static/images/en/developer/Admin-OAuth2Clients-2.png)
- [static/images/en/developer/Admin-OAuth2Clients-3.png](static/images/en/developer/Admin-OAuth2Clients-3.png)
- [static/images/en/developer/developerData.png](static/images/en/developer/developerData.png)
- [static/images/en/developer/vardefs.png](static/images/en/developer/vardefs.png)

</details>



This page provides an overview of the customization and development options available in SuiteCRM, covering both the legacy 7.x series and the modern 8.x architecture. It explains the different approaches for extending SuiteCRM functionality, from simple theme modifications to complex backend and frontend extensions.

For specific guidance on installation and upgrade procedures, see [Installation and Upgrade Guides](#5). For administration and configuration topics, see [Administration](#7).

## Development Environment Requirements

SuiteCRM development requires different tools depending on the version and type of customization:

| SuiteCRM Version | PHP | Node.js | Angular CLI | yarn | Purpose |
|------------------|-----|---------|-------------|------|---------|
| 8.8.x | 8.1, 8.2, 8.3 | ^20.11.1 | ^18 | ^4.5.0 | Frontend development |
| 8.7.x | 8.1, 8.2 | ^18.10 | ^16 | ^1.22.10 | Frontend development |
| 8.4.x-8.6.x | 8.1, 8.2 | ^14.15.1 | ^12 | ^1.22.10 | Frontend development |
| 7.14.x | 7.4, 8.0, 8.1, 8.2 | - | - | - | Backend only |

**Sources:** [content/8.x/admin/Compatibility Matrix.adoc:1-381]()

## Architecture Overview

SuiteCRM offers two distinct development paradigms depending on the version:

```mermaid
graph TB
    subgraph "SuiteCRM 7.x Legacy Architecture"
        PHP7["PHP Backend"]
        SMARTY["Smarty Templates"]
        JQUERY["jQuery/JavaScript"]
        MYSQL7["MySQL/MariaDB"]
        
        PHP7 --> SMARTY
        SMARTY --> JQUERY
        PHP7 --> MYSQL7
    end
    
    subgraph "SuiteCRM 8.x Modern Architecture"
        SYMFONY["Symfony Backend"]
        ANGULAR["Angular Frontend"]
        GRAPHQL["GraphQL API"]
        MYSQL8["MySQL/MariaDB"]
        
        ANGULAR --> GRAPHQL
        GRAPHQL --> SYMFONY
        SYMFONY --> MYSQL8
    end
    
    subgraph "Development Approaches"
        THEME["Theme Customization"]
        FRONTEND["Frontend Extensions"]
        BACKEND["Backend Development"]
    end
    
    THEME --> PHP7
    THEME --> ANGULAR
    FRONTEND --> ANGULAR
    BACKEND --> PHP7
    BACKEND --> SYMFONY
    
    style ANGULAR fill:#e3f2fd
    style SYMFONY fill:#f3e5f5
    style PHP7 fill:#fff3e0
```

**Sources:** [content/8.x/developer/developer-getting-started.adoc:1-42](), [content/8.x/developer/installation-guide/backend-end-installation-guide.adoc:1-113]()

## Customization Framework Architecture

The SuiteCRM customization system uses an extension framework that allows modular modifications:

```mermaid
graph TD
    subgraph "Extension Framework Core"
        EXTDIR["custom/Extension/"]
        COMPILE["Quick Repair & Rebuild"]
        EXTFILES["Compiled Extension Files"]
    end
    
    subgraph "Extension Types"
        VARDEFS["Vardefs Extensions<br/>custom/Extension/modules/Module/Ext/Vardefs/"]
        LOGICHOOKS["Logic Hooks<br/>custom/Extension/modules/Module/Ext/LogicHooks/"]
        LANGUAGE["Language Strings<br/>custom/Extension/modules/Module/Ext/Language/"]
        LAYOUTS["Layout Definitions<br/>custom/Extension/modules/Module/Ext/Layoutdefs/"]
        SCHEDULED["Scheduled Tasks<br/>custom/Extension/modules/Schedulers/Ext/ScheduledTasks/"]
        ENTRYPOINTS["Entry Points<br/>custom/Extension/application/Ext/EntryPointRegistry/"]
    end
    
    subgraph "Compilation Targets"
        VARDEFS_EXT["vardefs.ext.php"]
        HOOKS_EXT["logichooks.ext.php"] 
        LANG_EXT["Language Files"]
        LAYOUT_EXT["layoutdefs.ext.php"]
        SCHED_EXT["scheduledtasks.ext.php"]
        ENTRY_EXT["entry_point_registry.ext.php"]
    end
    
    VARDEFS --> COMPILE
    LOGICHOOKS --> COMPILE
    LANGUAGE --> COMPILE
    LAYOUTS --> COMPILE
    SCHEDULED --> COMPILE
    ENTRYPOINTS --> COMPILE
    
    COMPILE --> VARDEFS_EXT
    COMPILE --> HOOKS_EXT
    COMPILE --> LANG_EXT
    COMPILE --> LAYOUT_EXT
    COMPILE --> SCHED_EXT
    COMPILE --> ENTRY_EXT
    
    EXTDIR --> EXTFILES
```

**Sources:** [content/developer/Extension Framework.adoc:1-163](), [content/developer/Vardefs.adoc:318-334](), [content/developer/Logic Hooks.adoc:270-282]()

## Theme Customization

Theme customization in SuiteCRM involves modifying the visual appearance and user interface elements. The approach differs significantly between versions:

### SuiteCRM 7.x Theme System
- **SuiteP Theme**: Primary theme with sub-themes (Dawn, Day, Dusk, Night)
- **SCSS Compilation**: Uses `leafo/scssphp` for stylesheet compilation
- **File Structure**: `themes/SuiteP/css/SubThemeName/`
- **Build Command**: `./vendor/bin/pscss -f compressed themes/SuiteP/css/Noon/style.scss > themes/SuiteP/css/Noon/style.css`

### SuiteCRM 8.x Theme System
- **Angular Material**: Built on Angular Material components
- **CSS Custom Properties**: Modern CSS variable system
- **Component-Based**: Styling tied to Angular components

**Sources:** [content/blog/Customizing-Subthemes.adoc:1-191]()

## Frontend Extensions

Frontend extensions allow adding new user interface components and functionality to SuiteCRM 8.x:

```mermaid
graph LR
    subgraph "Frontend Extension Development"
        DEFAULTEXT["defaultExt Template"]
        ANGULAR_APP["Angular Extension App"]
        MODULE_FED["Module Federation"]
        BUILD_SYSTEM["Webpack Build"]
    end
    
    subgraph "Extension Components"
        SIDEBAR["SidebarWidgetRegistry"]
        CHARTS["ChartRegistry"] 
        COMPONENTS["Custom Components"]
        SERVICES["Custom Services"]
    end
    
    subgraph "Build Outputs"
        REMOTE_ENTRY["remoteEntry.js"]
        PUBLIC_DIR["public/extensions/"]
        EXTENSION_CONFIG["config/extension.php"]
    end
    
    DEFAULTEXT --> ANGULAR_APP
    ANGULAR_APP --> MODULE_FED
    MODULE_FED --> BUILD_SYSTEM
    
    ANGULAR_APP --> SIDEBAR
    ANGULAR_APP --> CHARTS
    ANGULAR_APP --> COMPONENTS
    ANGULAR_APP --> SERVICES
    
    BUILD_SYSTEM --> REMOTE_ENTRY
    BUILD_SYSTEM --> PUBLIC_DIR
    
    EXTENSION_CONFIG --> REMOTE_ENTRY
```

### Key Extension Registries
- **SidebarWidgetRegistry**: `sidebarWidgetRegistry.register('default', 'widget-name', ComponentClass)`
- **ChartRegistry**: `chartRegistry.register('default', 'chart-type', ChartComponent)`

### Build Commands
- Development: `yarn run build-dev:extensionName --watch`
- Production: `yarn run build:extensionName`

**Sources:** [content/8.x/developer/extensions/frontend/older/8x-fe-extensions-getting-started.adoc:1-185](), [content/8.x/developer/extensions/frontend/examples/add-sidebar-widget.adoc:1-881](), [content/8.x/developer/extensions/frontend/examples/add-charts-extension.adoc:1-392]()

## Backend Development

Backend development encompasses server-side customizations including business logic, data processing, and API extensions:

### Logic Hooks System
Logic hooks allow injecting custom code at specific points in the application lifecycle:

```mermaid
graph TD
    subgraph "Logic Hook Types"
        MODULE_HOOKS["Module Hooks<br/>before_save, after_save<br/>before_delete, after_delete"]
        APP_HOOKS["Application Hooks<br/>after_user_load<br/>after_entry_point"]
        USER_HOOKS["User Hooks<br/>after_login, after_logout<br/>login_failed"]
        JOB_HOOKS["Job Queue Hooks<br/>job_failure, job_failure_retry"]
    end
    
    subgraph "Hook Registration"
        HOOK_FILE["custom/Extension/modules/Module/Ext/LogicHooks/"]
        HOOK_ARRAY["hook_array definition"]
        HOOK_CLASS["Custom Hook Class"]
    end
    
    subgraph "Hook Execution"
        SORT_ORDER["Sort Order (numeric)"]
        HOOK_METHOD["Hook Method(bean, event, arguments)"]
        BUSINESS_LOGIC["Custom Business Logic"]
    end
    
    MODULE_HOOKS --> HOOK_FILE
    APP_HOOKS --> HOOK_FILE
    USER_HOOKS --> HOOK_FILE
    JOB_HOOKS --> HOOK_FILE
    
    HOOK_FILE --> HOOK_ARRAY
    HOOK_ARRAY --> HOOK_CLASS
    HOOK_CLASS --> SORT_ORDER
    SORT_ORDER --> HOOK_METHOD
    HOOK_METHOD --> BUSINESS_LOGIC
```

### Vardefs and Field Definitions
Vardefs define the structure and behavior of module fields:

```mermaid
graph LR
    subgraph "Vardef Components"
        FIELD_DEF["Field Definitions<br/>name, type, len, options"]
        RELATIONSHIP_DEF["Relationships<br/>lhs_module, rhs_module<br/>relationship_type"]
        INDEX_DEF["Database Indices<br/>name, type, fields"]
        TEMPLATE_DEF["Vardef Templates<br/>default, assignable, person"]
    end
    
    subgraph "Vardef Files"
        MODULE_VARDEF["modules/Module/vardefs.php"]
        CUSTOM_VARDEF["custom/Extension/modules/Module/Ext/Vardefs/"]
        COMPILED_VARDEF["custom/modules/Module/Ext/Vardefs/vardefs.ext.php"]
    end
    
    subgraph "Field Types"
        BASIC_TYPES["varchar, text, int<br/>bool, date, datetime"]
        RELATIONSHIP_TYPES["relate, link<br/>enum, multienum"]
        CUSTOM_TYPES["Custom Field Types"]
    end
    
    FIELD_DEF --> MODULE_VARDEF
    RELATIONSHIP_DEF --> MODULE_VARDEF
    INDEX_DEF --> MODULE_VARDEF
    TEMPLATE_DEF --> MODULE_VARDEF
    
    MODULE_VARDEF --> CUSTOM_VARDEF
    CUSTOM_VARDEF --> COMPILED_VARDEF
    
    BASIC_TYPES --> FIELD_DEF
    RELATIONSHIP_TYPES --> FIELD_DEF
    CUSTOM_TYPES --> FIELD_DEF
```

### Scheduled Tasks and Job Queue
Custom scheduled tasks for background processing:

- **Scheduler Definition**: `custom/Extension/modules/Schedulers/Ext/ScheduledTasks/TaskName.php`
- **Job Queue**: `SugarJobQueue` and `SchedulersJob` classes
- **Task Method**: Function added to `$job_strings` array

**Sources:** [content/developer/Logic Hooks.adoc:1-356](), [content/developer/Vardefs.adoc:1-404](), [content/developer/Scheduled Tasks.adoc:1-376]()

## Record Mappers (SuiteCRM 8.x)

Record mappers provide flexible data transformation between internal and external formats:

### Mapper Architecture
- **API Level**: Execute before/after API operations
- **Entity Level**: Execute during entity operations (save, retrieve)
- **Types**: Field Mapper, Field Type Mapper, Record Mapper
- **Modes**: retrieve, list, save

**Sources:** [content/8.x/developer/extensions/backend/record-mappers/_index.en.adoc:1-115]()

## Development Workflow

### Version 7.x Development
1. **Setup**: Install SuiteCRM 7.x package
2. **Customize**: Use Studio, custom directory, or extension framework
3. **Deploy**: Copy custom files, run Quick Repair & Rebuild

### Version 8.x Development  
1. **Setup**: Install dev package or build from source
2. **Install Dependencies**: `yarn install`
3. **Enable Extension**: Modify `extensions/defaultExt/config/extension.php`
4. **Develop**: Build extensions with `yarn run build-dev:extensionName --watch`
5. **Deploy**: Build production with `yarn run build:extensionName`

### Migration Considerations
- **No Direct Migration Path**: 7.x to 8.x requires complete redevelopment
- **Architecture Change**: Move from PHP/Smarty to Angular/Symfony
- **API Evolution**: v4.1 (SOAP/REST) to v8 (JSON API/OAuth2)

**Sources:** [content/8.x/developer/developer-getting-started.adoc:1-42](), [content/8.x/developer/extensions/frontend/older/8x-fe-extensions-getting-started.adoc:1-185]()