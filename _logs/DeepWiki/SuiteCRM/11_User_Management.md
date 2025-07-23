# User Management

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [include/SugarEmailAddress/templates/optInStatusTick.tpl](include/SugarEmailAddress/templates/optInStatusTick.tpl)
- [include/generic/SugarWidgets/SugarWidgetSubPanelEmailLink.php](include/generic/SugarWidgets/SugarWidgetSubPanelEmailLink.php)
- [include/generic/SugarWidgets/SugarWidgetSubPanelTopComposeEmailButton.php](include/generic/SugarWidgets/SugarWidgetSubPanelTopComposeEmailButton.php)
- [modules/Emails/EmailUI.css](modules/Emails/EmailUI.css)
- [modules/Emails/EmailUI.php](modules/Emails/EmailUI.php)
- [modules/Emails/EmailUIAjax.php](modules/Emails/EmailUIAjax.php)
- [modules/Emails/PopupDocuments.html](modules/Emails/PopupDocuments.html)
- [modules/Emails/javascript/EmailUI.js](modules/Emails/javascript/EmailUI.js)
- [modules/Emails/templates/editAccountDialogue.tpl](modules/Emails/templates/editAccountDialogue.tpl)
- [modules/Emails/templates/emailSettingsAccounts.tpl](modules/Emails/templates/emailSettingsAccounts.tpl)
- [modules/Emails/templates/emailSettingsFolders.tpl](modules/Emails/templates/emailSettingsFolders.tpl)
- [modules/Emails/templates/emailSettingsGeneral.tpl](modules/Emails/templates/emailSettingsGeneral.tpl)
- [modules/Emails/templates/outboundDialog.tpl](modules/Emails/templates/outboundDialog.tpl)
- [modules/Users/User.php](modules/Users/User.php)
- [modules/Users/UserViewHelper.php](modules/Users/UserViewHelper.php)
- [modules/Users/controller.php](modules/Users/controller.php)
- [modules/Users/language/en_us.lang.php](modules/Users/language/en_us.lang.php)
- [modules/Users/tpls/EditViewFooter.tpl](modules/Users/tpls/EditViewFooter.tpl)
- [modules/Users/tpls/EditViewHeader.tpl](modules/Users/tpls/EditViewHeader.tpl)
- [modules/Users/tpls/wizard.tpl](modules/Users/tpls/wizard.tpl)
- [modules/Users/views/view.wizard.php](modules/Users/views/view.wizard.php)

</details>



## Purpose and Scope

The User Management system in SuiteCRM handles user entities, authentication, preferences, and email integration. This system provides comprehensive user account management including user profiles, preferences, authentication, email settings, and administrative functions. For email-specific functionality beyond user settings, see [Email System](#4.2). For broader authentication and security configurations, see [Administration Panel](#5.2).

## Core User Entity

The `User` class serves as the central entity for user management, extending the `Person` class and implementing the `EmailInterface`. Located in [modules/Users/User.php:50](), this class manages user data, preferences, and email functionality.

```mermaid
classDiagram
    class Person {
        <<abstract>>
        +first_name: string
        +last_name: string
        +email1: string
    }
    
    class EmailInterface {
        <<interface>>
        +populateComposeViewFields()
    }
    
    class User {
        +user_name: string
        +user_hash: string
        +is_admin: boolean
        +status: string
        +portal_only: boolean
        +is_group: boolean
        +factor_auth: boolean
        +authenticated: boolean
        +_userPreferenceFocus: UserPreference
        +save(check_notify): string
        +getSystemUser(): User
        +setPreference(name, value, category): void
        +getPreference(name, category): mixed
        +getSignatures(): array
        +hasPersonalEmail(): boolean
    }
    
    class UserPreference {
        +setPreference(name, value, category): void
        +getPreference(name, category): mixed
        +loadPreferences(category): boolean
        +savePreferencesToDB(): void
    }
    
    Person <|-- User
    EmailInterface <|.. User
    User --> UserPreference : "_userPreferenceFocus"
```

The User class contains essential user properties including authentication fields (`user_name`, `user_hash`, `authenticated`), access control fields (`is_admin`, `portal_only`, `is_group`), and two-factor authentication settings (`factor_auth`, `factor_auth_interface`).

**Sources:** [modules/Users/User.php:50-138]()

## User Preferences Management

The user preferences system integrates with the `UserPreference` class to manage user-specific settings across different categories. The User class provides an interface to preference management through its `_userPreferenceFocus` property.

```mermaid
flowchart TD
    A["User::setPreference()"] --> B["UserPreference::setPreference()"]
    B --> C["Validate Category"]
    C --> D["Store in Memory"]
    D --> E["User::savePreferencesToDB()"]
    E --> F["UserPreference::savePreferencesToDB()"]
    F --> G["Database Update"]
    
    H["User::getPreference()"] --> I["UserPreference::getPreference()"]
    I --> J["Check Memory Cache"]
    J --> K["Load from DB if needed"]
    K --> L["Return Value"]
    
    M["User::loadPreferences()"] --> N["UserPreference::loadPreferences()"]
    N --> O["Query Database"]
    O --> P["Populate Memory Cache"]
```

Key preference categories include:
- `global`: General user settings
- `Emails`: Email-specific preferences  
- `ETag`: UI cache management

The system supports various preference types including timezone, date/time formats, currency settings, email signatures, and UI customizations.

**Sources:** [modules/Users/User.php:379-394](), [modules/Users/User.php:518-531](), [modules/Users/UserViewHelper.php:730-970]()

## Authentication and Access Control

The User class implements several authentication and authorization mechanisms:

### User Types and Access Levels

```mermaid
graph TB
    User --> Administrator["is_admin = true<br/>Full System Access"]
    User --> Regular["Regular User<br/>Role-based Access"]
    User --> Group["is_group = true<br/>Group Assignment Only"]
    User --> Portal["portal_only = true<br/>Portal API Access"]
    
    Administrator --> TwoFactor["factor_auth = true<br/>Two-Factor Authentication"]
    Regular --> TwoFactor
```

### Authentication Flow

The authentication process involves multiple validation steps including two-factor authentication support:

- Password validation with configurable complexity requirements
- Two-factor authentication through `factor_auth` and `factor_auth_interface` fields
- SMTP server validation for email-based authentication features
- Session management through the `authenticated` property

**Sources:** [modules/Users/User.php:621-654](), [modules/Users/UserViewHelper.php:115-168]()

## Email Integration System

The User class integrates extensively with the email system through the `EmailUI` class and email preference management. This integration includes signature management, email account configuration, and compose functionality.

```mermaid
flowchart LR
    subgraph "User Email Components"
        A["User"] --> B["EmailUI"]
        A --> C["Email Signatures"]
        A --> D["Email Preferences"]
        A --> E["Email Accounts"]
    end
    
    subgraph "EmailUI Functions"
        B --> F["populateComposeViewFields()"]
        B --> G["displayEmailFrame()"]
        B --> H["generateComposePackageForQuickCreate()"]
    end
    
    subgraph "Signature Management"
        C --> I["getSignatures()"]
        C --> J["getDefaultSignature()"]
        C --> K["getSignatureButtons()"]
    end
    
    subgraph "Email Preferences"
        D --> L["mail_fromname"]
        D --> M["mail_fromaddress"]
        D --> N["signature_default"]
        D --> O["email_link_type"]
    end
```

### Email Signature System

The signature management system allows users to create and manage multiple email signatures:

- Signatures are stored in the `users_signatures` table
- Users can set a default signature via preferences
- HTML and plain text signature support
- Signature selection in compose dialogs

### Email UI Integration

The `EmailUI` class provides comprehensive email functionality including:

- Email composition interface generation
- Account management dialogs
- Folder management
- Template integration
- Quick compose functionality

**Sources:** [modules/Users/User.php:181-325](), [modules/Emails/EmailUI.php:84-369](), [modules/Emails/EmailUI.php:458-605]()

## User Management UI Components

The user interface system consists of several view components and helpers that manage user data presentation and interaction.

```mermaid
graph TD
    A["UsersController"] --> B["EditView"]
    A --> C["DetailView"] 
    A --> D["WizardView"]
    
    B --> E["UserViewHelper"]
    C --> E
    D --> F["ViewWizard"]
    
    E --> G["setupAdditionalFields()"]
    E --> H["setupUserTypeDropdown()"]
    E --> I["setupPasswordTab()"]
    E --> J["setupEmailSettings()"]
    E --> K["setupThemeTab()"]
    
    G --> L["EditViewHeader.tpl"]
    G --> M["EditViewFooter.tpl"]
    
    F --> N["wizard.tpl"]
```

### User View Helper

The `UserViewHelper` class manages complex UI rendering for user edit and detail views:

- User type dropdown configuration
- Password change interface
- Email settings integration
- Theme selection
- Advanced preference tabs

### User Controller Actions

The `UsersController` provides several key actions:

- `action_editview()` and `action_detailview()`: Standard CRUD operations with access control
- `action_resetPreferences()`: User preference reset functionality
- `action_delete()`: User deactivation and cleanup
- `action_wizard()` and `action_saveuserwizard()`: New user onboarding

**Sources:** [modules/Users/UserViewHelper.php:49-112](), [modules/Users/controller.php:49-244](), [modules/Users/views/view.wizard.php:55-131]()

## User Lifecycle Management

The user management system handles the complete user lifecycle from creation to deletion, including specialized user types and account management.

```mermaid
stateDiagram-v2
    [*] --> Creating
    Creating --> Active : save()
    Creating --> Wizard : New User Setup
    
    Active --> Editing : Edit Profile
    Active --> PasswordChange : Change Password
    Active --> ResetPrefs : Reset Preferences
    
    Wizard --> Active : Complete Setup
    Editing --> Active : Save Changes
    PasswordChange --> Active : Password Updated
    ResetPrefs --> Active : Preferences Reset
    
    Active --> Inactive : Deactivate
    Inactive --> Active : Reactivate
    Active --> Deleted : mark_deleted()
    
    Deleted --> [*]
```

### User Creation and Setup

New users go through a wizard process that configures:
- Personal information
- Locale preferences (timezone, date/time formats, currency)
- Email settings
- Theme selection
- Initial module access

### User Types and Permissions

The system supports multiple user types with different capabilities:

- **Regular Users**: Standard CRM access with role-based permissions
- **Administrators**: Full system access including user management
- **Group Users**: Used for assignment purposes, cannot log in
- **Portal Users**: Limited to portal API access

### Account Maintenance

The system provides tools for ongoing user account maintenance:
- Preference reset functionality
- Password management with complexity requirements
- Email account configuration
- Theme and UI customization

**Sources:** [modules/Users/controller.php:104-220](), [modules/Users/views/view.wizard.php:72-200](), [modules/Users/User.php:605-728]()