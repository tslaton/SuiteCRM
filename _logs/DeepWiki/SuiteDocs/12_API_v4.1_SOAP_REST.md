# API v4.1 (SOAP & REST)

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.htmltest.yml](.htmltest.yml)
- [content/8.x/admin/releases/8.0/_index.en.adoc](content/8.x/admin/releases/8.0/_index.en.adoc)
- [content/admin/Advanced Configuration Options.adoc](content/admin/Advanced Configuration Options.adoc)
- [content/admin/administration-panel/System.adoc](content/admin/administration-panel/System.adoc)
- [content/admin/releases/7.10.x/_index.en.adoc](content/admin/releases/7.10.x/_index.en.adoc)
- [content/admin/releases/7.11.x/_index.en.adoc](content/admin/releases/7.11.x/_index.en.adoc)
- [content/admin/releases/7.12.x/_index.en.adoc](content/admin/releases/7.12.x/_index.en.adoc)
- [content/admin/releases/7.8.x/_index.en.adoc](content/admin/releases/7.8.x/_index.en.adoc)
- [content/blog/_index.es.md](content/blog/_index.es.md)
- [content/developer/api/API-4_1.adoc](content/developer/api/API-4_1.adoc)
- [content/developer/api/Developer-setup-guide/Configure Authentication.adoc](content/developer/api/Developer-setup-guide/Configure Authentication.adoc)
- [content/developer/api/Developer-setup-guide/Customization.adoc](content/developer/api/Developer-setup-guide/Customization.adoc)
- [content/developer/api/Developer-setup-guide/Getting Available Resources.adoc](content/developer/api/Developer-setup-guide/Getting Available Resources.adoc)
- [content/developer/api/Developer-setup-guide/Introduction.adoc](content/developer/api/Developer-setup-guide/Introduction.adoc)
- [content/developer/api/Developer-setup-guide/JSON-API.adoc](content/developer/api/Developer-setup-guide/JSON-API.adoc)
- [content/developer/api/Developer-setup-guide/Managing Tokens.adoc](content/developer/api/Developer-setup-guide/Managing Tokens.adoc)
- [content/developer/api/Developer-setup-guide/Requirements.adoc](content/developer/api/Developer-setup-guide/Requirements.adoc)
- [content/developer/api/Developer-setup-guide/SuiteCRM_V8_API_Set_Up_For_Postman.adoc](content/developer/api/Developer-setup-guide/SuiteCRM_V8_API_Set_Up_For_Postman.adoc)
- [content/developer/api/Developer-setup-guide/_index.en.adoc](content/developer/api/Developer-setup-guide/_index.en.adoc)
- [layouts/shortcodes/contribs.html](layouts/shortcodes/contribs.html)
- [layouts/shortcodes/dumpJSON.html](layouts/shortcodes/dumpJSON.html)
- [layouts/shortcodes/ghcontributors.html](layouts/shortcodes/ghcontributors.html)
- [static/images/en/8.x/user/features/subpanels/Filter-Expanded.png](static/images/en/8.x/user/features/subpanels/Filter-Expanded.png)
- [static/images/en/8.x/user/features/subpanels/Filter-Full-Panel.png](static/images/en/8.x/user/features/subpanels/Filter-Full-Panel.png)
- [static/images/en/8.x/user/features/subpanels/Filter-Searched.png](static/images/en/8.x/user/features/subpanels/Filter-Searched.png)

</details>



This document covers the SuiteCRM API version 4.1, which provides both SOAP and REST interfaces for external applications to interact with SuiteCRM data and functionality. API v4.1 was the primary API interface for SuiteCRM versions up to 7.9.x and remains available in later versions for backward compatibility.

For information about the modern JSON API available in SuiteCRM 8.x and later, see [JSON API (v8)](#4.2). For general API setup and authentication concepts, see [API Documentation](#4).

## API v4.1 Architecture Overview

API v4.1 provides dual protocol support through separate SOAP and REST endpoints, both sharing common authentication mechanisms and underlying service implementations.

```mermaid
graph TB
    subgraph "SuiteCRM Instance"
        subgraph "API v4.1 Layer"
            SOAP["SOAP Endpoint<br/>service/v4_1/soap.php"]
            REST["REST Endpoint<br/>service/v4_1/rest.php"]
            WSDL["WSDL Definition<br/>service/v4_1/soap.php?wsdl"]
        end
        
        subgraph "Core Implementation"
            IMPL["SugarWebServiceImplv4_1<br/>service/v4_1/SugarWebServiceImplv4_1.php"]
            REGISTRY["Service Registry<br/>service/v4_1/registry.php"]
            HELPER["Helper Classes<br/>service/core/"]
        end
        
        subgraph "Data Layer"
            BEANS["SugarBean Objects"]
            DB["Database"]
            ACL["Access Control"]
        end
        
        subgraph "Authentication"
            AUTH["Session Management"]
            USERAUTH["User Authentication"]
        end
    end
    
    subgraph "External Clients"
        SOAPCLIENT["SOAP Client Applications"]
        RESTCLIENT["REST Client Applications"]
    end
    
    SOAPCLIENT --> SOAP
    SOAPCLIENT --> WSDL
    RESTCLIENT --> REST
    
    SOAP --> IMPL
    REST --> IMPL
    WSDL --> REGISTRY
    
    IMPL --> BEANS
    IMPL --> AUTH
    BEANS --> DB
    BEANS --> ACL
    
    AUTH --> USERAUTH
```

**Sources:** [content/developer/api/API-4_1.adoc:1-50](), [content/developer/api/API-4_1.adoc:230-290]()

## SOAP API Implementation

The SOAP API provides a standards-compliant web service interface with full WSDL definition support.

### SOAP Endpoint Configuration

| Component | Location | Description |
|-----------|----------|-------------|
| **SOAP Service** | `service/v4_1/soap.php` | Main SOAP endpoint processor |
| **WSDL Definition** | `service/v4_1/soap.php?wsdl` | Web Service Description Language file |
| **Implementation Class** | `SugarWebServiceImplv4_1` | Core SOAP method implementations |

### SOAP Authentication Flow

```mermaid
sequenceDiagram
    participant Client as "SOAP Client"
    participant Endpoint as "soap.php"
    participant Auth as "Authentication System" 
    participant Session as "Session Manager"
    
    Client->>Endpoint: login(userAuth, appName, nameValueList)
    Endpoint->>Auth: Validate credentials
    Auth->>Session: Create session
    Session->>Endpoint: Return session_id
    Endpoint->>Client: Login response with session_id
    
    Client->>Endpoint: get_entry_list(session_id, module, query, ...)
    Endpoint->>Session: Validate session_id
    Session->>Endpoint: Session valid
    Endpoint->>Client: Entry list response
```

**Sources:** [content/developer/api/API-4_1.adoc:35-88]()

### SOAP Method Structure

The SOAP API methods follow a consistent pattern using the `SugarWebServiceImplv4_1` class:

```mermaid
graph LR
    subgraph "SOAP Method Categories"
        AUTH["Authentication Methods<br/>login(), logout()"]
        CRUD["CRUD Operations<br/>get_entry_list(), get_entry()<br/>set_entry(), set_entries()"]
        REL["Relationship Methods<br/>get_relationships()<br/>set_relationship()"]
        META["Metadata Methods<br/>get_module_fields()<br/>get_available_modules()"]
        UTIL["Utility Methods<br/>get_server_info()<br/>get_user_id()"]
    end
    
    AUTH --> IMPL["SugarWebServiceImplv4_1"]
    CRUD --> IMPL
    REL --> IMPL
    META --> IMPL
    UTIL --> IMPL
```

**Sources:** [content/developer/api/API-4_1.adoc:40-88]()

## REST API Implementation

The REST API provides a simplified HTTP-based interface, though it deviates from true REST principles by using POST for all operations.

### REST Endpoint Structure

| Parameter | Description | Example |
|-----------|-------------|---------|
| **method** | API method name | `login`, `get_entry_list` |
| **input_type** | Request data format | `JSON`, `Serialize` |
| **response_type** | Response data format | `JSON`, `Serialize` |
| **rest_data** | Method arguments | Encoded parameter array |

### REST Request Flow

```mermaid
sequenceDiagram
    participant Client as "REST Client"
    participant Endpoint as "rest.php"
    participant Processor as "Request Processor"
    participant Methods as "API Methods"
    
    Client->>Endpoint: POST with method='login'<br/>rest_data=JSON(userAuth)
    Endpoint->>Processor: Parse request parameters
    Processor->>Methods: Call login method
    Methods->>Processor: Return session data
    Processor->>Endpoint: Encode response
    Endpoint->>Client: JSON response with session_id
    
    Client->>Endpoint: POST with method='get_entry_list'<br/>rest_data=JSON(session, module, query)
    Endpoint->>Processor: Parse request parameters
    Processor->>Methods: Call get_entry_list method
    Methods->>Processor: Return entry data
    Processor->>Endpoint: Encode response
    Endpoint->>Client: JSON response with entries
```

**Sources:** [content/developer/api/API-4_1.adoc:90-202]()

### REST Parameter Ordering

The REST API requires strict parameter ordering in the `rest_data` array:

```mermaid
graph TD
    subgraph "REST Parameter Structure"
        ORDER["Parameter Order Matters"]
        LOGIN["login() Parameters:<br/>1. user_auth<br/>2. application_name<br/>3. name_value_list"]
        GETLIST["get_entry_list() Parameters:<br/>1. session<br/>2. module_name<br/>3. query<br/>4. order_by<br/>5. offset<br/>6. select_fields<br/>7. link_name_to_fields_array<br/>8. max_results<br/>9. deleted"]
    end
    
    ORDER --> LOGIN
    ORDER --> GETLIST
```

**Sources:** [content/developer/api/API-4_1.adoc:120-130](), [content/developer/api/API-4_1.adoc:210-230]()

## Authentication and Session Management

Both SOAP and REST APIs share the same authentication mechanism using username/password credentials and session tokens.

### Authentication Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| **user_name** | string | SuiteCRM username |
| **password** | string | MD5 hash of user password |
| **application_name** | string | Client application identifier |
| **name_value_list** | array | Additional authentication parameters |

### Session Lifecycle

```mermaid
stateDiagram-v2
    [*] --> Unauthenticated
    Unauthenticated --> Authenticated: login(credentials)
    Authenticated --> SessionActive: session_id returned
    SessionActive --> SessionActive: API calls with session_id
    SessionActive --> SessionExpired: timeout/inactivity
    SessionActive --> LoggedOut: logout() called
    SessionExpired --> Unauthenticated
    LoggedOut --> Unauthenticated
```

**Sources:** [content/developer/api/API-4_1.adoc:15-25](), [content/developer/api/API-4_1.adoc:45-55]()

## Version Compatibility and Lifecycle

API v4.1 maintains backward compatibility across multiple SuiteCRM versions with specific support windows:

### Version Support Matrix

| SuiteCRM Version | API v4.1 Status | Notes |
|------------------|-----------------|-------|
| **7.8.x and earlier** | Primary API | Full feature support |
| **7.9.x** | Primary API | Last version with v4.1 as main API |
| **7.10.x - 7.14.x** | Legacy Support | Maintained for compatibility |
| **8.x** | Legacy Support | JSON API v8 preferred |

### API Evolution Path

```mermaid
timeline
    title SuiteCRM API Evolution
    
    section Legacy Era
        7.8.x : API v4.1 Primary
             : SOAP/REST only
    
    section Transition Period  
        7.9.x : API v4.1 Primary
             : Last major v4.1 version
        7.10.x : API v4.1 Legacy
              : JSON API v8 introduced
    
    section Modern Era
        8.x : JSON API Primary
           : OAuth2 authentication
           : API v4.1 compatibility maintained
```

**Sources:** [content/developer/api/API-4_1.adoc:6-10](), [content/admin/releases/7.10.x/_index.en.adoc:1-50](), [content/admin/releases/7.11.x/_index.en.adoc:1-50]()

## Custom API Extensions

API v4.1 supports custom method extensions through the service framework:

### Custom Method Implementation

| Component | Location | Purpose |
|-----------|----------|---------|
| **Custom Service Class** | `custom/service/v4_1_custom/` | Extended service implementation |
| **Registry File** | `custom/service/v4_1_custom/registry.php` | Method registration |
| **Implementation Class** | `SugarWebServiceImplv4_1_custom` | Custom method definitions |

### Extension Architecture

```mermaid
graph TB
    subgraph "Core API Framework"
        COREIMPL["SugarWebServiceImplv4_1<br/>service/v4_1/"]
        COREREG["Core Registry<br/>service/v4_1/registry.php"]
    end
    
    subgraph "Custom Extensions"
        CUSTOMIMPL["SugarWebServiceImplv4_1_custom<br/>custom/service/v4_1_custom/"]
        CUSTOMREG["Custom Registry<br/>custom/service/v4_1_custom/registry.php"]
        CUSTOMMETHODS["Custom Methods<br/>write_log_message()<br/>custom_operation()"]
    end
    
    subgraph "API Endpoints"
        SOAPEP["SOAP Endpoint"]
        RESTEP["REST Endpoint"]
    end
    
    CUSTOMIMPL --> COREIMPL
    CUSTOMREG --> COREREG
    CUSTOMMETHODS --> CUSTOMIMPL
    
    SOAPEP --> CUSTOMIMPL
    RESTEP --> CUSTOMIMPL
```

**Sources:** [content/developer/api/API-4_1.adoc:230-290]()

## Security Considerations

API v4.1 implementations should address several security aspects documented in release notes:

### Common Security Issues

| Issue Type | Description | Mitigation |
|------------|-------------|------------|
| **SQL Injection** | Direct query parameter usage | Input validation and sanitization |
| **Access Control** | Insufficient permission checks | Session validation and ACL enforcement |
| **Authentication Bypass** | Session handling vulnerabilities | Proper session management |

### Security Updates Timeline

The release notes show ongoing security maintenance across versions, with CVE identifiers tracking specific vulnerabilities in API v4.1 implementations.

**Sources:** [content/admin/releases/7.10.x/_index.en.adoc:20-35](), [content/admin/releases/7.11.x/_index.en.adoc:20-35](), [content/admin/releases/7.12.x/_index.en.adoc:20-35]()