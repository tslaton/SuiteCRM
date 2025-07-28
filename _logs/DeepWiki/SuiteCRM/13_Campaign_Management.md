# Campaign Management

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [include/generic/SugarWidgets/SugarWidgetSubPanelTopCreateCampaignMarketingEmailButton.php](include/generic/SugarWidgets/SugarWidgetSubPanelTopCreateCampaignMarketingEmailButton.php)
- [jssource/src_files/modules/Campaigns/wizard.js](jssource/src_files/modules/Campaigns/wizard.js)
- [modules/Campaigns/DotListWizardMenu.php](modules/Campaigns/DotListWizardMenu.php)
- [modules/Campaigns/Subscriptions.html](modules/Campaigns/Subscriptions.html)
- [modules/Campaigns/Subscriptions.php](modules/Campaigns/Subscriptions.php)
- [modules/Campaigns/Subscriptions.tpl](modules/Campaigns/Subscriptions.tpl)
- [modules/Campaigns/WizardEmailSetup.html](modules/Campaigns/WizardEmailSetup.html)
- [modules/Campaigns/WizardHome.html](modules/Campaigns/WizardHome.html)
- [modules/Campaigns/WizardHome.php](modules/Campaigns/WizardHome.php)
- [modules/Campaigns/WizardMarketing.html](modules/Campaigns/WizardMarketing.html)
- [modules/Campaigns/WizardMarketing.php](modules/Campaigns/WizardMarketing.php)
- [modules/Campaigns/WizardNewsletter.html](modules/Campaigns/WizardNewsletter.html)
- [modules/Campaigns/WizardNewsletter.php](modules/Campaigns/WizardNewsletter.php)
- [modules/Campaigns/language/en_us.lang.php](modules/Campaigns/language/en_us.lang.php)
- [modules/Campaigns/metadata/listviewdefs.php](modules/Campaigns/metadata/listviewdefs.php)
- [modules/Campaigns/metadata/subpaneldefs.php](modules/Campaigns/metadata/subpaneldefs.php)
- [modules/Campaigns/tpls/WizardCampaignBudget.tpl](modules/Campaigns/tpls/WizardCampaignBudget.tpl)
- [modules/Campaigns/tpls/WizardCampaignHeader.tpl](modules/Campaigns/tpls/WizardCampaignHeader.tpl)
- [modules/Campaigns/tpls/WizardCampaignTargetList.tpl](modules/Campaigns/tpls/WizardCampaignTargetList.tpl)
- [modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl](modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl)
- [modules/Campaigns/tpls/WizardCampaignTracker.tpl](modules/Campaigns/tpls/WizardCampaignTracker.tpl)
- [modules/Campaigns/tpls/WizardHomeStart.tpl](modules/Campaigns/tpls/WizardHomeStart.tpl)
- [modules/Campaigns/tpls/WizardNewsletter.tpl](modules/Campaigns/tpls/WizardNewsletter.tpl)
- [modules/Campaigns/tpls/progressStepsStyle.html](modules/Campaigns/tpls/progressStepsStyle.html)
- [modules/Campaigns/wizard.js](modules/Campaigns/wizard.js)
- [modules/EmailMan/EmailManDelivery.php](modules/EmailMan/EmailManDelivery.php)
- [modules/EmailMarketing/List.php](modules/EmailMarketing/List.php)
- [modules/EmailTemplates/EmailTemplate.css](modules/EmailTemplates/EmailTemplate.css)
- [modules/EmailTemplates/EmailTemplateData.php](modules/EmailTemplates/EmailTemplateData.php)
- [modules/EmailTemplates/EmailTemplateFormBase.php](modules/EmailTemplates/EmailTemplateFormBase.php)

</details>



This document covers the Campaign Management system in SuiteCRM, which provides comprehensive tools for creating, managing, and executing marketing campaigns. The system includes a multi-step wizard interface, email template management, target list handling, and email delivery capabilities.

For information about the underlying email system infrastructure, see [Email System](#4.2). For user management and authentication details, see [User Management](#4.1).

## System Architecture Overview

The Campaign Management system is built around a wizard-based interface that guides users through campaign creation. The system supports multiple campaign types including newsletters, email campaigns, and surveys, with integrated email template management and target list handling.

```mermaid
graph TB
    CampaignWizard["Campaign Wizard"]
    EmailTemplates["Email Templates"]
    TargetLists["Target Lists"]
    EmailDelivery["Email Delivery"]
    CampaignTrackers["Campaign Trackers"]
    
    CampaignWizard --> EmailTemplates
    CampaignWizard --> TargetLists
    CampaignWizard --> EmailDelivery
    CampaignWizard --> CampaignTrackers
    
    EmailTemplates --> EmailDelivery
    TargetLists --> EmailDelivery
    
    subgraph "Campaign Types"
        Newsletter["Newsletter"]
        EmailCampaign["Email Campaign"]
        Survey["Survey"]
        Telesales["Telesales"]
    end
    
    CampaignWizard --> Newsletter
    CampaignWizard --> EmailCampaign
    CampaignWizard --> Survey
    CampaignWizard --> Telesales
```

**Sources:** [modules/Campaigns/WizardMarketing.php:1-600](), [modules/Campaigns/WizardNewsletter.php:1-600](), [modules/Campaigns/language/en_us.lang.php:45-470]()

## Campaign Wizard System

The campaign wizard provides a multi-step interface for campaign creation, implemented through several key components:

### Wizard Navigation and Flow

The wizard uses a step-based navigation system controlled by JavaScript functions and PHP backend processing:

```mermaid
graph LR
    Step1["Step 1: Campaign Header"]
    Step2["Step 2: Target Lists"]
    Step3["Step 3: Email Templates"] 
    Step4["Step 4: Marketing Setup"]
    Step5["Step 5: Summary"]
    
    Step1 --> Step2
    Step2 --> Step3
    Step3 --> Step4
    Step4 --> Step5
    
    Step1 -.-> WizardNewsletter["WizardNewsletter.php"]
    Step3 -.-> WizardMarketing["WizardMarketing.php"]
    Step5 -.-> WizardHome["WizardHome.php"]
    
    subgraph "JavaScript Navigation"
        navigate["navigate()"]
        showfirst["showfirst()"]
        validate_wiz["validate_wiz()"]
    end
    
    Step1 --> navigate
    Step2 --> navigate
    Step3 --> navigate
    Step4 --> navigate
```

### Core Wizard Classes and Functions

| Component | File | Key Functions |
|-----------|------|---------------|
| `DotListWizardMenu` | [modules/Campaigns/DotListWizardMenu.php]() | `getWizardMenuHTML()`, `getWizardMenuItemHTML()` |
| `navigate()` | [modules/Campaigns/wizard.js:134-248]() | Step navigation logic |
| `validate_wiz()` | [modules/Campaigns/wizard.js:365-402]() | Form validation |
| `campaignCreateAndRefreshPage()` | [modules/Campaigns/wizard.js:251-279]() | Campaign creation |

**Sources:** [modules/Campaigns/wizard.js:1-500](), [modules/Campaigns/DotListWizardMenu.php:1-58](), [modules/Campaigns/WizardMarketing.html:45-500]()

## Email Template Management

The system provides comprehensive email template management through the `EmailTemplateData.php` entry point and related classes:

### Template Operations

```mermaid
graph TB
    EmailTemplateData["EmailTemplateData.php"]
    EmailTemplateFormBase["EmailTemplateFormBase"]
    EmailTemplate["EmailTemplate Bean"]
    
    EmailTemplateData --> |"get"| EmailTemplate
    EmailTemplateData --> |"update"| EmailTemplate
    EmailTemplateData --> |"createCopy"| EmailTemplate
    EmailTemplateData --> |"uploadAttachments"| EmailTemplateFormBase
    
    EmailTemplateFormBase --> |"handleAttachmentsProcessImages()"| EmailTemplate
    
    subgraph "Template Functions"
        onEmailTemplateChange["onEmailTemplateChange()"]
        showEmailTemplateAttachments["showEmailTemplateAttachments()"]
    end
    
    EmailTemplateData --> onEmailTemplateChange
    EmailTemplateData --> showEmailTemplateAttachments
    
    subgraph "Campaign Integration"
        MarketingWizard["WizardMarketing"]
        SessionData["$_SESSION campaignWizard"]
    end
    
    EmailTemplateData --> MarketingWizard
    EmailTemplateData --> SessionData
```

### Template Data Handling

The template system supports various operations through the `EmailTemplateData.php` entry point:

| Operation | Function | Description |
|-----------|----------|-------------|
| `get` | Default operation | Retrieves template data including attachments |
| `update` | Template modification | Updates existing template with new content |
| `createCopy` | Template duplication | Creates a copy of existing template |
| `uploadAttachments` | File handling | Manages template attachments |

**Sources:** [modules/EmailTemplates/EmailTemplateData.php:1-158](), [modules/EmailTemplates/EmailTemplateFormBase.php:1-600](), [modules/Campaigns/wizard.js:416-500]()

## Target List Management

Target lists are managed through the campaign wizard interface with support for different list types:

### Target List Types and Structure

```mermaid
graph TB
    ProspectLists["ProspectLists Bean"]
    
    subgraph "List Types"
        DefaultList["default - Primary targets"]
        TestList["test - Test recipients"]  
        ExemptList["exempt - Suppression list"]
        SeedList["seed - Seed addresses"]
        ExemptDomain["exempt_domain - Domain suppression"]
        ExemptAddress["exempt_address - Email suppression"]
    end
    
    ProspectLists --> DefaultList
    ProspectLists --> TestList
    ProspectLists --> ExemptList
    ProspectLists --> SeedList
    ProspectLists --> ExemptDomain
    ProspectLists --> ExemptAddress
    
    subgraph "Campaign Integration"
        CampaignTargetList["WizardCampaignTargetListForNonNewsLetter.tpl"]
        TargetManagement["add_target() function"]
        PopupSelection["Target List Popup"]
    end
    
    ProspectLists --> CampaignTargetList
    CampaignTargetList --> TargetManagement
    CampaignTargetList --> PopupSelection
```

### Target List JavaScript Functions

The target list management includes several JavaScript functions for dynamic list handling:

| Function | Location | Purpose |
|----------|----------|---------|
| `add_target()` | [modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl:222-280]() | Adds target lists to campaign |
| `addTargetListData()` | [modules/Campaigns/wizard.js:475-483]() | Handles target list selection |
| `set_return_prospect_list()` | Referenced in templates | Popup callback for list selection |

**Sources:** [modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl:1-400](), [modules/Campaigns/WizardNewsletter.php:420-470](), [modules/Campaigns/wizard.js:475-500]()

## Email Marketing and Delivery

The email delivery system is handled through the `EmailManDelivery.php` system with integration to campaign marketing records:

### Email Delivery Architecture

```mermaid
graph TB
    EmailManDelivery["EmailManDelivery.php"]
    EmailMan["EmailMan Bean"]
    SugarPHPMailer["SugarPHPMailer"]
    OutboundEmailAccounts["OutboundEmailAccounts"]
    
    EmailManDelivery --> EmailMan
    EmailManDelivery --> SugarPHPMailer
    EmailManDelivery --> OutboundEmailAccounts
    
    subgraph "Campaign Components"
        EmailMarketing["EmailMarketing Bean"]
        Campaign["Campaign Bean"] 
        EmailTemplate["EmailTemplate Bean"]
        ProspectLists["ProspectLists Bean"]
    end
    
    EmailManDelivery --> EmailMarketing
    EmailMarketing --> Campaign
    EmailMarketing --> EmailTemplate
    Campaign --> ProspectLists
    
    subgraph "Delivery Process"
        QueueProcessing["Queue Processing"]
        SuppressionLists["Suppression Lists"]
        BounceHandling["Bounce Handling"]
        TestMode["Test Mode"]
    end
    
    EmailManDelivery --> QueueProcessing
    EmailManDelivery --> SuppressionLists
    EmailManDelivery --> BounceHandling
    EmailManDelivery --> TestMode
```

### Email Delivery Flow

The delivery system processes emails through several stages:

1. **Queue Selection**: Based on `send_date_time` and campaign criteria
2. **Validation**: Verifies campaign, marketing, and template records
3. **Suppression Processing**: Checks exempt lists and domains
4. **SMTP Configuration**: Uses campaign-specific or system SMTP settings
5. **Delivery Execution**: Sends emails with tracking and bounce handling

**Sources:** [modules/EmailMan/EmailManDelivery.php:1-300](), [modules/Campaigns/WizardMarketing.php:220-290]()

## Campaign Types and Wizard Variations

The system supports multiple campaign types with different wizard flows:

### Campaign Type Configuration

```mermaid
graph TB
    CampaignTypes["Campaign Types"]
    
    subgraph "Email-Based Campaigns"
        Newsletter["Newsletter"]
        EmailCampaign["Email Campaign"]
        Survey["Survey"]
    end
    
    subgraph "Non-Email Campaigns"
        Telesales["Telesales"]
        General["General"]
    end
    
    CampaignTypes --> Newsletter
    CampaignTypes --> EmailCampaign
    CampaignTypes --> Survey
    CampaignTypes --> Telesales
    CampaignTypes --> General
    
    subgraph "Wizard Steps"
        NewsletterSteps["Header → Budget → Subscriptions → Summary"]
        EmailSteps["Header → Budget → Target Lists → Templates → Marketing → Summary"]
        TelesalesSteps["Header → Budget → Target Lists → Summary"]
    end
    
    Newsletter --> NewsletterSteps
    EmailCampaign --> EmailSteps
    Survey --> EmailSteps
    Telesales --> TelesalesSteps
```

### Type-Specific Functionality

| Campaign Type | Key Features | Special Templates |
|---------------|--------------|------------------|
| Newsletter | Subscription management | [modules/Campaigns/tpls/WizardCampaignTargetList.tpl]() |
| Email | Multiple target lists | [modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl]() |
| Survey | Survey integration | Survey selection in header |
| Telesales | No email components | Simplified wizard flow |

**Sources:** [modules/Campaigns/WizardNewsletter.php:122-135](), [modules/Campaigns/language/en_us.lang.php:453-456](), [modules/Campaigns/tpls/WizardCampaignHeader.tpl:74-85]()

## Key Classes and Components

### Core Campaign Classes

| Class/Component | File | Primary Purpose |
|-----------------|------|-----------------|
| `Campaign` | Bean class | Core campaign entity |
| `EmailMarketing` | Bean class | Marketing message configuration |
| `EmailTemplate` | Bean class | Email template management |
| `ProspectLists` | Bean class | Target list management |
| `CampaignTrackers` | Bean class | URL tracking management |
| `EmailMan` | Bean class | Email queue management |

### Wizard Controllers and Actions

| Controller | File | Actions |
|------------|------|---------|
| `WizardNewsletter` | [modules/Campaigns/WizardNewsletter.php]() | Campaign header, budget, target lists |
| `WizardMarketing` | [modules/Campaigns/WizardMarketing.php]() | Email templates, marketing setup |
| `WizardHome` | [modules/Campaigns/WizardHome.php]() | Campaign summary and management |
| `EmailTemplateData` | [modules/EmailTemplates/EmailTemplateData.php]() | Template CRUD operations |

### JavaScript Navigation System

The wizard navigation is controlled by JavaScript functions that manage step transitions, validation, and form submission:

| Function | Purpose | Key Logic |
|----------|---------|-----------|
| `navigate()` | Step navigation | Validates current step, updates UI |
| `showfirst()` | Initial wizard setup | Shows first step, configures buttons |
| `validate_wiz()` | Step validation | Calls custom validation functions |
| `onEmailTemplateChange()` | Template selection | Loads template data via AJAX |

**Sources:** [modules/Campaigns/wizard.js:1-500](), [modules/Campaigns/WizardMarketing.php:1-600](), [modules/Campaigns/WizardNewsletter.php:1-600](), [modules/Campaigns/WizardHome.php:1-400]()