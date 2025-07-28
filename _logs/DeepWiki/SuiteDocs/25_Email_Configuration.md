# Email Configuration

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [content/8.x/admin/Licensing.ru.adoc](content/8.x/admin/Licensing.ru.adoc)
- [content/8.x/features/_index.ru.adoc](content/8.x/features/_index.ru.adoc)
- [content/admin/administration-panel/Emails/Email-Compose-From-List.ru.adoc](content/admin/administration-panel/Emails/Email-Compose-From-List.ru.adoc)
- [content/admin/administration-panel/Emails/Email.ru.adoc](content/admin/administration-panel/Emails/Email.ru.adoc)
- [content/admin/administration-panel/Emails/Microsoft-OAuth-Provider-HowTo.ru.adoc](content/admin/administration-panel/Emails/Microsoft-OAuth-Provider-HowTo.ru.adoc)
- [content/admin/releases/7.13.x/_index.en.adoc](content/admin/releases/7.13.x/_index.en.adoc)
- [content/admin/releases/7.14.x/_index.en.adoc](content/admin/releases/7.14.x/_index.en.adoc)
- [static/images/en/admin/release/Externaloauth1.png](static/images/en/admin/release/Externaloauth1.png)
- [static/images/en/admin/release/Externaloauth2.png](static/images/en/admin/release/Externaloauth2.png)
- [static/images/en/admin/release/Externaloauth3.png](static/images/en/admin/release/Externaloauth3.png)
- [static/images/en/admin/release/InboundEmail1.png](static/images/en/admin/release/InboundEmail1.png)
- [static/images/en/admin/release/InboundEmail2.png](static/images/en/admin/release/InboundEmail2.png)
- [static/images/en/admin/release/InboundEmail3.png](static/images/en/admin/release/InboundEmail3.png)
- [static/images/en/admin/release/InboundEmail4.png](static/images/en/admin/release/InboundEmail4.png)
- [static/images/en/admin/release/InboundEmail5.png](static/images/en/admin/release/InboundEmail5.png)
- [static/images/en/admin/release/InboundOAuthConfiguration.png](static/images/en/admin/release/InboundOAuthConfiguration.png)
- [static/images/en/admin/release/OAuthMicrosoftConnection.png](static/images/en/admin/release/OAuthMicrosoftConnection.png)
- [static/images/en/admin/release/Outbound1.png](static/images/en/admin/release/Outbound1.png)
- [static/images/en/admin/release/Outbound2.png](static/images/en/admin/release/Outbound2.png)
- [static/images/ru/8.x/features/loadmore/image0.png](static/images/ru/8.x/features/loadmore/image0.png)
- [static/images/ru/8.x/features/loadmore/image1.png](static/images/ru/8.x/features/loadmore/image1.png)
- [static/images/ru/8.x/features/loadmore/image2.png](static/images/ru/8.x/features/loadmore/image2.png)
- [static/images/ru/8.x/features/loadmore/image3.png](static/images/ru/8.x/features/loadmore/image3.png)
- [static/images/ru/8.x/features/notifications/image1.png](static/images/ru/8.x/features/notifications/image1.png)
- [static/images/ru/8.x/features/notifications/image2.png](static/images/ru/8.x/features/notifications/image2.png)
- [static/images/ru/8.x/features/notifications/image3.png](static/images/ru/8.x/features/notifications/image3.png)
- [static/images/ru/admin/Email/image4.png](static/images/ru/admin/Email/image4.png)
- [static/images/ru/admin/Email/image6.png](static/images/ru/admin/Email/image6.png)

</details>



This document covers the configuration and administration of SuiteCRM's email system, including traditional SMTP/IMAP settings and modern OAuth2 authentication integration. Email configuration encompasses inbound email processing, outbound email delivery, OAuth provider setup, and email account management across personal, group, and system-level contexts.

For information about using the email interface as an end user, see [User Email Guide](#4.1). For API-level email integration, see [API Documentation](#4).

## Email System Architecture

SuiteCRM's email system consists of multiple interconnected components that handle different aspects of email processing, authentication, and delivery.

```mermaid
graph TB
    subgraph "Email Configuration Components"
        SystemConfig["System Email Settings<br/>(config.php)"]
        InboundAccounts["Inbound Email Accounts<br/>(InboundEmail module)"]
        OutboundAccounts["Outbound Email Accounts<br/>(OutboundEmail module)"]
        OAuthProviders["OAuth Providers<br/>(ExternalOAuthConnection)"]
    end
    
    subgraph "Account Types"
        PersonalInbound["Personal Inbound"]
        GroupInbound["Group Inbound"]
        BounceInbound["Bounce Handling"]
        PersonalOutbound["Personal Outbound"]
        GroupOutbound["Group Outbound"]
        SystemOutbound["System Outbound"]
    end
    
    subgraph "Authentication Methods"
        BasicAuth["Basic Authentication<br/>(username/password)"]
        OAuthAuth["OAuth2 Authentication<br/>(token-based)"]
    end
    
    subgraph "Processing Components"
        EmailScheduler["Email Scheduler Jobs"]
        EmailQueue["Email Queue Management"]
        EmailImport["Email Import Processing"]
        CaseCreation["Automatic Case Creation"]
    end
    
    SystemConfig --> OutboundAccounts
    InboundAccounts --> PersonalInbound
    InboundAccounts --> GroupInbound
    InboundAccounts --> BounceInbound
    OutboundAccounts --> PersonalOutbound
    OutboundAccounts --> GroupOutbound
    OutboundAccounts --> SystemOutbound
    
    OAuthProviders --> OAuthAuth
    OAuthAuth --> InboundAccounts
    OAuthAuth --> OutboundAccounts
    BasicAuth --> InboundAccounts
    BasicAuth --> OutboundAccounts
    
    EmailScheduler --> EmailQueue
    EmailScheduler --> EmailImport
    GroupInbound --> CaseCreation
```

**Sources:** content/admin/releases/7.13.x/_index.en.adoc, content/admin/administration-panel/Emails/Email.ru.adoc

## Account Types and Configuration Entities

The email system supports different account types, each serving specific organizational needs and mapped to distinct code entities.

| Account Type | Module/Entity | Purpose | Configuration Location |
|-------------|---------------|---------|----------------------|
| Personal Inbound | `InboundEmail` | Individual user email access | User profile + Admin panel |
| Group Inbound | `InboundEmail` | Shared team email processing | Admin panel only |
| Bounce Handling | `InboundEmail` | Campaign bounce processing | Admin panel only |
| Personal Outbound | `OutboundEmail` | Individual sending accounts | User profile + Admin panel |
| Group Outbound | `OutboundEmail` | Shared sending accounts | Admin panel only |
| System Outbound | System settings | Default notification sender | Admin panel only |

### Inbound Email Account Configuration

Inbound email accounts handle email retrieval and processing through IMAP protocols with optional OAuth2 authentication.

```mermaid
graph LR
    subgraph "InboundEmail Configuration"
        InboundForm["Inbound Email Form"]
        AuthType["Authentication Type"]
        ServerConfig["Server Configuration"]
        ProcessingRules["Processing Rules"]
    end
    
    subgraph "Authentication Options"
        BasicCreds["Basic Authentication<br/>(username/password)"]
        OAuthConnection["OAuth Connection<br/>(ExternalOAuthConnection)"]
    end
    
    subgraph "Processing Features"
        AutoImport["Automatic Import"]
        CaseCreation["Case Creation"]
        AutoReply["Auto-Reply Templates"]
        SecurityGroups["Security Group Assignment"]
    end
    
    InboundForm --> AuthType
    AuthType --> BasicCreds
    AuthType --> OAuthConnection
    InboundForm --> ServerConfig
    InboundForm --> ProcessingRules
    ProcessingRules --> AutoImport
    ProcessingRules --> CaseCreation
    ProcessingRules --> AutoReply
    ProcessingRules --> SecurityGroups
    
    OAuthConnection -.-> OAuthProviders["OAuth Providers<br/>(Microsoft, Google, etc.)"]
```

**Sources:** content/admin/releases/7.13.x/_index.en.adoc, static/images/en/admin/release/InboundOAuthConfiguration.png

## OAuth Integration Architecture

OAuth2 integration provides secure, token-based authentication for modern email providers like Microsoft 365 and Google Workspace.

```mermaid
graph TB
    subgraph "OAuth Provider Setup"
        ProviderConfig["ExternalOAuthConnection<br/>Provider Configuration"]
        ClientCredentials["Client ID & Secret"]
        RedirectURI["Redirect URI<br/>(index.php?entryPoint=setExternalOAuthToken)"]
        TokenEndpoints["Authorization & Token Endpoints"]
    end
    
    subgraph "OAuth Flow Implementation"
        AuthRequest["Authorization Request"]
        AuthCode["Authorization Code"]
        TokenExchange["Token Exchange"]
        AccessToken["Access Token Storage"]
        RefreshToken["Token Refresh Logic"]
    end
    
    subgraph "Email Account Integration"
        InboundOAuth["Inbound Email + OAuth"]
        OutboundOAuth["Outbound Email + OAuth"]
        IMAP2Handler["Imap2Handler<br/>(xoauth support)"]
    end
    
    subgraph "Security Features"
        SecurityGroupAccess["Security Group Access Control"]
        PersonalVsGroup["Personal vs Group Connections"]
        TokenEncryption["Token Storage Security"]
    end
    
    ProviderConfig --> ClientCredentials
    ProviderConfig --> RedirectURI
    ProviderConfig --> TokenEndpoints
    
    ClientCredentials --> AuthRequest
    AuthRequest --> AuthCode
    AuthCode --> TokenExchange
    TokenExchange --> AccessToken
    AccessToken --> RefreshToken
    
    AccessToken --> InboundOAuth
    AccessToken --> OutboundOAuth
    InboundOAuth --> IMAP2Handler
    
    InboundOAuth --> SecurityGroupAccess
    OutboundOAuth --> SecurityGroupAccess
    SecurityGroupAccess --> PersonalVsGroup
    AccessToken --> TokenEncryption
```

**Sources:** content/admin/releases/7.13.x/_index.en.adoc, content/admin/administration-panel/Emails/Microsoft-OAuth-Provider-HowTo.ru.adoc, static/images/en/admin/release/OAuthMicrosoftConnection.png

## Configuration Management Components

Email configuration involves multiple administrative interfaces and storage mechanisms across the SuiteCRM codebase.

### System-Level Configuration

The core email settings are managed through the admin panel and stored in `config.php`:

- **Default outbound mail server**: System-wide SMTP configuration
- **Assignment notifications**: User notification preferences  
- **Security settings**: Email content filtering and tag restrictions
- **Campaign settings**: Mass email processing parameters

### Module-Specific Configuration

Each email account type has dedicated configuration interfaces:

```mermaid
graph LR
    subgraph "Admin Panel Interfaces"
        BasicEmailSettings["Basic Email Settings<br/>(config.php management)"]
        InboundEmailModule["Inbound Email Module<br/>(CRUD operations)"]
        OutboundEmailModule["Outbound Email Module<br/>(CRUD operations)"]
        OAuthProviderModule["OAuth Provider Module<br/>(External connections)"]
    end
    
    subgraph "User Profile Interfaces"
        UserEmailSettings["User Email Settings<br/>(Personal accounts)"]
        SignatureManager["Email Signatures<br/>(Personal/Group)"]
    end
    
    subgraph "Processing Components"
        EmailScheduler["Scheduler Jobs<br/>(Email processing)"]
        EmailQueue["Email Queue<br/>(Send queue management)"]
        ImportProcessor["Import Processor<br/>(Email ingestion)"]
    end
    
    BasicEmailSettings --> SystemConfig["System Configuration<br/>(config.php)"]
    InboundEmailModule --> InboundStorage["InboundEmail Records"]
    OutboundEmailModule --> OutboundStorage["OutboundEmail Records"]
    OAuthProviderModule --> OAuthStorage["ExternalOAuthConnection Records"]
    
    UserEmailSettings --> PersonalAccounts["Personal Account Records"]
    SignatureManager --> EmailSignatures["Email Signature Storage"]
    
    EmailScheduler --> ScheduledJobs["Scheduled Task Definitions"]
    EmailQueue --> QueueTables["Email Queue Tables"]
    ImportProcessor --> EmailRecords["Imported Email Records"]
```

**Sources:** content/admin/administration-panel/Emails/Email.ru.adoc, content/admin/releases/7.14.x/_index.en.adoc

## Authentication Method Implementation

SuiteCRM supports both traditional and modern authentication methods for email connectivity.

### Basic Authentication Flow

Traditional username/password authentication for email servers:

```mermaid
sequenceDiagram
    participant Admin as "Administrator"
    participant UI as "Admin Interface"
    participant Config as "Account Configuration"
    participant EmailServer as "Email Server"
    
    Admin->>UI: Configure Email Account
    Admin->>UI: Enter Server Details
    Admin->>UI: Provide Credentials
    UI->>Config: Store Account Settings
    UI->>EmailServer: Test Connection
    EmailServer-->>UI: Connection Result
    UI-->>Admin: Configuration Status
    
    Note over Config: Credentials stored securely
    Note over EmailServer: IMAP/SMTP with authentication
```

### OAuth2 Authentication Flow

Modern token-based authentication with external providers:

```mermaid
sequenceDiagram
    participant Admin as "Administrator"
    participant SuiteCRM as "SuiteCRM"
    participant Provider as "OAuth Provider"
    participant EmailServer as "Email Server"
    
    Admin->>SuiteCRM: Configure OAuth Provider
    Admin->>SuiteCRM: Create Email Account
    Admin->>SuiteCRM: Select OAuth Connection
    SuiteCRM->>Provider: Authorization Request
    Provider-->>SuiteCRM: Authorization Code
    SuiteCRM->>Provider: Exchange for Tokens
    Provider-->>SuiteCRM: Access & Refresh Tokens
    SuiteCRM->>EmailServer: Authenticate with Token
    EmailServer-->>SuiteCRM: Access Granted
    
    Note over SuiteCRM: Tokens stored securely
    Note over Provider: Supports Microsoft, Google, etc.
```

**Sources:** content/admin/releases/7.13.x/_index.en.adoc, content/admin/releases/7.14.x/_index.en.adoc

## Email Processing and Automation

The email system includes automated processing capabilities for different organizational workflows.

### Group Email Processing Features

Group email accounts provide additional automation capabilities:

- **Automatic email import**: Configurable import of all incoming messages
- **Case creation**: Automatic generation of support cases from emails
- **Auto-reply templates**: Customizable automatic responses
- **Assignment distribution**: Round-robin or rule-based assignment methods
- **Security group integration**: Access control for team-based email accounts

### Processing Configuration Options

| Feature | Configuration Entity | Purpose |
|---------|---------------------|---------|
| Auto Import | `InboundEmail.auto_import` | Import emails into SuiteCRM |
| Case Creation | `InboundEmail.create_case` | Generate cases from emails |
| Auto Reply | `InboundEmail.template_id` | Automated response templates |
| Assignment Method | `InboundEmail.assignment_method` | User assignment strategy |
| Security Groups | `SecurityGroup` relationships | Access control |

**Sources:** content/admin/administration-panel/Emails/Email.ru.adoc

## Email Queue and Scheduling

SuiteCRM implements a robust email queue system for reliable message delivery and processing.

```mermaid
graph TB
    subgraph "Email Queue System"
        EmailComposer["Email Composer<br/>(User Interface)"]
        QueueManager["Email Queue Manager<br/>(Queue processing)"]
        SchedulerJobs["Scheduler Jobs<br/>(Automated processing)"]
    end
    
    subgraph "Queue Operations"
        QueueStorage["Email Queue Storage<br/>(Database tables)"]
        BatchProcessing["Batch Processing<br/>(Configurable limits)"]
        RetryLogic["Retry Logic<br/>(Failed delivery handling)"]
    end
    
    subgraph "Processing Types"
        ImmediateSend["Immediate Send<br/>(Interactive emails)"]
        CampaignMailing["Campaign Mailing<br/>(Mass email processing)"]
        NotificationSend["Notification Send<br/>(System notifications)"]
    end
    
    EmailComposer --> QueueStorage
    QueueStorage --> QueueManager
    SchedulerJobs --> QueueManager
    QueueManager --> BatchProcessing
    BatchProcessing --> RetryLogic
    
    QueueManager --> ImmediateSend
    QueueManager --> CampaignMailing
    QueueManager --> NotificationSend
    
    ImmediateSend --> DeliveryStatus["Delivery Status Tracking"]
    CampaignMailing --> DeliveryStatus
    NotificationSend --> DeliveryStatus
```

### Queue Configuration Parameters

The email queue system supports several configuration parameters for optimal performance:

- **Batch size**: Number of emails processed simultaneously
- **Campaign tracker location**: URL configuration for tracking links
- **Message storage**: Optional retention of sent message copies
- **Retry intervals**: Failed delivery retry scheduling

**Sources:** content/admin/administration-panel/Emails/Email.ru.adoc, content/admin/releases/7.14.x/_index.en.adoc