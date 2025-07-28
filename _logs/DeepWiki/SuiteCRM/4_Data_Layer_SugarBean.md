# Data Layer (SugarBean)

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [data/SugarBean.php](data/SugarBean.php)
- [include/Sugar_Smarty.php](include/Sugar_Smarty.php)

</details>



## Purpose and Scope

The Data Layer in SuiteCRM is built around the `SugarBean` class, which serves as the foundational Object-Relational Mapping (ORM) system for all business entities. This layer provides comprehensive database abstraction, field management, relationship handling, and business object lifecycle management. The SugarBean system integrates with the templating engine to enable dynamic content rendering and supports extensibility through custom fields and business logic hooks.

For information about the MVC framework that utilizes this data layer, see [MVC Framework](#2.1). For configuration management that works with the data layer, see [Configuration System](#2.3).

## Core Architecture

The SugarBean data layer follows a pattern where each business module extends the base `SugarBean` class to inherit common functionality while implementing module-specific behavior. The system manages object persistence, field definitions, relationships, and business rules through a unified interface.

```mermaid
graph TB
    subgraph "Base Layer"
        SugarBean["SugarBean"]
        DBManager["DBManager"]
        VardefManager["VardefManager"]
        DynamicField["DynamicField"]
    end
    
    subgraph "Business Objects"
        Account["Account extends SugarBean"]
        Contact["Contact extends SugarBean"]
        Lead["Lead extends SugarBean"]
        Opportunity["Opportunity extends SugarBean"]
    end
    
    subgraph "Field Management"
        field_defs["field_defs"]
        field_name_map["field_name_map"]
        column_fields["column_fields"]
        custom_fields["custom_fields"]
    end
    
    subgraph "Database Layer"
        database[("Database")]
        audit_tables[("Audit Tables")]
        custom_tables[("Custom Tables")]
    end
    
    SugarBean --> DBManager
    SugarBean --> VardefManager
    SugarBean --> DynamicField
    
    Account --> SugarBean
    Contact --> SugarBean
    Lead --> SugarBean
    Opportunity --> SugarBean
    
    SugarBean --> field_defs
    SugarBean --> field_name_map
    SugarBean --> column_fields
    DynamicField --> custom_fields
    
    DBManager --> database
    SugarBean --> audit_tables
    DynamicField --> custom_tables
```

**Sources:** [data/SugarBean.php:49-517]()

The `SugarBean` constructor initializes the data layer by loading field definitions from vardefs, setting up custom fields, and establishing database connections. Each bean maintains its own field definitions and database metadata.

## Database Operations and CRUD Lifecycle

The SugarBean implements a comprehensive CRUD (Create, Read, Update, Delete) system with built-in support for auditing, optimistic locking, and business logic hooks. The `save()` method serves as the primary entry point for data persistence.

```mermaid
graph TB
    subgraph "CRUD Operations"
        save["save()"]
        retrieve["retrieve()"]
        delete_method["mark_deleted()"]
        get_list["get_list()"]
    end
    
    subgraph "Save Process Flow"
        cleanBean["cleanBean()"]
        optimistic_lock["_checkOptimisticLocking()"]
        before_save["before_save logic hooks"]
        db_operation["Database INSERT/UPDATE"]
        after_save["after_save logic hooks"]
        audit_save["saveAuditRecords()"]
    end
    
    subgraph "Retrieve Process"
        populate_from_row["populateFromRow()"]
        process_dates["processDateFields()"]
        load_relationships["load_relationship()"]
        custom_retrieve["retrieveCustomFields()"]
    end
    
    subgraph "Database Interaction"
        DBManagerFactory["DBManagerFactory::getInstance()"]
        query_methods["query() / limitQuery()"]
        fetchByAssoc["fetchByAssoc()"]
    end
    
    save --> cleanBean
    cleanBean --> optimistic_lock
    optimistic_lock --> before_save
    before_save --> db_operation
    db_operation --> after_save
    after_save --> audit_save
    
    retrieve --> populate_from_row
    populate_from_row --> process_dates
    process_dates --> load_relationships
    load_relationships --> custom_retrieve
    
    db_operation --> DBManagerFactory
    DBManagerFactory --> query_methods
    query_methods --> fetchByAssoc
```

**Sources:** [data/SugarBean.php:2309-2575](), [data/SugarBean.php:449](), [data/SugarBean.php:3143-3352]()

The save process includes data sanitization through `cleanBean()`, optimistic locking checks, and automatic audit trail generation. The system supports both insert and update operations based on the presence of an ID field.

## Field Definition and Metadata System

SugarBean uses a sophisticated field definition system (vardefs) to manage field metadata, validation rules, and database schema information. The system supports both standard and custom fields through the `DynamicField` component.

```mermaid
graph TB
    subgraph "Vardef System"
        dictionary["$dictionary global"]
        VardefManager_load["VardefManager::loadVardef()"]
        object_vardefs["Object-specific vardefs"]
    end
    
    subgraph "Field Collections"
        field_defs["field_defs[]"]
        field_name_map["field_name_map[]"]
        column_fields["column_fields[]"]
        list_fields["list_fields[]"]
        required_fields["required_fields[]"]
    end
    
    subgraph "Custom Fields"
        DynamicField_setup["DynamicField::setup()"]
        custom_table["[table_name]_cstm"]
        custom_field_defs["custom field definitions"]
    end
    
    subgraph "Field Types"
        basic_types["varchar, int, text, date"]
        complex_types["relate, link, function"]
        custom_types["multienum, currency"]
    end
    
    dictionary --> VardefManager_load
    VardefManager_load --> object_vardefs
    object_vardefs --> field_defs
    field_defs --> field_name_map
    field_defs --> column_fields
    field_defs --> list_fields
    field_defs --> required_fields
    
    DynamicField_setup --> custom_table
    custom_table --> custom_field_defs
    custom_field_defs --> field_defs
    
    field_defs --> basic_types
    field_defs --> complex_types
    field_defs --> custom_types
```

**Sources:** [data/SugarBean.php:453-516](), [data/SugarBean.php:525-529](), [modules/DynamicFields/DynamicField.php]()

Field definitions control data validation, database schema generation, and UI rendering. The system automatically merges standard and custom field definitions during bean initialization.

## Relationship Management

SugarBean provides a comprehensive relationship management system that handles one-to-one, one-to-many, and many-to-many relationships between modules. Relationships are defined in vardefs and instantiated as `Link2` objects.

```mermaid
graph TB
    subgraph "Relationship Loading"
        load_relationship["load_relationship(rel_name)"]
        get_linked_fields["get_linked_fields()"]
        Link2_creation["new Link2(rel_name, bean)"]
    end
    
    subgraph "Relationship Operations"
        get_linked_beans["get_linked_beans()"]
        getBeans["Link2::getBeans()"]
        add_relationship["add_relationship()"]
        remove_relationship["remove_relationship()"]
    end
    
    subgraph "Relationship Metadata"
        relationships_table[("relationships table")]
        relationship_defs["relationship definitions"]
        join_tables["many-to-many join tables"]
    end
    
    subgraph "Query Building"
        getSubpanelQuery["Link2::getSubpanelQuery()"]
        get_union_related_list["get_union_related_list()"]
        build_sub_queries["build_sub_queries_for_union()"]
    end
    
    load_relationship --> get_linked_fields
    get_linked_fields --> Link2_creation
    Link2_creation --> get_linked_beans
    get_linked_beans --> getBeans
    
    getBeans --> getSubpanelQuery
    getSubpanelQuery --> get_union_related_list
    get_union_related_list --> build_sub_queries
    
    Link2_creation --> relationships_table
    relationships_table --> relationship_defs
    relationship_defs --> join_tables
```

**Sources:** [data/SugarBean.php:1972-2024](), [data/SugarBean.php:2044-2083](), [data/SugarBean.php:809-1021]()

The relationship system supports lazy loading, where relationships are only instantiated when accessed. Complex queries for subpanels and related lists use union queries to combine data from multiple related modules.

## Template Integration with Sugar_Smarty

The data layer integrates with the `Sugar_Smarty` templating system to provide dynamic content rendering. SugarBean objects are passed to templates where their properties can be accessed and displayed.

```mermaid
graph TB
    subgraph "Template System"
        Sugar_Smarty["Sugar_Smarty extends Smarty"]
        template_files["*.tpl files"]
        compiled_templates["compiled templates"]
    end
    
    subgraph "Data Assignment"
        assign["Sugar_Smarty::assign()"]
        bean_data["SugarBean properties"]
        global_vars["APP, MOD, APP_LIST_STRINGS"]
        field_metadata["field definitions for rendering"]
    end
    
    subgraph "Template Processing"
        fetch["Sugar_Smarty::fetch()"]
        loadTemplatePath["loadTemplatePath()"]
        theme_override["theme-specific templates"]
        custom_override["custom template overrides"]
    end
    
    subgraph "Output Generation"
        compiled_output["compiled PHP"]
        rendered_html["rendered HTML"]
        field_widgets["form fields and display widgets"]
    end
    
    Sugar_Smarty --> template_files
    template_files --> compiled_templates
    
    assign --> bean_data
    assign --> global_vars
    assign --> field_metadata
    
    fetch --> loadTemplatePath
    loadTemplatePath --> theme_override
    loadTemplatePath --> custom_override
    
    compiled_templates --> compiled_output
    compiled_output --> rendered_html
    rendered_html --> field_widgets
```

**Sources:** [include/Sugar_Smarty.php:152-179](), [include/Sugar_Smarty.php:341-353]()

The templating system automatically assigns global variables like `$APP`, `$MOD`, and `$APP_LIST_STRINGS` for use in templates. Template path resolution supports theme customization and custom overrides.

## Business Logic Hooks and Extensibility

SugarBean provides an extensible architecture through logic hooks that allow custom code to execute at specific points in the bean lifecycle. The system also supports custom fields and business object extensions.

| Hook Point | Method | Purpose |
|------------|--------|---------|
| `before_save` | Before database operation | Validation, data transformation |
| `after_save` | After database operation | Notifications, related data updates |
| `before_retrieve` | Before data loading | Access control, query modification |
| `after_retrieve` | After data loading | Computed fields, related data loading |
| `before_delete` | Before deletion | Validation, dependency checks |
| `after_delete` | After deletion | Cleanup, cascade operations |

**Sources:** [data/SugarBean.php:2350-2575](), [data/SugarBean.php:388-409]()

The bean lifecycle includes automatic audit trail generation for fields marked as audited, optimistic locking for concurrent access control, and support for custom field definitions through the `DynamicField` system.

## Performance and Caching Features

The SugarBean system includes several performance optimization features:

- **Definition Caching**: Field definitions are cached in static variables to avoid repeated loading
- **Relationship Lazy Loading**: Relationships are only loaded when accessed
- **Query Optimization**: Support for eager loading and optimized union queries for subpanels
- **Template Compilation**: Smarty templates are compiled to PHP for faster execution

**Sources:** [data/SugarBean.php:448-517](), [data/SugarBean.php:1911-1946](), [include/Sugar_Smarty.php:206-236]()

The system maintains loaded relationship tracking to optimize memory usage and provides configurable query optimization features for large datasets.