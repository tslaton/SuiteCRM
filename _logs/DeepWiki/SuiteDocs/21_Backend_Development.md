# Backend Development

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [content/admin/Troubleshooting and Support.adoc](content/admin/Troubleshooting and Support.adoc)
- [content/admin/Troubleshooting and Support.ru.adoc](content/admin/Troubleshooting and Support.ru.adoc)
- [content/admin/releases/7.9.x/_index.en.adoc](content/admin/releases/7.9.x/_index.en.adoc)
- [content/admin/releases/_index.ru.adoc](content/admin/releases/_index.ru.adoc)
- [content/blog/ListView-conditional-formatting.adoc](content/blog/ListView-conditional-formatting.adoc)
- [content/blog/larger-upgrades.ru.adoc](content/blog/larger-upgrades.ru.adoc)
- [content/community/contributing-to-docs/guidelines.adoc](content/community/contributing-to-docs/guidelines.adoc)
- [content/community/contributing-to-docs/simple-issue.es.adoc](content/community/contributing-to-docs/simple-issue.es.adoc)
- [content/community/contributing-to-docs/translate.adoc](content/community/contributing-to-docs/translate.adoc)
- [content/developer/Best Practices.adoc](content/developer/Best Practices.adoc)
- [content/developer/Config.adoc](content/developer/Config.adoc)
- [content/developer/Controllers.adoc](content/developer/Controllers.adoc)
- [content/developer/Entry Points.adoc](content/developer/Entry Points.adoc)
- [content/developer/Extension Framework.adoc](content/developer/Extension Framework.adoc)
- [content/developer/Further Resources.adoc](content/developer/Further Resources.adoc)
- [content/developer/Language Strings.adoc](content/developer/Language Strings.adoc)
- [content/developer/Logging.adoc](content/developer/Logging.adoc)
- [content/developer/Logic Hooks.adoc](content/developer/Logic Hooks.adoc)
- [content/developer/Metadata.adoc](content/developer/Metadata.adoc)
- [content/developer/Module Installer.adoc](content/developer/Module Installer.adoc)
- [content/developer/Scheduled Tasks.adoc](content/developer/Scheduled Tasks.adoc)
- [content/developer/Translate strings.adoc](content/developer/Translate strings.adoc)
- [content/developer/Vardefs.adoc](content/developer/Vardefs.adoc)
- [content/developer/_index.es.adoc](content/developer/_index.es.adoc)
- [content/developer/scripts/codecept.bat](content/developer/scripts/codecept.bat)
- [content/developer/scripts/codecept.sh](content/developer/scripts/codecept.sh)
- [static/images/en/developer/developerData.png](static/images/en/developer/developerData.png)
- [static/images/en/developer/vardefs.png](static/images/en/developer/vardefs.png)
- [static/images/ru/blog/upgrading-strategies.png](static/images/ru/blog/upgrading-strategies.png)

</details>



This page covers the core backend development concepts and systems in SuiteCRM, including data definition, custom business logic, background processing, and extensibility frameworks. This documentation focuses on server-side PHP development and database customization.

For frontend customization using Angular, see [Frontend Extensions](#6.2). For visual theme modifications, see [Theme Customization](#6.1).

## Backend Architecture Overview

SuiteCRM's backend architecture provides several key systems for customization and extension:

```mermaid
graph TB
    subgraph "Data Layer"
        Vardefs["Vardefs<br/>Field & Relationship<br/>Definitions"]
        DB["Database<br/>Tables & Schema"]
        Beans["SugarBean<br/>ORM Layer"]
    end
    
    subgraph "Business Logic Layer"
        LogicHooks["Logic Hooks<br/>Event-Driven Code"]
        Schedulers["Scheduled Tasks<br/>Background Jobs"]
        Controllers["Controllers<br/>Request Handling"]
    end
    
    subgraph "Extension Framework"
        ExtFramework["Extension Framework<br/>Modular Customization"]
        ModuleInstaller["Module Installer<br/>Package Management"]
        CustomDir["custom/ Directory<br/>Upgrade-Safe Storage"]
    end
    
    subgraph "Supporting Systems"
        Logging["Logging System<br/>LoggerManager"]
        Config["Configuration<br/>config.php"]
        Language["Language Strings<br/>Internationalization"]
    end
    
    Vardefs --> DB
    DB --> Beans
    Beans --> LogicHooks
    LogicHooks --> Schedulers
    Controllers --> Beans
    
    ExtFramework --> Vardefs
    ExtFramework --> LogicHooks
    ExtFramework --> Language
    ModuleInstaller --> ExtFramework
    
    CustomDir --> ExtFramework
    Config --> Beans
    Logging --> LogicHooks
    Logging --> Schedulers
```

Sources: [content/developer/Vardefs.adoc:1-404](), [content/developer/Logic Hooks.adoc:1-356](), [content/developer/Extension Framework.adoc:1-163]()

## Vardefs System

Vardefs (Variable Definitions) are the foundation of SuiteCRM's data layer, defining fields, relationships, and database structure for modules.

### Core Vardefs Structure

Vardefs are defined in `$dictionary` arrays and specify module metadata:

| Component | Purpose | Location |
|-----------|---------|----------|
| **Fields** | Define field properties and types | `modules/<Module>/vardefs.php` |
| **Relationships** | Define inter-module connections | `$dictionary['Module']['relationships']` |
| **Indices** | Define database indexes | `$dictionary['Module']['indices']` |
| **Templates** | Apply common field sets | `VardefManager::createVardef()` |

### Field Types and Properties

```mermaid
graph LR
    subgraph "Field Types"
        BasicTypes["Basic Types<br/>varchar, text, int<br/>date, datetime, bool"]
        RelationTypes["Relation Types<br/>relate, link<br/>id_name, rname"]
        SpecialTypes["Special Types<br/>enum, multienum<br/>phone, currency"]
    end
    
    subgraph "Field Properties"
        CoreProps["Core Properties<br/>name, type, len<br/>required, audited"]
        DisplayProps["Display Properties<br/>vname, studio<br/>massupdate, unified_search"]
        DBProps["Database Properties<br/>dbtype, source<br/>default, isnull"]
    end
    
    BasicTypes --> CoreProps
    RelationTypes --> CoreProps
    SpecialTypes --> CoreProps
    
    CoreProps --> DisplayProps
    CoreProps --> DBProps
```

### Customizing Vardefs

Custom vardefs are placed in the Extension Framework:

```
custom/Extension/modules/<TheModule>/Ext/Vardefs/
```

Sources: [content/developer/Vardefs.adoc:10-404](), [content/developer/Extension Framework.adoc:94-96]()

## Logic Hooks System

Logic Hooks provide event-driven customization points throughout SuiteCRM's execution flow.

### Hook Types and Execution Points

```mermaid
graph TD
    subgraph "Application Hooks"
        AppHooks["after_user_load<br/>after_entry_point<br/>after_ui_footer<br/>server_round_trip"]
    end
    
    subgraph "User Hooks"
        UserHooks["after_login<br/>after_logout<br/>before_logout<br/>login_failed"]
    end
    
    subgraph "Module Hooks"
        ModuleHooks["before_save<br/>after_save<br/>before_delete<br/>after_delete<br/>after_retrieve<br/>process_record"]
    end
    
    subgraph "Job Queue Hooks"
        JobHooks["job_failure<br/>job_failure_retry"]
    end
    
    AppHooks --> LogicHookRegistry["Logic Hook Registry<br/>$hook_array"]
    UserHooks --> LogicHookRegistry
    ModuleHooks --> LogicHookRegistry
    JobHooks --> LogicHookRegistry
    
    LogicHookRegistry --> HookExecution["Hook Execution<br/>Sort Order Priority"]
```

### Hook Definition Structure

Logic hooks are defined in `$hook_array` with five components:

1. **Sort Order** - Execution priority (lower numbers execute first)
2. **Hook Label** - Descriptive name for the hook
3. **File Path** - Location of the hook class
4. **Class Name** - PHP class containing the hook method
5. **Method Name** - Function to execute

### Implementation Locations

| Hook Type | Definition Location |
|-----------|-------------------|
| **Application** | `custom/Extension/application/Ext/LogicHooks/` |
| **Module** | `custom/Extension/modules/<Module>/Ext/LogicHooks/` |
| **User** | `custom/Extension/application/Ext/LogicHooks/` |

Sources: [content/developer/Logic Hooks.adoc:9-356](), [content/developer/Extension Framework.adoc:75-78]()

## Extension Framework

The Extension Framework provides a modular system for customizations that consolidates files during Quick Repair and Rebuild.

### Standard Extensions

```mermaid
graph TB
    subgraph "Core Extensions"
        Vardefs["Vardefs<br/>vardefs.ext.php"]
        LogicHooks["LogicHooks<br/>logichooks.ext.php"]
        Language["Language<br/>*.lang.php"]
        Layoutdefs["Layoutdefs<br/>layoutdefs.ext.php"]
    end
    
    subgraph "Administrative Extensions"
        Admin["Administration<br/>administration.ext.php"]
        ScheduledTasks["ScheduledTasks<br/>scheduledtasks.ext.php"]
        EntryPoints["EntryPointRegistry<br/>entry_point_registry.ext.php"]
        Menus["Menus<br/>menu.ext.php"]
    end
    
    subgraph "Advanced Extensions"
        ActionMaps["Action Maps<br/>action_view_map.ext.php"]
        Utils["Utils<br/>custom_utils.ext.php"]
        JSGroupings["JSGroupings<br/>jsgroups.ext.php"]
        Include["Include<br/>modules.ext.php"]
    end
    
    QuickRepair["Quick Repair<br/>& Rebuild"] --> Vardefs
    QuickRepair --> LogicHooks
    QuickRepair --> Admin
    QuickRepair --> ScheduledTasks
```

### Extension Framework Workflow

The Extension Framework follows this process:

1. **File Placement** - Custom files placed in `custom/Extension/` structure
2. **Consolidation** - Quick Repair scans extension directories
3. **Compilation** - Files merged into single `.ext.php` files
4. **Loading** - Compiled extensions loaded by SuiteCRM

### Override Mechanism

Files prefixed with `_override` are processed after standard extensions, ensuring precedence:

```
custom/Extension/modules/Accounts/Ext/Vardefs/_override_my_field_change.php
```

Sources: [content/developer/Extension Framework.adoc:8-163]()

## Scheduled Tasks System

SuiteCRM's scheduler handles both recurring tasks and one-time job queue processing.

### Scheduler Architecture

```mermaid
graph TB
    subgraph "Scheduler Definition"
        SchedulerExt["ScheduledTasks Extension<br/>custom/Extension/modules/Schedulers/Ext/ScheduledTasks/"]
        JobStrings["$job_strings[]<br/>Method Registration"]
        LanguageFile["Language File<br/>LBL_METHODNAME"]
    end
    
    subgraph "Job Queue System"
        JobCreation["Job Creation<br/>SchedulersJob"]
        JobQueue["SugarJobQueue<br/>submitJob()"]
        JobExecution["Job Execution<br/>RunnableSchedulerJob"]
    end
    
    subgraph "Execution Flow"
        CronJob["Cron/Scheduled Task<br/>php -f cron.php"]
        Scheduler["Scheduler Process"]
        JobRunner["Job Runner"]
    end
    
    SchedulerExt --> JobStrings
    JobStrings --> LanguageFile
    
    JobCreation --> JobQueue
    JobQueue --> CronJob
    CronJob --> Scheduler
    Scheduler --> JobRunner
    JobRunner --> JobExecution
```

### Scheduled Task Implementation

Scheduled tasks require three components:

1. **Method Definition** - Function added to `$job_strings` array
2. **Function Implementation** - Actual task logic
3. **Language Label** - User-friendly name with key `LBL_UPPERMETHODNAME`

### Job Queue for Asynchronous Processing

The job queue allows deferring long-running tasks:

```php
// Job creation pattern
$scheduledJob = new SchedulersJob();
$scheduledJob->name = "Background Task";
$scheduledJob->assigned_user_id = '1';
$scheduledJob->data = json_encode($taskData);
$scheduledJob->target = "class::TaskClass";
$queue = new SugarJobQueue();
$queue->submitJob($scheduledJob);
```

Sources: [content/developer/Scheduled Tasks.adoc:10-376]()

## Module Installer

The Module Installer packages customizations for distribution and installation across SuiteCRM instances.

### Package Structure

```mermaid
graph LR
    subgraph "Package Components"
        ManifestFile["manifest.php<br/>Package Definition"]
        InstallDefs["$installdefs<br/>Installation Instructions"]
        UpgradeDefs["$upgrade_manifest<br/>Upgrade Handling"]
    end
    
    subgraph "Package Contents"
        CustomFiles["Custom Files<br/>PHP, Templates, Images"]
        LanguageFiles["Language Files<br/>Translations"]
        Vardefs["Vardefs<br/>Field Definitions"]
        LogicHookFiles["Logic Hooks<br/>Custom Business Logic"]
    end
    
    subgraph "Installation Types"
        ModuleType["module<br/>New Modules"]
        PatchType["patch<br/>System Updates"]
        ThemeType["theme<br/>Visual Themes"]
        LangType["langpack<br/>Translations"]
    end
    
    ManifestFile --> CustomFiles
    InstallDefs --> CustomFiles
    ManifestFile --> ModuleType
```

### Key Manifest Components

| Component | Purpose | Key Properties |
|-----------|---------|----------------|
| **$manifest** | Package metadata | `name`, `version`, `type`, `dependencies` |
| **$installdefs** | Installation rules | `copy`, `vardefs`, `logic_hooks`, `custom_fields` |
| **$upgrade_manifest** | Upgrade handling | Version-specific `installdefs` |

### Installation Definition Types

The `$installdefs` array supports various installation components:

- **copy** - File and directory copying
- **vardefs** - Field and relationship definitions  
- **logic_hooks** - Event-driven custom code
- **custom_fields** - New field creation
- **language** - Translation files
- **administration** - Admin panel additions

Sources: [content/developer/Module Installer.adoc:9-524]()

## Configuration and Logging

### Configuration System

SuiteCRM uses two main configuration files:

| File | Purpose | Modification |
|------|---------|--------------|
| **config.php** | Core settings, database config, site URL | Manual editing for migrations |
| **config_override.php** | Admin-configurable overrides | Modified through Admin interface |

### Logging System

```mermaid
graph LR
    subgraph "Logging Components"
        LoggerManager["LoggerManager::getLogger()"]
        LogLevels["Log Levels<br/>debug, info, warn<br/>error, fatal, security"]
        LogFile["suitecrm.log<br/>Root Directory"]
    end
    
    subgraph "Log Output Format"
        LogFormat["Date ProcessId UserId LogLevel Message<br/>Tue Apr 28 16:52:21 2015 [15006][1][DEBUG]"]
    end
    
    LoggerManager --> LogLevels
    LogLevels --> LogFile
    LogFile --> LogFormat
```

The logging system provides debug capabilities with configurable verbosity levels. Production instances typically use `error` or `fatal` levels, while development uses `debug`.

Sources: [content/developer/Config.adoc:9-123](), [content/developer/Logging.adoc:9-91]()

## Best Practices

### Development Environment

- **Use development instances** - Never develop directly on production
- **Version control** - Track customizations with Git or similar systems
- **Regular backups** - Maintain file and database backups before changes

### Customization Guidelines

- **Extension Framework** - Use extension directories for upgrade-safe customizations
- **Custom directory** - Place customizations in `custom/` structure
- **Logic hooks over core modifications** - Prefer event-driven customization
- **Proper testing** - Test in development before deploying to production

### Performance Considerations

- **Job queue for heavy tasks** - Use scheduled jobs for long-running operations
- **Efficient database queries** - Optimize custom database operations
- **Appropriate log levels** - Use less verbose logging in production

Sources: [content/developer/Best Practices.adoc:7-76]()