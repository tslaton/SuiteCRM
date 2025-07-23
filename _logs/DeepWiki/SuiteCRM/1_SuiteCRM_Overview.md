# SuiteCRM Overview

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [README.md](README.md)
- [composer.json](composer.json)
- [composer.lock](composer.lock)
- [files.md5](files.md5)
- [include/utils.php](include/utils.php)
- [modules/Import/tpls/last.tpl](modules/Import/tpls/last.tpl)
- [modules/Import/tpls/listview.tpl](modules/Import/tpls/listview.tpl)
- [suitecrm_version.php](suitecrm_version.php)
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
- [themes/SuiteP/include/DetailView/DetailView.tpl](themes/SuiteP/include/DetailView/DetailView.tpl)
- [themes/SuiteP/include/DetailView/footer.tpl](themes/SuiteP/include/DetailView/footer.tpl)
- [themes/SuiteP/include/DetailView/header.tpl](themes/SuiteP/include/DetailView/header.tpl)
- [themes/SuiteP/include/DetailView/tab_panel_content.tpl](themes/SuiteP/include/DetailView/tab_panel_content.tpl)
- [themes/SuiteP/include/DetailView/test.tpl](themes/SuiteP/include/DetailView/test.tpl)
- [themes/SuiteP/include/EditView/QuickCreate.tpl](themes/SuiteP/include/EditView/QuickCreate.tpl)
- [themes/SuiteP/js/style.js](themes/SuiteP/js/style.js)
- [themes/SuiteP/modules/Studio/TabGroups/EditViewTabs.tpl](themes/SuiteP/modules/Studio/TabGroups/EditViewTabs.tpl)
- [themes/SuiteP/tpls/_headerModuleList.tpl](themes/SuiteP/tpls/_headerModuleList.tpl)

</details>



This document provides a comprehensive overview of SuiteCRM's architecture, core systems, and key components. SuiteCRM is an open-source Customer Relationship Management (CRM) system that provides enterprise-grade functionality for managing customer relationships, sales processes, and business operations.

For detailed information about specific subsystems, see [Core Architecture](#2) for foundational patterns, [User Interface System](#3) for presentation layer details, [Core Business Modules](#4) for CRM functionality, and [Administration & Configuration](#5) for system management.

## System Purpose and Architecture

SuiteCRM is built on a PHP-based Model-View-Controller (MVC) architecture that extends the original SugarCRM Community Edition. The system provides a web-based interface for managing customer data, sales processes, email communications, reporting, and administrative functions.

```mermaid
graph TB
    subgraph "Presentation Layer"
        SuiteP["SuiteP Theme System<br/>themes/SuiteP/"]
        Templates["Smarty Templates<br/>*.tpl files"]
        CSS["Responsive CSS<br/>Day/Dawn/Dusk/Night variants"]
        JS["JavaScript Framework<br/>themes/SuiteP/js/"]
    end
    
    subgraph "Application Layer"
        MVC["MVC Framework<br/>include/MVC/"]
        Controllers["Module Controllers<br/>modules/*/controller.php"]
        Views["View Classes<br/>include/MVC/View/"]
        Actions["Action Handlers"]
    end
    
    subgraph "Business Logic"
        SugarBean["SugarBean ORM<br/>data/SugarBean.php"]
        Modules["Business Modules<br/>modules/*/"]
        Email["Email System<br/>modules/Emails/"]
        Campaigns["Campaign Management<br/>modules/Campaigns/"]
        Reports["AOR Reports<br/>modules/AOR_Reports/"]
    end
    
    subgraph "Data & Infrastructure"
        Database[("Database<br/>MySQL/MariaDB")]
        Files["File System<br/>upload/, cache/"]
        Config["Configuration<br/>config.php, sugar_config"]
        API["REST API V8<br/>Api/V8/"]
    end
    
    SuiteP --> MVC
    Templates --> Controllers
    CSS --> SuiteP
    JS --> Views
    
    Controllers --> SugarBean
    Views --> Modules
    Actions --> Email
    
    SugarBean --> Database
    Modules --> Files
    Email --> Config
    API --> SugarBean
```

Sources: [README.md:5-6](), [composer.json:2-4](), [suitecrm_version.php:6](), [include/utils.php:46]()

## Core Technology Stack

SuiteCRM leverages several key technologies and frameworks to deliver its functionality:

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Backend Framework** | PHP 7.4+ | Server-side application logic |
| **Templating Engine** | Smarty 4.x | Dynamic HTML generation |
| **Database Abstraction** | `SugarBean` ORM | Data persistence and relationships |
| **Frontend Framework** | Bootstrap 3.3.7 | Responsive UI components |
| **Theme System** | SuiteP with SCSS | Customizable visual presentation |
| **Search Engine** | Elasticsearch 7.x | Advanced search capabilities |
| **Email Processing** | PHPMailer 6.x | Email composition and delivery |
| **API Layer** | Slim Framework 3.x | RESTful web services |
| **Authentication** | OAuth2 Server | Secure API access |

Sources: [composer.json:35-77](), [themes/SuiteP/css/Day/style.css:2-6](), [include/utils.php:54-290]()

## System Architecture Overview

The following diagram illustrates how SuiteCRM's major subsystems interact and the data flow between them:

```mermaid
graph TB
    subgraph "User Interface Layer"
        Browser["Web Browser"]
        Mobile["Mobile Interface"]
        API_Client["API Clients"]
    end
    
    subgraph "Web Server Layer"
        Apache["Apache/Nginx"]
        PHP_FPM["PHP-FPM"]
        Static_Files["Static Assets<br/>themes/, images/"]
    end
    
    subgraph "Application Core"
        Entry_Point["index.php<br/>Entry Point"]
        SugarApplication["SugarApplication<br/>include/MVC/"]
        Router["Request Router<br/>include/MVC/Controller/"]
        Session_Manager["Session Management<br/>include/utils.php"]
    end
    
    subgraph "Business Logic Layer"
        Module_Controllers["Module Controllers<br/>modules/*/controller.php"]
        SugarBean_Factory["BeanFactory<br/>include/SugarObjects/"]
        Bean_Instances["SugarBean Instances<br/>Data Objects"]
        Workflow_Engine["Workflow Engine<br/>modules/AOW_WorkFlow/"]
    end
    
    subgraph "Data Persistence"
        DBManager["DBManagerFactory<br/>include/database/"]
        MySQL_DB[("MySQL Database<br/>Primary Storage")]
        File_System[("File System<br/>Uploads & Cache")]
        Search_Index[("Elasticsearch<br/>Search Index")]
    end
    
    subgraph "External Services"
        SMTP_Server["SMTP Server<br/>Email Delivery"]
        IMAP_Server["IMAP Server<br/>Email Retrieval"]
        Calendar_Sync["Google Calendar<br/>Integration"]
    end
    
    Browser --> Apache
    Mobile --> Apache
    API_Client --> Apache
    
    Apache --> PHP_FPM
    Apache --> Static_Files
    
    PHP_FPM --> Entry_Point
    Entry_Point --> SugarApplication
    SugarApplication --> Router
    Router --> Session_Manager
    
    Router --> Module_Controllers
    Module_Controllers --> SugarBean_Factory
    SugarBean_Factory --> Bean_Instances
    Module_Controllers --> Workflow_Engine
    
    Bean_Instances --> DBManager
    DBManager --> MySQL_DB
    Bean_Instances --> File_System
    Bean_Instances --> Search_Index
    
    Module_Controllers --> SMTP_Server
    Module_Controllers --> IMAP_Server
    Module_Controllers --> Calendar_Sync
```

Sources: [index.php](), [include/utils.php:54-290](), [include/MVC/](), [modules/]()

## Key System Components

### Configuration Management

SuiteCRM uses a centralized configuration system managed through the `sugar_config` array and utility functions:

- **Primary Config**: `config.php` contains the main `$sugar_config` array
- **Config Utilities**: `make_sugar_config()` and `get_sugar_config_defaults()` functions in [include/utils.php:54-594]()
- **Runtime Config**: Dynamic configuration loading and caching

### Database Abstraction Layer

The `SugarBean` class serves as the primary ORM and provides:

- **Data Modeling**: Base class for all business objects
- **Relationship Management**: Link definitions and relationship handling
- **Query Building**: Database-agnostic query construction
- **Caching**: Automatic result caching and invalidation

### Theme and Presentation System

SuiteCRM implements a sophisticated theming system through SuiteP:

- **Theme Variants**: Day, Dawn, Dusk, and Night color schemes
- **Responsive Design**: Bootstrap-based responsive layouts
- **SCSS Compilation**: [themes/SuiteP/css/suitep-base/]() contains source SCSS files
- **Template Engine**: Smarty templates for dynamic content generation

### Module Architecture

Business functionality is organized into discrete modules:

```mermaid
graph LR
    Module_Base["Module Base<br/>include/SugarObjects/"]
    
    subgraph "Core Modules"
        Users["Users<br/>modules/Users/"]
        Accounts["Accounts<br/>modules/Accounts/"]
        Contacts["Contacts<br/>modules/Contacts/"]
        Leads["Leads<br/>modules/Leads/"]
    end
    
    subgraph "Communication Modules"
        Emails["Emails<br/>modules/Emails/"]
        EmailTemplates["Email Templates<br/>modules/EmailTemplates/"]
        Campaigns["Campaigns<br/>modules/Campaigns/"]
        Calls["Calls<br/>modules/Calls/"]
    end
    
    subgraph "Business Process Modules"
        Opportunities["Opportunities<br/>modules/Opportunities/"]
        Cases["Cases<br/>modules/Cases/"]
        Projects["Projects<br/>modules/Project/"]
        Workflows["Workflows<br/>modules/AOW_WorkFlow/"]
    end
    
    subgraph "Analytics Modules"
        Reports["AOR Reports<br/>modules/AOR_Reports/"]
        Charts["AOR Charts<br/>modules/AOR_Charts/"]
        Dashboards["Home Dashboards<br/>modules/Home/"]
    end
    
    Module_Base --> Users
    Module_Base --> Accounts
    Module_Base --> Contacts
    Module_Base --> Leads
    
    Module_Base --> Emails
    Module_Base --> EmailTemplates
    Module_Base --> Campaigns
    Module_Base --> Calls
    
    Module_Base --> Opportunities
    Module_Base --> Cases
    Module_Base --> Projects
    Module_Base --> Workflows
    
    Module_Base --> Reports
    Module_Base --> Charts
    Module_Base --> Dashboards
```

Sources: [modules/](), [include/SugarObjects/]()

## Request Processing Flow

SuiteCRM processes web requests through a well-defined pipeline:

```mermaid
sequenceDiagram
    participant Browser
    participant "index.php" as Entry
    participant "SugarApplication" as App
    participant "Router" as Router
    participant "Controller" as Ctrl
    participant "SugarBean" as Bean
    participant "Database" as DB
    participant "View" as View
    
    Browser->>Entry: HTTP Request
    Entry->>App: Initialize Application
    App->>Router: Route Request
    Router->>Ctrl: Load Module Controller
    Ctrl->>Bean: Instantiate Business Object
    Bean->>DB: Execute Database Queries
    DB-->>Bean: Return Data
    Bean-->>Ctrl: Return Populated Object
    Ctrl->>View: Prepare View Data
    View-->>Browser: Render HTML Response
```

Sources: [index.php](), [include/MVC/](), [include/SugarObjects/]()

## File Organization Structure

The SuiteCRM codebase follows a logical directory structure:

| Directory | Purpose |
|-----------|---------|
| `modules/` | Business logic modules and MVC components |
| `include/` | Core framework classes and utilities |
| `themes/` | UI themes, templates, and presentation assets |
| `Api/` | REST API implementation (V8) |
| `cache/` | Runtime cache files and compiled templates |
| `custom/` | Customizations and extensions |
| `upload/` | User-uploaded files and attachments |
| `vendor/` | Third-party dependencies (Composer) |

Sources: [composer.json:99-117](), file structure analysis

This architectural overview provides the foundation for understanding SuiteCRM's design patterns and implementation approach. The system's modular architecture enables extensibility while maintaining clear separation of concerns between presentation, business logic, and data persistence layers.