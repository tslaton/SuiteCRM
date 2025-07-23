# Core Architecture

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [data/SugarBean.php](data/SugarBean.php)
- [include/EditView/SugarVCR.php](include/EditView/SugarVCR.php)
- [include/HTTP_WebDAV_Server/Server.php](include/HTTP_WebDAV_Server/Server.php)
- [include/MVC/SugarApplication.php](include/MVC/SugarApplication.php)
- [include/MVC/View/SugarView.php](include/MVC/View/SugarView.php)
- [include/MVC/View/tpls/displayLoginJS.tpl](include/MVC/View/tpls/displayLoginJS.tpl)
- [include/SugarTheme/SugarTheme.php](include/SugarTheme/SugarTheme.php)
- [include/Sugar_Smarty.php](include/Sugar_Smarty.php)
- [include/social/facebook/facebook_sdk/src/base_facebook.php](include/social/facebook/facebook_sdk/src/base_facebook.php)
- [include/social/facebook/facebook_sdk/src/facebook.php](include/social/facebook/facebook_sdk/src/facebook.php)
- [modules/ModuleBuilder/tpls/MBModule/dropdown.tpl](modules/ModuleBuilder/tpls/MBModule/dropdown.tpl)
- [modules/ModuleBuilder/views/view.dropdown.php](modules/ModuleBuilder/views/view.dropdown.php)
- [modules/UserPreferences/UserPreference.php](modules/UserPreferences/UserPreference.php)
- [modules/Users/Logout.php](modules/Users/Logout.php)
- [themes/SuiteP/images/edit_inline.gif](themes/SuiteP/images/edit_inline.gif)

</details>



## Purpose and Scope

This document covers the foundational architectural patterns and systems that underpin SuiteCRM's operation. It focuses on the Model-View-Controller (MVC) framework, the SugarBean ORM system, view rendering with templates, theme management, and configuration systems that form the technical foundation for all CRM functionality.

For information about specific business modules like email management or campaign systems, see [Core Business Modules](#4). For user interface components and theming details, see [User Interface System](#3). For administration and system configuration, see [Administration & Configuration](#5).

## MVC Request Flow Architecture

SuiteCRM implements a traditional MVC pattern where `SugarApplication` orchestrates the request lifecycle, delegating to controllers and views for processing.

### Application Bootstrap and Request Processing

```mermaid
graph TD
    A["index.php"] --> B["SugarApplication"]
    B --> C["execute()"]
    C --> D["ControllerFactory::getController()"]
    D --> E["SugarController"]
    E --> F["ViewFactory::getView()"]
    F --> G["SugarView"]
    G --> H["Sugar_Smarty"]
    H --> I["Template Output"]
    
    C --> J["loadUser()"]
    C --> K["ACLFilter()"]
    C --> L["loadLanguages()"]
    C --> M["loadDisplaySettings()"]
    
    J --> N["AuthenticationController"]
    M --> O["SugarThemeRegistry"]
```

The `SugarApplication::execute()` method manages the complete request lifecycle:

1. **Module Resolution**: Determines target module from `$_REQUEST['module']` or defaults to configured home module
2. **Authentication**: Validates user session through `AuthenticationController` 
3. **Controller Instantiation**: `ControllerFactory` creates module-specific controllers
4. **View Processing**: Controllers delegate to `SugarView` subclasses for output generation

**Sources:** [include/MVC/SugarApplication.php:74-103]()

### Controller and View Interaction

```mermaid
graph LR
    A["SugarApplication"] --> B["ControllerFactory"]
    B --> C["SugarController"]
    C --> D["preProcess()"]
    C --> E["action methods"]
    C --> F["ViewFactory"]
    F --> G["SugarView subclass"]
    G --> H["preDisplay()"]
    G --> I["display()"]
    I --> J["Sugar_Smarty::fetch()"]
```

Controllers handle business logic and data preparation, while views manage presentation logic and template rendering. The `SugarView::process()` method coordinates header display, content rendering, and footer output.

**Sources:** [include/MVC/View/SugarView.php:185-281](), [include/MVC/SugarApplication.php:86-101]()

## Data Layer Architecture (SugarBean)

`SugarBean` serves as SuiteCRM's primary ORM and base class for all business objects, providing CRUD operations, relationship management, and data validation.

### SugarBean Core Structure

```mermaid
graph TB
    A["SugarBean"] --> B["VardefManager"]
    A --> C["DBManagerFactory"]
    A --> D["DynamicField"]
    A --> E["RelationshipFactory"]
    
    B --> F["field_defs"]
    B --> G["field_name_map"] 
    C --> H["Database Operations"]
    D --> I["Custom Fields"]
    E --> J["Relationship Management"]
    
    A --> K["save()"]
    A --> L["retrieve()"]
    A --> M["delete()"]
    A --> N["get_list()"]
    
    K --> O["Logic Hooks"]
    L --> O
    M --> O
```

### Database Operations and Field Management

The `SugarBean` constructor initializes field definitions through `VardefManager::loadVardef()` and establishes database connections via `DBManagerFactory::getInstance()`. Field definitions stored in `field_defs` array control data types, validation rules, and relationship mappings.

```mermaid
graph LR
    A["SugarBean::__construct()"] --> B["VardefManager::loadVardef()"]
    B --> C["dictionary loading"]
    C --> D["field_defs population"]
    D --> E["DynamicField::setup()"]
    E --> F["Custom field integration"]
    
    A --> G["DBManagerFactory::getInstance()"]
    G --> H["Database connection"]
```

Key data operations include:
- **CRUD Methods**: `save()`, `retrieve()`, `delete()` handle persistence
- **List Operations**: `get_list()` and `get_union_related_list()` for queries
- **Relationship Management**: Link and unlink related records through relationship definitions
- **Field Processing**: Date/time conversion, number formatting, and custom field handling

**Sources:** [data/SugarBean.php:445-517](), [data/SugarBean.php:458-499]()

### Bean Lifecycle and Hooks

```mermaid
graph TD
    A["Bean Creation"] --> B["populateDefaultValues()"]
    B --> C["save()"]
    C --> D["before_save hooks"]
    D --> E["Database INSERT/UPDATE"]
    E --> F["after_save hooks"]
    
    G["Bean Retrieval"] --> H["retrieve()"]
    H --> I["Database SELECT"]
    I --> J["field population"]
    J --> K["after_retrieve hooks"]
    
    L["Bean Deletion"] --> M["mark_deleted()"]
    M --> N["before_delete hooks"]
    N --> O["Database UPDATE deleted=1"]
    O --> P["after_delete hooks"]
```

**Sources:** [data/SugarBean.php:544-595](), [data/SugarBean.php:809-964]()

## View and Template System

SuiteCRM uses `Sugar_Smarty` (a Smarty wrapper) for template processing, integrated with the `SugarView` hierarchy for presentation logic.

### Template Processing Flow

```mermaid
graph TB
    A["SugarView::display()"] --> B["_initSmarty()"]
    B --> C["Sugar_Smarty creation"]
    C --> D["Template directory setup"]
    D --> E["Plugin registration"]
    
    F["SugarView::displayHeader()"] --> G["Sugar_Smarty::assign()"]
    G --> H["Global variables"]
    H --> I["APP strings"]
    H --> J["MOD strings"]
    H --> K["Theme variables"]
    
    L["Template rendering"] --> M["Sugar_Smarty::fetch()"]
    M --> N["loadTemplatePath()"]
    N --> O["Theme-aware template resolution"]
    O --> P["HTML output"]
```

### Template Resolution and Theming

The `Sugar_Smarty::loadTemplatePath()` method implements theme-aware template resolution:

1. **Theme Template Check**: Look for template in current theme directory
2. **Custom Template Check**: Check for customized versions
3. **Default Fallback**: Use base template if theme-specific version not found

**Sources:** [include/Sugar_Smarty.php:341-353](), [include/MVC/View/SugarView.php:167-180]()

### View Hierarchy and Specialization

```mermaid
graph TD
    A["SugarView"] --> B["ViewDetail"]
    A --> C["ViewEdit"] 
    A --> D["ViewList"]
    A --> E["Custom Views"]
    
    B --> F["DetailView templates"]
    C --> G["EditView templates"]
    D --> H["ListView templates"]
    
    I["ViewFactory"] --> J["Module-specific views"]
    J --> K["themes/*/modules/*/tpls/"]
```

**Sources:** [include/MVC/View/SugarView.php:49-148]()

## Theme Management System

`SugarTheme` provides comprehensive theming capabilities including CSS compilation, image management, JavaScript processing, and template resolution.

### Theme Architecture and Resource Management

```mermaid
graph TB
    A["SugarThemeRegistry"] --> B["SugarTheme instances"]
    B --> C["Theme Configuration"]
    C --> D["CSS Processing"]
    C --> E["Image Management"]
    C --> F["JavaScript Handling"]
    C --> G["Template Resolution"]
    
    D --> H["style.css compilation"]
    D --> I["Sprite generation"]
    E --> J["Image caching"]
    E --> K["SVG support"]
    F --> L["JS minification"]
    G --> M["Template inheritance"]
```

### Theme Resource Resolution

The theme system implements a hierarchical resource resolution pattern:

```mermaid
graph LR
    A["Resource Request"] --> B["Current Theme"]
    B --> C["Custom Override?"]
    C -->|Yes| D["custom/themes/*/"]
    C -->|No| E["Parent Theme?"]
    E -->|Yes| F["Parent theme check"]
    E -->|No| G["Default theme"]
    F --> G
    D --> H["Resource served"]
    G --> H
```

Key theme methods:
- **`getCSS()`**: Compiles CSS with sprite support and color/font variants
- **`getTemplate()`**: Resolves template files with inheritance
- **`getImage()`**: Handles image serving with SVG preference and sprite optimization
- **`getJS()`**: Manages JavaScript compilation and minification

**Sources:** [include/SugarTheme/SugarTheme.php:618-677](), [include/SugarTheme/SugarTheme.php:699-727](), [include/SugarTheme/SugarTheme.php:742-781]()

## Configuration Management

The configuration system centers on `UserPreference` for user-specific settings and global `$sugar_config` for system-wide configuration.

### User Preference Architecture

```mermaid
graph TB
    A["UserPreference"] --> B["Session Storage"]
    A --> C["Database Persistence"]
    A --> D["Default Values"]
    
    B --> E["_SESSION[username_PREFERENCES]"]
    C --> F["user_preferences table"]
    D --> G["sugar_config defaults"]
    
    H["getPreference()"] --> I["Session check"]
    I -->|Hit| J["Return cached value"]
    I -->|Miss| K["Database load"]
    K --> L["loadPreferences()"]
    L --> M["Cache in session"]
    
    N["setPreference()"] --> O["Update session"]
    O --> P["Mark for DB save"]
    P --> Q["savePreferencesToDB()"]
```

### Configuration Data Flow

```mermaid
graph LR
    A["User Login"] --> B["loadDisplaySettings()"]
    B --> C["Theme selection"]
    C --> D["SugarThemeRegistry::set()"]
    
    E["Preference Change"] --> F["setPreference()"]
    F --> G["Session update"]
    G --> H["Deferred save flag"]
    
    I["Request End"] --> J["sugar_cleanup()"]
    J --> K["savePreferencesToDB()"]
    K --> L["Database persistence"]
```

The preference system uses lazy loading and deferred saving for performance:
- **Lazy Loading**: Categories loaded on first access via `loadPreferences()`
- **Session Caching**: Preferences cached in `$_SESSION` to avoid repeated DB queries  
- **Deferred Persistence**: Changes batched and saved during `sugar_cleanup()`

**Sources:** [modules/UserPreferences/UserPreference.php:97-125](), [modules/UserPreferences/UserPreference.php:172-200](), [modules/UserPreferences/UserPreference.php:336-377]()

## Component Integration Patterns

### Request Processing Integration

```mermaid
graph TB
    A["HTTP Request"] --> B["SugarApplication::execute()"]
    B --> C["Authentication & ACL"]
    B --> D["Theme Loading"]
    B --> E["Language Loading"]
    B --> F["Controller Processing"]
    
    F --> G["SugarBean Operations"]
    F --> H["Business Logic"]
    F --> I["View Rendering"]
    
    I --> J["SugarView::process()"]
    J --> K["Sugar_Smarty Templating"]
    J --> L["SugarTheme Resources"]
    
    M["UserPreference"] --> D
    M --> E
    N["VardefManager"] --> G
    O["DBManagerFactory"] --> G
```

### Cross-Component Dependencies

The architecture maintains loose coupling through factory patterns and registry systems:

- **`DBManagerFactory`**: Provides database abstraction for all data operations
- **`SugarThemeRegistry`**: Manages theme instances and switching
- **`VardefManager`**: Centralizes field definition loading and caching
- **`ViewFactory`** and **`ControllerFactory`**: Enable modular MVC component loading

**Sources:** [include/MVC/SugarApplication.php:96-101](), [include/MVC/View/SugarView.php:207-216](), [data/SugarBean.php:449-456]()