# Documentation Structure

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [.github/ISSUE_TEMPLATE.md](.github/ISSUE_TEMPLATE.md)
- [.github/ISSUE_TEMPLATE.md.NOT](.github/ISSUE_TEMPLATE.md.NOT)
- [LICENSE.md](LICENSE.md)
- [README.md](README.md)
- [archetypes/blog.md](archetypes/blog.md)
- [archetypes/default.md](archetypes/default.md)
- [content/_index.en.md](content/_index.en.md)
- [content/admin/Compatibility Matrix.adoc](content/admin/Compatibility Matrix.adoc)
- [i18n/en.toml](i18n/en.toml)
- [layouts/index.html](layouts/index.html)
- [layouts/partials/header.html](layouts/partials/header.html)
- [layouts/partials/menu.html](layouts/partials/menu.html)
- [layouts/partials/search.html](layouts/partials/search.html)
- [static/css/theme-suitecrm.css](static/css/theme-suitecrm.css)
- [static/css/theme.css](static/css/theme.css)
- [static/images/favicon.png](static/images/favicon.png)
- [themes/hugo-theme-learn/layouts/partials/menu.html](themes/hugo-theme-learn/layouts/partials/menu.html)

</details>



This document explains how the SuiteCRM documentation is organized across different user types, content categories, and technical implementation. It covers the hierarchical structure of documentation sections, content organization patterns, and navigation systems that make up the SuiteDocs repository.

For information about the technical build process and deployment pipeline, see [Documentation System Architecture](#2). For details about contributing to documentation, see [Contributing to Documentation](#8.1).

## Documentation Organization Hierarchy

The SuiteDocs repository follows a structured approach to organize documentation content across multiple dimensions: user types, SuiteCRM versions, and content categories.

```mermaid
graph TD
    DOCS["SuiteDocs Root"]
    
    subgraph "Primary Navigation Sections"
        USER["User Guide<br/>/user/"]
        DEV["Developer Guide<br/>/developer/"]
        ADMIN["Administrator Guide<br/>/admin/"]
        COMMUNITY["Community<br/>/community/"]
        BLOG["Technical Blog<br/>/blog/"]
    end
    
    subgraph "Content Categories by User Type"
        USER_CONTENT["Core Modules<br/>Advanced Features<br/>User Interface"]
        DEV_CONTENT["API Documentation<br/>Extensions<br/>Customization"]
        ADMIN_CONTENT["Installation<br/>Configuration<br/>Maintenance"]
        COMM_CONTENT["Contributing<br/>Issue Reporting<br/>Translation"]
    end
    
    subgraph "Version-Specific Documentation"
        V7["SuiteCRM 7.x<br/>7.8 - 7.14"]
        V8["SuiteCRM 8.x<br/>8.0 - 8.8"]
        API4["API v4.1<br/>SOAP & REST"]
        API8["API v8<br/>JSON API"]
    end
    
    DOCS --> USER
    DOCS --> DEV
    DOCS --> ADMIN
    DOCS --> COMMUNITY
    DOCS --> BLOG
    
    USER --> USER_CONTENT
    DEV --> DEV_CONTENT
    ADMIN --> ADMIN_CONTENT
    COMMUNITY --> COMM_CONTENT
    
    USER_CONTENT --> V7
    USER_CONTENT --> V8
    DEV_CONTENT --> API4
    DEV_CONTENT --> API8
    ADMIN_CONTENT --> V7
    ADMIN_CONTENT --> V8
```

**Top-Level Navigation Structure Implementation**

The main navigation is implemented in the menu template system, with distinct sections for different user audiences.

Sources: [layouts/partials/menu.html:64-75](), [i18n/en.toml:40-62]()

## Content File Organization

The documentation content follows a hierarchical file structure that mirrors the logical organization of information.

```mermaid
graph TB
    CONTENT["content/"]
    
    subgraph "Language Directories"
        EN["_index.en.md"]
        ES["es/ (Spanish)"]
        RU["ru/ (Russian)"]
    end
    
    subgraph "Section Directories"
        ADMIN_DIR["admin/"]
        USER_DIR["user/"]
        DEV_DIR["developer/"]
        COMM_DIR["community/"]
        BLOG_DIR["blog/"]
    end
    
    subgraph "Content Types"
        ADOC["AsciiDoc Files<br/>*.adoc"]
        MD["Markdown Files<br/>*.md"]
        ASSETS["Images & Assets<br/>/static/"]
    end
    
    subgraph "Example Admin Content"
        COMPAT["Compatibility Matrix.adoc"]
        INSTALL["Installation guides"]
        CONFIG["Configuration docs"]
    end
    
    CONTENT --> EN
    CONTENT --> ES
    CONTENT --> RU
    
    CONTENT --> ADMIN_DIR
    CONTENT --> USER_DIR
    CONTENT --> DEV_DIR
    CONTENT --> COMM_DIR
    CONTENT --> BLOG_DIR
    
    ADMIN_DIR --> COMPAT
    ADMIN_DIR --> INSTALL
    ADMIN_DIR --> CONFIG
    
    ADOC --> ADMIN_DIR
    MD --> USER_DIR
    ASSETS --> CONTENT
```

**File Naming and Weight System**

Content files use Hugo's weight system for ordering and AsciiDoc/Markdown frontmatter for metadata. The compatibility matrix demonstrates structured tabular documentation.

Sources: [content/admin/Compatibility Matrix.adoc:1-4](), [content/_index.en.md:1-6](), [archetypes/default.md:1-6]()

## Navigation System Implementation

The site navigation is implemented through Hugo's template system with multi-level menu generation and language switching capabilities.

```mermaid
graph LR
    subgraph "Navigation Templates"
        MENU_PARTIAL["menu.html"]
        HEADER_PARTIAL["header.html"]
        SEARCH_PARTIAL["search.html"]
    end
    
    subgraph "Menu Generation Logic"
        SECTION_TREE["section-tree-nav template"]
        WEIGHT_ORDER["ordersectionsby: weight"]
        VISITED_LINKS["showVisitedLinks"]
    end
    
    subgraph "Top Navigation Bar"
        TOP_MENU["topmenu"]
        GUIDES_MENU["Guides dropdown"]
        LANG_SELECT["Language selector"]
    end
    
    subgraph "Sidebar Navigation"
        SIDEBAR["#sidebar"]
        TOPICS_UL["ul.topics"]
        SEARCH_BOX["searchbox"]
    end
    
    MENU_PARTIAL --> SECTION_TREE
    MENU_PARTIAL --> TOP_MENU
    MENU_PARTIAL --> SIDEBAR
    
    SECTION_TREE --> WEIGHT_ORDER
    SECTION_TREE --> VISITED_LINKS
    
    TOP_MENU --> GUIDES_MENU
    SIDEBAR --> TOPICS_UL
    SIDEBAR --> SEARCH_BOX
    SIDEBAR --> LANG_SELECT
```

**Menu Template Structure**

The navigation system uses recursive template generation to build hierarchical menus based on Hugo's content structure.

Sources: [layouts/partials/menu.html:134-146](), [layouts/partials/menu.html:179-236](), [layouts/partials/header.html:67-85]()

## Multi-Language Documentation Structure

The documentation supports multiple languages through Hugo's multi-language configuration and i18n templates.

| Language | Path Prefix | Configuration File | Status |
|----------|-------------|-------------------|---------|
| English | `/` (root) | `i18n/en.toml` | Primary language |
| Spanish | `/es/` | `i18n/es.toml` | Secondary language |
| Russian | `/ru/` | `i18n/ru.toml` | Secondary language |

```mermaid
graph TD
    subgraph "Language Infrastructure"
        I18N["i18n/ directory"]
        EN_TOML["en.toml"]
        ES_TOML["es.toml"] 
        RU_TOML["ru.toml"]
    end
    
    subgraph "Content Translation"
        EN_CONTENT["English content<br/>(default)"]
        ES_CONTENT["Spanish content<br/>/es/ prefix"]
        RU_CONTENT["Russian content<br/>/ru/ prefix"]
    end
    
    subgraph "UI Translation Keys"
        NAV_KEYS["Navigation labels"]
        SEARCH_KEYS["Search placeholders"]
        GUIDE_KEYS["Guide section names"]
        ACTION_KEYS["Action button text"]
    end
    
    I18N --> EN_TOML
    I18N --> ES_TOML
    I18N --> RU_TOML
    
    EN_TOML --> EN_CONTENT
    ES_TOML --> ES_CONTENT
    RU_TOML --> RU_CONTENT
    
    EN_TOML --> NAV_KEYS
    EN_TOML --> SEARCH_KEYS
    EN_TOML --> GUIDE_KEYS
    EN_TOML --> ACTION_KEYS
```

**Language Switching Implementation**

The language selector is implemented in the sidebar with dropdown functionality and automatic URL translation.

Sources: [layouts/partials/menu.html:92-132](), [i18n/en.toml:1-147](), [layouts/partials/search.html:13-18]()

## Content Types and Styling

The documentation system supports different content types with specialized styling and formatting.

| Content Type | File Extension | Primary Use | Styling Classes |
|--------------|---------------|-------------|-----------------|
| AsciiDoc | `.adoc` | Structured documentation | `.admonition`, `.listingblock` |
| Markdown | `.md` | Simple content pages | `.markdown-body` |
| Blog Posts | `.md` | Technical blog content | `.blog-post`, `.tags` |
| Compatibility Tables | `.adoc` | Version matrices | `#smaller-table-spacing` |

```mermaid
graph LR
    subgraph "Content Processing Pipeline"
        ASCIIDOC["AsciiDoc<br/>*.adoc files"]
        MARKDOWN["Markdown<br/>*.md files"]
        HUGO_PROC["Hugo Processing"]
        STYLED_OUTPUT["Styled HTML Output"]
    end
    
    subgraph "CSS Theme System"
        BASE_THEME["theme.css"]
        SUITE_THEME["theme-suitecrm.css"]
        CUSTOM_STYLES["Custom component styles"]
    end
    
    subgraph "Special Content Features"
        TABLES["Compatibility matrices"]
        NOTICES["Info/Warning boxes"]
        CODE_BLOCKS["Syntax highlighting"]
        NAV_BREADCRUMBS["Breadcrumb navigation"]
    end
    
    ASCIIDOC --> HUGO_PROC
    MARKDOWN --> HUGO_PROC
    HUGO_PROC --> STYLED_OUTPUT
    
    BASE_THEME --> STYLED_OUTPUT
    SUITE_THEME --> STYLED_OUTPUT
    
    STYLED_OUTPUT --> TABLES
    STYLED_OUTPUT --> NOTICES
    STYLED_OUTPUT --> CODE_BLOCKS
    STYLED_OUTPUT --> NAV_BREADCRUMBS
```

**Specialized Table Styling**

The compatibility matrix uses custom CSS classes for compact table display with version-specific formatting.

Sources: [static/css/theme-suitecrm.css:119-128](), [content/admin/Compatibility Matrix.adoc:10-47](), [static/css/theme.css:726-737]()

## Homepage Structure and Call-to-Action System

The homepage implements a structured layout with multiple call-to-action sections targeting different user types.

```mermaid
graph TD
    subgraph "Homepage Layout Components"
        TITLE_WRAP["home-title-wrap"]
        CTA_SECTION["home-ctas"]
        RECENT_SECTION["home-recent"]
    end
    
    subgraph "Call-to-Action Blocks"
        CTA1["home-cta1<br/>Getting Started"]
        CTA2["home-cta2<br/>Using SuiteCRM"]
        CTA3["home-cta3<br/>Contributing"]
    end
    
    subgraph "Dynamic Content Areas"
        BLOG_LATEST["home-blog-latest<br/>Recent blog posts"]
        RECENTLY_EDITED["home-recently-edited<br/>Documentation updates"]
    end
    
    TITLE_WRAP --> CTA_SECTION
    CTA_SECTION --> CTA1
    CTA_SECTION --> CTA2
    CTA_SECTION --> CTA3
    
    CTA_SECTION --> RECENT_SECTION
    RECENT_SECTION --> BLOG_LATEST
    RECENT_SECTION --> RECENTLY_EDITED
```

**Homepage Template Implementation**

The homepage uses partial templates for modular content blocks with responsive flex layout.

Sources: [layouts/index.html:7-38](), [static/css/theme-suitecrm.css:873-1100](), [i18n/en.toml:70-147]()