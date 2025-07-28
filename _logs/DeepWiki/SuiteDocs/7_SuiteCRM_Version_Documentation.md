# SuiteCRM Version Documentation

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.htmltest.yml](.htmltest.yml)
- [content/8.x/admin/releases/8.0/_index.en.adoc](content/8.x/admin/releases/8.0/_index.en.adoc)
- [content/8.x/admin/releases/8.1/_index.en.adoc](content/8.x/admin/releases/8.1/_index.en.adoc)
- [content/8.x/admin/releases/8.2/_index.en.adoc](content/8.x/admin/releases/8.2/_index.en.adoc)
- [content/8.x/admin/releases/8.3/_index.en.adoc](content/8.x/admin/releases/8.3/_index.en.adoc)
- [content/8.x/admin/releases/8.4/_index.en.adoc](content/8.x/admin/releases/8.4/_index.en.adoc)
- [content/8.x/admin/releases/8.5/_index.en.adoc](content/8.x/admin/releases/8.5/_index.en.adoc)
- [content/8.x/admin/releases/8.6/_index.en.adoc](content/8.x/admin/releases/8.6/_index.en.adoc)
- [content/8.x/admin/releases/8.7/_index.en.adoc](content/8.x/admin/releases/8.7/_index.en.adoc)
- [content/8.x/admin/upgrading/general-info.adoc](content/8.x/admin/upgrading/general-info.adoc)
- [content/admin/Advanced Configuration Options.adoc](content/admin/Advanced Configuration Options.adoc)
- [content/admin/releases/7.10.x/_index.en.adoc](content/admin/releases/7.10.x/_index.en.adoc)
- [content/admin/releases/7.11.x/_index.en.adoc](content/admin/releases/7.11.x/_index.en.adoc)
- [content/admin/releases/7.12.x/_index.en.adoc](content/admin/releases/7.12.x/_index.en.adoc)
- [content/admin/releases/7.13.x/_index.en.adoc](content/admin/releases/7.13.x/_index.en.adoc)
- [content/admin/releases/7.14.x/_index.en.adoc](content/admin/releases/7.14.x/_index.en.adoc)
- [content/admin/releases/7.8.x/_index.en.adoc](content/admin/releases/7.8.x/_index.en.adoc)
- [content/blog/_index.es.md](content/blog/_index.es.md)
- [content/developer/api/API-4_1.adoc](content/developer/api/API-4_1.adoc)
- [layouts/shortcodes/contribs.html](layouts/shortcodes/contribs.html)
- [layouts/shortcodes/dumpJSON.html](layouts/shortcodes/dumpJSON.html)
- [layouts/shortcodes/ghcontributors.html](layouts/shortcodes/ghcontributors.html)
- [static/images/en/8.x/admin/release/portal-user-enable-buttons.gif](static/images/en/8.x/admin/release/portal-user-enable-buttons.gif)
- [static/images/en/8.x/admin/release/preinstall-page-re-styled.png](static/images/en/8.x/admin/release/preinstall-page-re-styled.png)
- [static/images/en/8.x/admin/release/release-notes-field-actions-example.gif](static/images/en/8.x/admin/release/release-notes-field-actions-example.gif)
- [static/images/en/8.x/user/features/subpanels/Filter-Expanded.png](static/images/en/8.x/user/features/subpanels/Filter-Expanded.png)
- [static/images/en/8.x/user/features/subpanels/Filter-Full-Panel.png](static/images/en/8.x/user/features/subpanels/Filter-Full-Panel.png)
- [static/images/en/8.x/user/features/subpanels/Filter-Searched.png](static/images/en/8.x/user/features/subpanels/Filter-Searched.png)
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

</details>



This document provides a comprehensive overview of the SuiteCRM versions covered in the SuiteDocs documentation system. It serves as a reference for understanding the version landscape, release cycles, support status, and API evolution across both SuiteCRM 7.x and 8.x series.

For specific installation procedures, see [Installation Process](#5.1). For upgrade instructions between versions, see [Upgrade Procedures](#5.2). For API-specific documentation, see [API Documentation](#4).

## SuiteCRM Version Ecosystem

The SuiteCRM ecosystem spans two major architectural generations, each with distinct characteristics, support lifecycles, and development approaches.

### Version Architecture Overview

```mermaid
graph TB
    subgraph "Legacy 7.x Architecture"
        V78["7.8.x<br/>End of Life<br/>July 2019"]
        V710["7.10.x<br/>Extended Support Ended<br/>January 2022"]
        V711["7.11.x<br/>Maintenance Mode"]
        V712["7.12.x<br/>Active Support<br/>Latest: 7.12.14"]
        V713["7.13.x<br/>OAuth Integration<br/>Latest: 7.13.4"]
        V714["7.14.x<br/>PHP 8.2 Support<br/>Latest: 7.14.6"]
    end
    
    subgraph "Modern 8.x Architecture"
        V80["8.0.x<br/>Angular Rewrite<br/>Initial Release"]
        V82["8.2.x<br/>Migration Entry Point<br/>Latest: 8.2.4"]
        V84["8.4.x<br/>PHP 8.1+ Required<br/>Latest: 8.4.2"]
        V86["8.6.x<br/>Login Language Config<br/>Latest: 8.6.2"]
        V87["8.7.x<br/>Angular 18, 2FA<br/>Latest: 8.7.1"]
    end
    
    V78 --> V710
    V710 --> V711
    V711 --> V712
    V712 --> V713
    V713 --> V714
    
    V80 --> V82
    V82 --> V84
    V84 --> V86
    V86 --> V87
    
    V714 -.->|"No Direct Migration"| V82
```

**Sources:** [content/admin/releases/7.8.x/_index.en.adoc](), [content/admin/releases/7.10.x/_index.en.adoc](), [content/admin/releases/7.11.x/_index.en.adoc](), [content/admin/releases/7.12.x/_index.en.adoc](), [content/admin/releases/7.13.x/_index.en.adoc](), [content/admin/releases/7.14.x/_index.en.adoc](), [content/8.x/admin/releases/8.0/_index.en.adoc](), [content/8.x/admin/releases/8.2/_index.en.adoc](), [content/8.x/admin/releases/8.4/_index.en.adoc](), [content/8.x/admin/releases/8.6/_index.en.adoc](), [content/8.x/admin/releases/8.7/_index.en.adoc]()

## Release Documentation Structure

The version documentation follows a hierarchical file structure that mirrors the SuiteCRM release organization:

### Documentation File Mapping

```mermaid
graph LR
    subgraph "Admin Release Documentation"
        AdminRoot["content/admin/releases/"]
        V7Structure["7.x.x/_index.en.adoc"]
        V8Structure["content/8.x/admin/releases/8.x/_index.en.adoc"]
    end
    
    subgraph "Version-Specific Content"
        Release714["7.14.x/_index.en.adoc<br/>weight: 9850"]
        Release713["7.13.x/_index.en.adoc<br/>weight: 9860"]
        Release712["7.12.x/_index.en.adoc<br/>weight: 9870"]
        Release711["7.11.x/_index.en.adoc<br/>weight: 9880"]
        Release710["7.10.x/_index.en.adoc<br/>weight: 9890"]
        Release78["7.8.x/_index.en.adoc<br/>weight: 9910"]
    end
    
    subgraph "8.x Release Structure"
        Release87["8.7/_index.en.adoc<br/>weight: 9810"]
        Release86["8.6/_index.en.adoc<br/>weight: 9820"]
        Release85["8.5/_index.en.adoc<br/>weight: 9830"]
        Release84["8.4/_index.en.adoc<br/>weight: 9840"]
        Release82["8.2/_index.en.adoc<br/>weight: 9860"]
        Release80["8.0/_index.en.adoc<br/>weight: 9880"]
    end
    
    AdminRoot --> V7Structure
    AdminRoot --> V8Structure
    V7Structure --> Release714
    V7Structure --> Release713
    V7Structure --> Release712
    V7Structure --> Release711
    V7Structure --> Release710
    V7Structure --> Release78
    V8Structure --> Release87
    V8Structure --> Release86
    V8Structure --> Release85
    V8Structure --> Release84
    V8Structure --> Release82
    V8Structure --> Release80
```

**Sources:** [content/admin/releases/7.14.x/_index.en.adoc:1-4](), [content/admin/releases/7.13.x/_index.en.adoc:1-4](), [content/admin/releases/7.12.x/_index.en.adoc:1-4](), [content/8.x/admin/releases/8.7/_index.en.adoc:1-6](), [content/8.x/admin/releases/8.6/_index.en.adoc:1-6]()

## Version Support Lifecycle

### SuiteCRM 7.x Series Status

| Version | Status | Last Release | End of Life | PHP Support |
|---------|--------|-------------|-------------|-------------|
| 7.8.x | End of Life | 7.8.31 (July 2019) | July 2019 | PHP 5.6+ |
| 7.10.x | End of Life | 7.10.36 (January 2022) | January 2022 | PHP 7.0+ |
| 7.11.x | Maintenance | 7.11.23 (November 2021) | Maintenance Mode | PHP 7.1+ |
| 7.12.x | Active | 7.12.14 (November 2023) | Active Support | PHP 7.4+ |
| 7.13.x | Active | 7.13.4 (July 2023) | Active Support | PHP 8.0+ |
| 7.14.x | Active | 7.14.6 (November 2024) | Active Support | PHP 8.2+ |

### SuiteCRM 8.x Series Status

| Version | Status | Last Release | Key Features | PHP Requirement |
|---------|--------|-------------|--------------|-----------------|
| 8.0.x | Superseded | 8.0.4 (March 2022) | Angular Rewrite | PHP 7.4+ |
| 8.2.x | Migration Entry | 8.2.4 (March 2023) | Migration Support | PHP 7.4+ |
| 8.4.x | Superseded | 8.4.2 (November 2023) | PHP 8.1+ Required | PHP 8.1+ |
| 8.6.x | Active | 8.6.2 (August 2024) | Login Language Config | PHP 8.1+ |
| 8.7.x | Active | 8.7.1 (November 2024) | Angular 18, 2FA | PHP 8.1+ |

**Sources:** [content/admin/releases/7.8.x/_index.en.adoc:14-16](), [content/admin/releases/7.10.x/_index.en.adoc:22](), [content/8.x/admin/releases/8.4/_index.en.adoc:185-189]()

## API Evolution Across Versions

### API Version Compatibility Matrix

```mermaid
graph TB
    subgraph "API v4.1 - SOAP & REST"
        API41["API v4.1<br/>SOAP & REST<br/>Available in ALL Versions"]
        SOAP["SOAP Endpoint<br/>service/v4_1/soap.php"]
        REST["REST Endpoint<br/>service/v4_1/rest.php"]
    end
    
    subgraph "API v8 - JSON API"
        API8["API v8<br/>JSON API & OAuth2<br/>8.x Only"]
        JSON["JSON API Endpoint<br/>/api/graphql"]
        OAuth["OAuth2 Authentication"]
    end
    
    subgraph "Version Compatibility"
        All7x["All 7.x Versions<br/>7.8.x - 7.14.x"]
        All8x["All 8.x Versions<br/>8.0.x - 8.7.x"]
    end
    
    API41 --> SOAP
    API41 --> REST
    API41 --> All7x
    API41 --> All8x
    
    API8 --> JSON
    API8 --> OAuth
    API8 --> All8x
```

**Sources:** [content/developer/api/API-4_1.adoc:14-18](), [content/developer/api/API-4_1.adoc:25-29]()

### API Authentication Methods

The authentication mechanisms vary significantly between API versions:

**API v4.1 Authentication:**
- Username/password authentication with MD5 hashing
- Session-based authentication using `login()` method
- Available in both SOAP and REST implementations

**API v8 Authentication:**
- OAuth2 with client credentials flow
- Bearer token authentication
- JWT token support for enhanced security

**Sources:** [content/developer/api/API-4_1.adoc:47-51]()

## Security and CVE Tracking

### Security Vulnerability Management

The documentation tracks security vulnerabilities using CVE (Common Vulnerabilities and Exposures) identifiers across all versions:

```mermaid
graph LR
    subgraph "Security Issue Types"
        SQL["SQL Injection<br/>CVE-2024-49773"]
        XSS["XSS Vulnerabilities<br/>CVE-2024-50335"]
        RCE["Remote Code Execution<br/>CVE-2024-50333"]
        Access["Improper Access Control<br/>CVE-2024-45392"]
    end
    
    subgraph "Affected Versions"
        V7All["7.x Series<br/>All Active Versions"]
        V8All["8.x Series<br/>All Active Versions"]
    end
    
    subgraph "Resolution Process"
        Report["Security Report<br/>security@suitecrm.com"]
        CVE["CVE Assignment"]
        Patch["Security Patch Release"]
    end
    
    SQL --> V7All
    SQL --> V8All
    XSS --> V7All
    XSS --> V8All
    RCE --> V7All
    RCE --> V8All
    Access --> V8All
    
    V7All --> Report
    V8All --> Report
    Report --> CVE
    CVE --> Patch
```

**Sources:** [content/admin/releases/7.14.x/_index.en.adoc:26-31](), [content/8.x/admin/releases/8.7/_index.en.adoc:29-34](), [content/admin/releases/7.12.x/_index.en.adoc:223-230]()

## Migration and Upgrade Paths

### Version Upgrade Complexity

The upgrade paths between versions vary significantly in complexity:

**Within 7.x Series:**
- Sequential upgrades generally supported (7.12.x → 7.13.x → 7.14.x)
- Minor version upgrades within series (7.14.5 → 7.14.6)
- Database schema updates handled automatically

**7.x to 8.x Migration:**
- No direct upgrade path available
- Complete system migration required
- Data migration tools provided for 8.2.0+
- Legacy mode available for compatibility

**Within 8.x Series:**
- Standard upgrade procedures between minor versions
- Breaking changes documented in release notes
- Configuration updates may be required

**Sources:** [content/8.x/admin/upgrading/general-info.adoc:14-17](), [content/8.x/admin/releases/8.4/_index.en.adoc:211-219]()

## Release Notes Structure

### Standard Release Documentation Format

Each version's release documentation follows a consistent structure implemented across all version files:

```mermaid
graph TB
    subgraph "Release Note Components"
        Assets["Assets Section<br/>GitHub Download Links<br/>Install/Upgrade Guides"]
        Security["Security Section<br/>CVE Identifiers<br/>Vulnerability Descriptions"]
        Features["Features/Enhancements<br/>New Functionality<br/>Configuration Changes"]
        Bugs["Bug Fixes<br/>GitHub Issue References<br/>Pull Request Links"]
        Community["Community Section<br/>Contributor Recognition<br/>Security Reporters"]
    end
    
    subgraph "Documentation Elements"
        Icons["AsciiDoc Icons<br/>icon:box-open[]<br/>icon:lock[]<br/>icon:bug[]"]
        Links["GitHub References<br/>PR Numbers<br/>Issue Numbers"]
        CVELinks["CVE Database Links<br/>NIST/MITRE References"]
    end
    
    Assets --> Icons
    Security --> Icons
    Security --> CVELinks
    Bugs --> Links
    Features --> Icons
    Community --> Links
```

**Sources:** [content/admin/releases/7.14.x/_index.en.adoc:15-32](), [content/8.x/admin/releases/8.7/_index.en.adoc:17-34](), [layouts/shortcodes/ghcontributors.html:1-36]()

## Community Contribution Tracking

### Contributor Recognition System

The documentation system includes automated contributor recognition using GitHub usernames:

**GitHub Contributors Shortcode:**
- Implemented in `layouts/shortcodes/ghcontributors.html`
- Displays contributor avatars and GitHub profiles
- Used across all release notes for community recognition

**Community Contribution Categories:**
- Security vulnerability reporting
- Bug fixes and feature development
- Documentation improvements
- Translation and localization

**Sources:** [layouts/shortcodes/ghcontributors.html:23-34](), [content/admin/releases/7.14.x/_index.en.adoc:52-58]()