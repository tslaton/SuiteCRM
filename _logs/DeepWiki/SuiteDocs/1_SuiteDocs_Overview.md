# SuiteDocs Overview

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [LICENSE.md](LICENSE.md)
- [README.md](README.md)
- [archetypes/blog.md](archetypes/blog.md)
- [archetypes/default.md](archetypes/default.md)
- [config.toml](config.toml)
- [content/community/contributing-code/Forking.adoc](content/community/contributing-code/Forking.adoc)
- [i18n/en.toml](i18n/en.toml)
- [i18n/ru.toml](i18n/ru.toml)
- [layouts/index.html](layouts/index.html)
- [layouts/partials/header.html](layouts/partials/header.html)
- [layouts/partials/menu.html](layouts/partials/menu.html)
- [layouts/partials/search.html](layouts/partials/search.html)
- [netlify.toml](netlify.toml)
- [static/css/theme-suitecrm.css](static/css/theme-suitecrm.css)
- [static/css/theme.css](static/css/theme.css)
- [static/images/favicon.png](static/images/favicon.png)
- [themes/hugo-theme-learn/layouts/partials/menu.html](themes/hugo-theme-learn/layouts/partials/menu.html)

</details>



SuiteDocs is the comprehensive documentation system for SuiteCRM, serving as the primary source of technical documentation, user guides, and community resources for the SuiteCRM open-source CRM platform. This system generates and hosts the documentation website at https://docs.suitecrm.com, providing structured information for administrators, end users, developers, and community contributors.

The SuiteDocs repository contains the source content, build configuration, and deployment infrastructure for generating a multi-language static documentation site. It covers installation procedures, user interface guides, development APIs, customization techniques, and community contribution guidelines across multiple SuiteCRM versions (7.x and 8.x series).

For information about contributing code to SuiteCRM itself, see [Community Contribution](#8). For details about SuiteCRM version compatibility, see [Version Compatibility Matrix](#3.3).

## Documentation System Architecture

```mermaid
graph TB
    subgraph "Source Repository"
        CONFIG["config.toml<br/>Hugo Configuration"]
        NETLIFY_CONFIG["netlify.toml<br/>Deployment Config"]
        CONTENT["content/<br/>AsciiDoc & Markdown"]
        LAYOUTS["layouts/<br/>Hugo Templates"]
        STATIC["static/<br/>CSS, Images, JS"]
        I18N["i18n/<br/>Translation Files"]
    end
    
    subgraph "Build Process"
        HUGO["Hugo Static Generator<br/>v0.85.0"]
        ASCIIDOCTOR["AsciiDoctor Engine<br/>Ruby Processing"]
        THEME["hugo-theme-learn<br/>SuiteCRM Variant"]
    end
    
    subgraph "Generated Output"
        PUBLIC["public/<br/>Static HTML/CSS/JS"]
        INDEXES["Search Indexes<br/>Lunr.js"]
        LANG_DIRS["Language Directories<br/>/en/, /es/, /ru/"]
    end
    
    subgraph "Deployment"
        NETLIFY["Netlify Platform"]
        PROD["docs.suitecrm.com<br/>Production Site"]
        PREVIEW["Deploy Previews<br/>PR Testing"]
    end
    
    CONFIG --> HUGO
    NETLIFY_CONFIG --> NETLIFY
    CONTENT --> ASCIIDOCTOR
    LAYOUTS --> HUGO
    STATIC --> HUGO
    I18N --> HUGO
    
    HUGO --> PUBLIC
    ASCIIDOCTOR --> PUBLIC
    THEME --> PUBLIC
    
    PUBLIC --> INDEXES
    PUBLIC --> LANG_DIRS
    
    PUBLIC --> NETLIFY
    NETLIFY --> PROD
    NETLIFY --> PREVIEW
```

**Sources:** [config.toml:1-118](), [netlify.toml:1-32](), [README.md:12-17](), [layouts/partials/header.html:1-118]()

## Hugo Configuration System

The documentation system uses Hugo with specific configuration parameters that define the site structure, language support, and build behavior:

| Configuration Area | File | Key Settings |
|-------------------|------|-------------|
| Base Configuration | `config.toml` | `baseURL`, `title`, `theme`, `timeout` |
| Build Settings | `config.toml` | `enableGitInfo`, `metaDataFormat: "yaml"` |
| Language Configuration | `config.toml` | `defaultContentLanguage: "en"` |
| Theme Variant | `config.toml` | `themeVariant = "suitecrm"` |
| Deployment | `netlify.toml` | `HUGO_VERSION = "0.85.0"` |

```mermaid
graph LR
    subgraph "Hugo Configuration Flow"
        CONFIG_TOML["config.toml"]
        PARAMS["Site Params"]
        LANGUAGES["Languages Config"]
        MENUS["Menu Shortcuts"]
        OUTPUTS["Output Formats"]
    end
    
    subgraph "Key Parameters"
        GITHUB_REPO["GitHubRepo<br/>EditURL<br/>IssueURL"]
        THEME_VAR["themeVariant: suitecrm"]
        VISITED_LINKS["showVisitedLinks: true"]
        GIT_INFO["enableGitInfo: true"]
    end
    
    CONFIG_TOML --> PARAMS
    CONFIG_TOML --> LANGUAGES
    CONFIG_TOML --> MENUS
    CONFIG_TOML --> OUTPUTS
    
    PARAMS --> GITHUB_REPO
    PARAMS --> THEME_VAR
    PARAMS --> VISITED_LINKS
    PARAMS --> GIT_INFO
```

**Sources:** [config.toml:13-23](), [config.toml:34-40](), [netlify.toml:10-13]()

## Multi-Language Support Architecture

SuiteDocs implements comprehensive internationalization through Hugo's built-in multi-language features, supporting English, Spanish, and Russian translations:

```mermaid
graph TB
    subgraph "Language Configuration"
        EN_CONFIG["Languages.en<br/>Weight: 1<br/>LanguageDir: /"]
        ES_CONFIG["Languages.es<br/>Weight: 2<br/>LanguageDir: /es/"]
        RU_CONFIG["Languages.ru<br/>Weight: 3<br/>LanguageDir: /ru/"]
    end
    
    subgraph "Translation Files"
        EN_I18N["i18n/en.toml<br/>147 translation keys"]
        ES_I18N["i18n/es.toml<br/>Spanish translations"]
        RU_I18N["i18n/ru.toml<br/>Russian translations"]
    end
    
    subgraph "Menu Generation"
        MENU_HTML["layouts/partials/menu.html"]
        SELECT_LANG["Language Selector"]
        LANG_SWITCH["Dynamic Language Switching"]
    end
    
    subgraph "URL Structure"
        ROOT_URL["docs.suitecrm.com/<br/>English (default)"]
        ES_URL["docs.suitecrm.com/es/<br/>Spanish"]
        RU_URL["docs.suitecrm.com/ru/<br/>Russian"]
    end
    
    EN_CONFIG --> EN_I18N
    ES_CONFIG --> ES_I18N
    RU_CONFIG --> RU_I18N
    
    EN_I18N --> MENU_HTML
    ES_I18N --> MENU_HTML
    RU_I18N --> MENU_HTML
    
    MENU_HTML --> SELECT_LANG
    SELECT_LANG --> LANG_SWITCH
    
    EN_CONFIG --> ROOT_URL
    ES_CONFIG --> ES_URL
    RU_CONFIG --> RU_URL
```

**Sources:** [config.toml:28-118](), [i18n/en.toml:1-147](), [i18n/ru.toml:1-142](), [layouts/partials/menu.html:92-131]()

## Content Organization and Navigation

The documentation content follows a hierarchical structure with specialized sections for different user types and use cases:

```mermaid
graph TD
    subgraph "Main Navigation Structure"
        DOCS_ROOT["Documentation Root"]
        USER_GUIDE["user/<br/>End User Documentation"]
        ADMIN_GUIDE["admin/<br/>Administrator Guide"]
        DEV_GUIDE["developer/<br/>Developer Documentation"]
        COMMUNITY["community/<br/>Community Guidelines"]
        BLOG["blog/<br/>Technical Blog"]
    end
    
    subgraph "Menu System"
        TOP_MENU["layouts/partials/menu.html<br/>Line 64-75"]
        SIDEBAR_NAV["Sidebar Navigation<br/>Section Tree"]
        BREADCRUMBS["Breadcrumb Navigation<br/>header.html"]
    end
    
    subgraph "Content Processing"
        ASCIIDOC["*.adoc files<br/>AsciiDoc Format"]
        MARKDOWN["*.md files<br/>Markdown Format"]
        HUGO_PROCESS["Hugo Processing<br/>Static Generation"]
    end
    
    DOCS_ROOT --> USER_GUIDE
    DOCS_ROOT --> ADMIN_GUIDE
    DOCS_ROOT --> DEV_GUIDE
    DOCS_ROOT --> COMMUNITY
    DOCS_ROOT --> BLOG
    
    TOP_MENU --> SIDEBAR_NAV
    SIDEBAR_NAV --> BREADCRUMBS
    
    ASCIIDOC --> HUGO_PROCESS
    MARKDOWN --> HUGO_PROCESS
    HUGO_PROCESS --> SIDEBAR_NAV
```

**Sources:** [layouts/partials/menu.html:64-75](), [layouts/partials/header.html:68-85](), [content/community/contributing-code/Forking.adoc:1-57]()

## Build and Deployment Pipeline

```mermaid
graph LR
    subgraph "Source Control"
        GITHUB["GitHub Repository<br/>salesagility/SuiteDocs"]
        MASTER["master branch"]
        PR["Pull Requests"]
    end
    
    subgraph "Build Configuration"
        BUILD_SH["build.sh<br/>Custom Build Script"]
        NETLIFY_TOML["netlify.toml<br/>Build Commands"]
        HUGO_CONFIG["Hugo v0.85.0<br/>Static Generation"]
    end
    
    subgraph "Build Process"
        CHMOD["chmod +x build.sh"]
        BUNDLE["Ruby Bundle<br/>AsciiDoctor"]
        HUGO_BUILD["Hugo Build<br/>Static Site Generation"]
        CSS_COMPILE["CSS Processing<br/>theme-suitecrm.css"]
    end
    
    subgraph "Deployment Targets"
        PRODUCTION["docs.suitecrm.com<br/>Production Site"]
        DEPLOY_PREVIEW["Deploy Previews<br/>PR Testing"]
        BRANCH_DEPLOY["Branch Deployments<br/>Feature Testing"]
    end
    
    GITHUB --> MASTER
    GITHUB --> PR
    
    MASTER --> BUILD_SH
    PR --> BUILD_SH
    
    BUILD_SH --> NETLIFY_TOML
    NETLIFY_TOML --> HUGO_CONFIG
    
    HUGO_CONFIG --> CHMOD
    CHMOD --> BUNDLE
    BUNDLE --> HUGO_BUILD
    HUGO_BUILD --> CSS_COMPILE
    
    CSS_COMPILE --> PRODUCTION
    CSS_COMPILE --> DEPLOY_PREVIEW
    CSS_COMPILE --> BRANCH_DEPLOY
```

**Sources:** [netlify.toml:3-6](), [netlify.toml:15-21](), [netlify.toml:22-27](), [README.md:21-29]()

## Theme and Styling System

The documentation uses a customized version of the Hugo Learn theme with SuiteCRM-specific styling and branding:

```mermaid
graph TB
    subgraph "Theme Architecture"
        HUGO_THEME["hugo-theme-learn<br/>Base Theme"]
        THEME_VARIANT["themeVariant: suitecrm<br/>config.toml:22"]
        THEME_CSS["static/css/theme-suitecrm.css<br/>1401 lines"]
    end
    
    subgraph "CSS Structure"
        CSS_VARS["CSS Variables<br/>Lines 1-21"]
        MAIN_CONTENT["Main Content Styles<br/>Lines 23-321"]
        WEB_NAV["Web Navigation<br/>Lines 393-537"]
        SIDEBAR["Sidebar Styles<br/>Lines 601-837"]
        RESPONSIVE["Media Queries<br/>Lines 1103-1322"]
    end
    
    subgraph "Color Scheme"
        PRIMARY["--MAIN-LINK-color: #ED6758"]
        MENU_BG["--MENU-HEADER-BG-color: #5C566A"]
        SEARCH_BG["--MENU-SEARCH-BG-color: #696D7D"]
        ACTIVE_BG["--MENU-SECTIONS-ACTIVE-BG-color: #fff"]
    end
    
    subgraph "Layout Components"
        HEADER["layouts/partials/header.html<br/>CSS Includes"]
        MENU_PARTIAL["layouts/partials/menu.html<br/>Navigation Structure"]
        SEARCH["layouts/partials/search.html<br/>Search Interface"]
    end
    
    HUGO_THEME --> THEME_VARIANT
    THEME_VARIANT --> THEME_CSS
    
    THEME_CSS --> CSS_VARS
    THEME_CSS --> MAIN_CONTENT
    THEME_CSS --> WEB_NAV
    THEME_CSS --> SIDEBAR
    THEME_CSS --> RESPONSIVE
    
    CSS_VARS --> PRIMARY
    CSS_VARS --> MENU_BG
    CSS_VARS --> SEARCH_BG
    CSS_VARS --> ACTIVE_BG
    
    HEADER --> CSS_VARS
    MENU_PARTIAL --> SIDEBAR
    SEARCH --> SEARCH_BG
```

**Sources:** [static/css/theme-suitecrm.css:1-21](), [static/css/theme-suitecrm.css:393-537](), [layouts/partials/header.html:13-25](), [layouts/partials/search.html:1-20]()

## Search and Navigation Features

```mermaid
graph LR
    subgraph "Search System"
        LUNR["lunr.min.js<br/>Search Engine"]
        LUNR_MULTI["lunr.multi.js<br/>Multi-language"]
        LUNR_RU["lunr.ru.js<br/>Russian Support"]
        SEARCH_JS["search.js<br/>Search Interface"]
    end
    
    subgraph "Navigation Features"
        VISITED_LINKS["showVisitedLinks: true<br/>Read Status Tracking"]
        BREADCRUMBS["Breadcrumb Navigation<br/>header.html:68-85"]
        SIDEBAR_TOGGLE["Mobile Sidebar Toggle<br/>Responsive Design"]
        EDIT_LINKS["GitHub Edit Links<br/>Issue Reporting"]
    end
    
    subgraph "User Interface"
        SEARCH_BOX["Search Input Box<br/>Auto-complete"]
        LANG_SELECTOR["Language Selector<br/>Dropdown Menu"]
        TOP_BAR["Top Navigation Bar<br/>Guide Categories"]
        GITHUB_LINKS["GitHub Integration<br/>Edit/Issue Links"]
    end
    
    LUNR --> SEARCH_BOX
    LUNR_MULTI --> SEARCH_BOX
    LUNR_RU --> SEARCH_BOX
    SEARCH_JS --> SEARCH_BOX
    
    VISITED_LINKS --> SIDEBAR_TOGGLE
    BREADCRUMBS --> TOP_BAR
    SIDEBAR_TOGGLE --> LANG_SELECTOR
    EDIT_LINKS --> GITHUB_LINKS
```

**Sources:** [layouts/partials/search.html:7-19](), [layouts/partials/header.html:49-66](), [config.toml:21](), [layouts/partials/menu.html:190-196]()