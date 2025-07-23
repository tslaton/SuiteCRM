# Google Calendar Integration

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [include/GoogleSync/GoogleSync.php](include/GoogleSync/GoogleSync.php)
- [include/GoogleSync/GoogleSyncBase.php](include/GoogleSync/GoogleSyncBase.php)
- [include/GoogleSync/GoogleSyncExceptions.php](include/GoogleSync/GoogleSyncExceptions.php)
- [include/GoogleSync/GoogleSyncHelper.php](include/GoogleSync/GoogleSyncHelper.php)
- [modules/Configurator/language/en_us.lang.php](modules/Configurator/language/en_us.lang.php)
- [modules/Configurator/tpls/EditView.tpl](modules/Configurator/tpls/EditView.tpl)
- [modules/Configurator/views/view.edit.php](modules/Configurator/views/view.edit.php)
- [modules/Users/GoogleApiKeySaverEntryPoint.php](modules/Users/GoogleApiKeySaverEntryPoint.php)
- [modules/Users/entryPointSaveGoogleApiKey.php](modules/Users/entryPointSaveGoogleApiKey.php)
- [modules/Users/googleApiKeySaverEntryPointError.tpl](modules/Users/googleApiKeySaverEntryPointError.tpl)
- [tests/unit/phpunit/lib/Search/UI/SearchResultsControllerTest.php](tests/unit/phpunit/lib/Search/UI/SearchResultsControllerTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Log/CliLoggerHandlerTest.php](tests/unit/phpunit/lib/SuiteCRM/Log/CliLoggerHandlerTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/AbstractDocumentifierTest.php](tests/unit/phpunit/lib/SuiteCRM/Search/AbstractDocumentifierTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchClientBuilderTest.php](tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchClientBuilderTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchEngineTest.php](tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchEngineTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchIndexerTest.php](tests/unit/phpunit/lib/SuiteCRM/Search/ElasticSearch/ElasticSearchIndexerTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/SearchTestAbstract.php](tests/unit/phpunit/lib/SuiteCRM/Search/SearchTestAbstract.php)
- [tests/unit/phpunit/lib/SuiteCRM/Search/SearchWrapperTest.php](tests/unit/phpunit/lib/SuiteCRM/Search/SearchWrapperTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Utility/ArrayMapperTest.php](tests/unit/phpunit/lib/SuiteCRM/Utility/ArrayMapperTest.php)
- [tests/unit/phpunit/lib/SuiteCRM/Utility/BeanJsonSerializerTest.php](tests/unit/phpunit/lib/SuiteCRM/Utility/BeanJsonSerializerTest.php)
- [tests/unit/phpunit/modules/Administration/BaseHandlerTest.php](tests/unit/phpunit/modules/Administration/BaseHandlerTest.php)
- [tests/unit/phpunit/modules/Users/GoogleApiKeySaverEntryPointMock.php](tests/unit/phpunit/modules/Users/GoogleApiKeySaverEntryPointMock.php)
- [tests/unit/phpunit/modules/Users/GoogleApiKeySaverEntryPointTest.php](tests/unit/phpunit/modules/Users/GoogleApiKeySaverEntryPointTest.php)

</details>



## Purpose and Scope

This document covers SuiteCRM's Google Calendar Integration system, which provides bidirectional synchronization between SuiteCRM Meetings and Google Calendar events. The integration supports OAuth2 authentication, user-level sync preferences, and automated scheduling via cron jobs. For information about other external integrations, see [External Integrations](#7). For ElasticSearch integration details, see [ElasticSearch Integration](#7.2).

## Architecture Overview

The Google Calendar Integration consists of several interconnected components that handle authentication, synchronization logic, and configuration management.

### System Component Architecture

```mermaid
graph TB
    subgraph "Authentication Layer"
        OAUTH["GoogleApiKeySaverEntryPoint<br/>OAuth2 Flow Handler"]
        TOKEN["User Token Storage<br/>GoogleApiToken Preference"]
        CONFIG["Google API Configuration<br/>google_auth_json"]
    end
    
    subgraph "Sync Engine"
        SYNC["GoogleSync<br/>Main Sync Orchestrator"]
        BASE["GoogleSyncBase<br/>Core API Operations"]
        HELPER["GoogleSyncHelper<br/>Sync Logic Utilities"]
    end
    
    subgraph "Data Layer"
        MEETINGS["Meeting Beans<br/>SuiteCRM Meetings"]
        GCAL["Google Calendar API<br/>Calendar Events"]
        PREFS["User Preferences<br/>syncGCal Setting"]
    end
    
    subgraph "Configuration Layer"
        ADMIN["Administration Panel<br/>Google Auth JSON"]
        USER["User Settings<br/>Calendar Sync Toggle"]
        SCHEDULER["Cron Scheduler<br/>Automated Sync Jobs"]
    end
    
    OAUTH --> TOKEN
    CONFIG --> OAUTH
    ADMIN --> CONFIG
    
    SYNC --> BASE
    SYNC --> HELPER
    BASE --> GCAL
    BASE --> MEETINGS
    
    TOKEN --> BASE
    PREFS --> SYNC
    USER --> PREFS
    
    SCHEDULER --> SYNC
```

**Sources:** [include/GoogleSync/GoogleSync.php:57-313](), [include/GoogleSync/GoogleSyncBase.php:60-285](), [modules/Users/GoogleApiKeySaverEntryPoint.php:58-264]()

### OAuth2 Authentication Flow

```mermaid
sequenceDiagram
    participant User
    participant SuiteCRM as "SuiteCRM<br/>entryPointSaveGoogleApiKey"
    participant Google as "Google OAuth2<br/>API"
    participant KeySaver as "GoogleApiKeySaverEntryPoint"
    
    User->>SuiteCRM: Navigate to User Settings
    SuiteCRM->>KeySaver: Request new token (?getnew=1)
    KeySaver->>Google: createAuthUrl()
    Google-->>KeySaver: Authorization URL
    KeySaver->>User: Redirect to Google OAuth
    User->>Google: Grant permissions
    Google->>SuiteCRM: Callback with auth code
    SuiteCRM->>KeySaver: handleRequestCode()
    KeySaver->>Google: fetchAccessTokenWithAuthCode()
    Google-->>KeySaver: Access + Refresh Tokens
    KeySaver->>SuiteCRM: Save to user preferences
    KeySaver->>User: Redirect to User Settings
```

**Sources:** [modules/Users/GoogleApiKeySaverEntryPoint.php:106-198](), [modules/Users/entryPointSaveGoogleApiKey.php:55-57]()

## Core Classes and Responsibilities

### GoogleSync Class

The `GoogleSync` class serves as the main orchestrator for synchronization operations.

| Method | Purpose | Line Reference |
|--------|---------|---------------|
| `doSync()` | Performs sync for a single user | [include/GoogleSync/GoogleSync.php:144-169]() |
| `syncAllUsers()` | Orchestrates sync for all configured users | [include/GoogleSync/GoogleSync.php:281-312]() |
| `pushPullSkip()` | Determines sync action for event pairs | [include/GoogleSync/GoogleSync.php:204-228]() |
| `doAction()` | Executes the determined sync action | [include/GoogleSync/GoogleSync.php:100-135]() |

### GoogleSyncBase Class

The `GoogleSyncBase` class provides core functionality for Google API interactions and data management.

| Method | Purpose | Line Reference |
|--------|---------|---------------|
| `initUserService()` | Initializes Google services for a user | [include/GoogleSync/GoogleSyncBase.php:257-285]() |
| `pushEvent()` | Syncs SuiteCRM meeting to Google | [include/GoogleSync/GoogleSyncBase.php:556-586]() |
| `pullEvent()` | Syncs Google event to SuiteCRM | [include/GoogleSync/GoogleSyncBase.php:631-662]() |
| `getUserMeetings()` | Retrieves user's SuiteCRM meetings | [include/GoogleSync/GoogleSyncBase.php:295-318]() |
| `getUserGoogleEvents()` | Retrieves user's Google calendar events | [include/GoogleSync/GoogleSyncBase.php:397-427]() |

**Sources:** [include/GoogleSync/GoogleSync.php:48-313](), [include/GoogleSync/GoogleSyncBase.php:51-285]()

## Synchronization Logic

### Sync Decision Matrix

The system uses sophisticated logic to determine the appropriate action when synchronizing events between SuiteCRM and Google Calendar.

```mermaid
flowchart TD
    START["Event Comparison"] --> SINGLE{"Single Event?"}
    
    SINGLE -->|Yes| STYPE{"Event Type?"}
    STYPE -->|SuiteCRM Only<br/>not deleted| PUSH["Action: push"]
    STYPE -->|Google Only<br/>not cancelled<br/>not all-day| PULL["Action: pull"]
    STYPE -->|Other| SKIP1["Action: skip"]
    
    SINGLE -->|No| SYNCED{"Already Synced<br/>This Session?"}
    SYNCED -->|Yes| SKIP2["Action: skip"]
    
    SYNCED -->|No| DELETED{"Both Deleted?"}
    DELETED -->|Yes| SKIP3["Action: skip"]
    
    DELETED -->|No| MODIFIED{"Modified Since<br/>Last Sync?"}
    MODIFIED -->|No| SKIP4["Action: skip"]
    
    MODIFIED -->|Yes| NEWER{"Which is Newer?"}
    NEWER -->|Google Newer<br/>Cancelled| PULLDELETE["Action: pull_delete"]
    NEWER -->|Google Newer<br/>Active| PULL2["Action: pull"]
    NEWER -->|SuiteCRM Newer<br/>Deleted| PUSHDELETE["Action: push_delete"]
    NEWER -->|SuiteCRM Newer<br/>Active| PUSH2["Action: push"]
```

**Sources:** [include/GoogleSync/GoogleSyncHelper.php:69-170](), [include/GoogleSync/GoogleSync.php:204-228]()

### Data Flow Architecture

```mermaid
graph LR
    subgraph "SuiteCRM Data"
        MEET["Meeting Records<br/>meetings table"]
        GSYNC["gsync_id field<br/>gsync_lastsync field"]
        USERPREFS["User Preferences<br/>GoogleApiToken<br/>syncGCal"]
    end
    
    subgraph "Sync Engine"
        SYNCPROC["Sync Process<br/>GoogleSync::doSync()"]
        COMPARE["Event Comparison<br/>GoogleSyncHelper"]
        ACTIONS["Sync Actions<br/>push/pull/skip/delete"]
    end
    
    subgraph "Google Calendar"
        GEVENTS["Calendar Events<br/>Google Calendar API"]
        GCAL["SuiteCRM Calendar<br/>Created if missing"]
        EXTPROPS["Extended Properties<br/>suitecrm_id<br/>suitecrm_type"]
    end
    
    MEET --> SYNCPROC
    USERPREFS --> SYNCPROC
    SYNCPROC --> COMPARE
    COMPARE --> ACTIONS
    ACTIONS --> GSYNC
    ACTIONS --> GEVENTS
    GEVENTS --> EXTPROPS
    EXTPROPS --> GCAL
```

**Sources:** [include/GoogleSync/GoogleSyncBase.php:295-427](), [include/GoogleSync/GoogleSyncHelper.php:92-170]()

## Configuration Management

### System-Level Configuration

The Google Calendar integration requires system-wide configuration of Google API credentials stored in the `google_auth_json` configuration variable.

```mermaid
graph TD
    subgraph "Admin Configuration"
        ADMIN["Admin Panel<br/>modules/Configurator"]
        JSON["google_auth_json<br/>Base64 Encoded JSON"]
        VALIDATE["Config Validation<br/>checkGoogleSyncJSON()"]
    end
    
    subgraph "JSON Structure"
        WEB["web object"]
        CLIENTID["client_id"]
        SECRET["client_secret"]
        REDIRECT["redirect_uris"]
    end
    
    subgraph "User Configuration"
        USEREDIT["User EditView"]
        SYNCPREF["syncGCal Preference"]
        TOKENS["OAuth Tokens<br/>GoogleApiToken<br/>GoogleApiRefreshToken"]
    end
    
    ADMIN --> JSON
    JSON --> VALIDATE
    JSON --> WEB
    WEB --> CLIENTID
    WEB --> SECRET
    WEB --> REDIRECT
    
    USEREDIT --> SYNCPREF
    USEREDIT --> TOKENS
```

**Sources:** [modules/Configurator/views/view.edit.php:176-209](), [modules/Users/GoogleApiKeySaverEntryPoint.php:106-142]()

### User Preferences Schema

| Preference Key | Category | Purpose | Format |
|---------------|----------|---------|---------|
| `GoogleApiToken` | `GoogleSync` | OAuth access token | Base64 encoded JSON |
| `GoogleApiRefreshToken` | `GoogleSync` | OAuth refresh token | Base64 encoded string |
| `syncGCal` | `GoogleSync` | Enable/disable sync | Boolean |

**Sources:** [include/GoogleSync/GoogleSyncBase.php:186-195](), [modules/Users/GoogleApiKeySaverEntryPoint.php:190-194]()

## Error Handling and Exceptions

### Exception Hierarchy

The system defines a comprehensive set of custom exceptions for different failure scenarios.

```mermaid
classDiagram
    class GoogleSyncException {
        +int MEETING_NOT_FOUND = 101
        +int EVENT_ID_IS_EMPTY = 102
        +int INVALID_CLIENT_ID = 104
        +int UNABLE_TO_RETRIEVE_USER = 105
        +int NO_REFRESH_TOKEN = 110
        +int JSON_CORRUPT = 121
        +int JSON_KEY_MISSING = 122
        +int GEVENT_INSERT_OR_UPDATE_FAILURE = 124
    }
    
    Exception <|-- GoogleSyncException
```

### Common Error Scenarios

| Error Code | Constant | Typical Cause | Recovery Action |
|------------|----------|---------------|-----------------|
| 101 | `MEETING_NOT_FOUND` | Database query failure | Retry or skip record |
| 110 | `NO_REFRESH_TOKEN` | Missing refresh token | Re-authenticate user |
| 121 | `JSON_CORRUPT` | Invalid Google auth JSON | Reconfigure admin settings |
| 124 | `GEVENT_INSERT_OR_UPDATE_FAILURE` | Google API error | Retry with backoff |

**Sources:** [include/GoogleSync/GoogleSyncExceptions.php:51-84]()

## Scheduling and Automation

### Cron Job Integration

The system supports automated synchronization through SuiteCRM's scheduler system, allowing administrators to configure regular sync intervals.

```mermaid
graph LR
    subgraph "Scheduler System"
        CRON["Cron Job Trigger"]
        SCHEDULER["SuiteCRM Scheduler"]
        GSYNCJOB["Google Sync Job"]
    end
    
    subgraph "Sync Execution"
        USERS["setSyncUsers()<br/>Find eligible users"]
        LOOP["User Sync Loop<br/>syncAllUsers()"]
        INDIVIDUAL["Individual Sync<br/>doSync(userId)"]
    end
    
    subgraph "User Selection Criteria"
        TOKEN["Has GoogleApiToken"]
        ENABLED["syncGCal = true"]
        VALID["Token not expired"]
    end
    
    CRON --> SCHEDULER
    SCHEDULER --> GSYNCJOB
    GSYNCJOB --> USERS
    USERS --> TOKEN
    USERS --> ENABLED
    USERS --> VALID
    USERS --> LOOP
    LOOP --> INDIVIDUAL
```

**Sources:** [include/GoogleSync/GoogleSync.php:239-312](), [include/GoogleSync/GoogleSync.php:281-312]()

## Integration Points

### Meeting Module Integration

The Google Calendar integration extends the SuiteCRM Meeting module with additional fields for tracking synchronization state.

| Field | Purpose | Type |
|-------|---------|------|
| `gsync_id` | Google Calendar event ID | VARCHAR |
| `gsync_lastsync` | Last synchronization timestamp | TIMESTAMP |

### Google Calendar API Integration

The system interacts with the Google Calendar API through the official Google Client Library, specifically using:

- `\Google\Client` for authentication
- `\Google\Service\Calendar` for calendar operations  
- `\Google\Service\Calendar\Event` for event manipulation

**Sources:** [include/GoogleSync/GoogleSyncBase.php:65-78](), [include/GoogleSync/GoogleSyncBase.php:221-242]()