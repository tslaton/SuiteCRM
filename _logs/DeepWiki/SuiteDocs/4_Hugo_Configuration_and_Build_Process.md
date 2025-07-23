# Hugo Configuration and Build Process

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [Gemfile](Gemfile)
- [Gemfile.lock](Gemfile.lock)
- [_source/fixes.php](_source/fixes.php)
- [_source/yamlize](_source/yamlize)
- [config.toml](config.toml)
- [content/community/contributing-code/Forking.adoc](content/community/contributing-code/Forking.adoc)
- [content/developer/database-schema.adoc](content/developer/database-schema.adoc)
- [i18n/ru.toml](i18n/ru.toml)
- [layouts/_default/single.html](layouts/_default/single.html)
- [layouts/partials/header-link.html](layouts/partials/header-link.html)
- [netlify.toml](netlify.toml)
- [static/images/en/developer/database-schema/schema.png](static/images/en/developer/database-schema/schema.png)
- [static/images/en/developer/database-schema/schemaspy.png](static/images/en/developer/database-schema/schemaspy.png)
- [static/images/ru/user/core-modules/Calls/image2.png](static/images/ru/user/core-modules/Calls/image2.png)
- [static/images/ru/user/core-modules/Calls/image5.png](static/images/ru/user/core-modules/Calls/image5.png)
- [static/images/ru/user/core-modules/Meetings/image2.png](static/images/ru/user/core-modules/Meetings/image2.png)

</details>



This document covers the Hugo static site generator configuration and build process used by the SuiteDocs documentation system. It details how content is processed from AsciiDoc sources into the final deployed website, including multi-language support and deployment workflows.

For information about multi-language content structure and translation workflows, see [Multi-language Support](#2.2). For deployment specifics and Netlify configuration, see [Netlify Deployment](#2.3).

## Hugo Configuration Structure

The SuiteDocs system uses Hugo with a customized version of the `hugo-theme-learn` theme. The main configuration is defined in `config.toml`, which establishes the site structure, language settings, and integration parameters.

### Core Site Configuration

The base Hugo configuration defines essential site properties and build behavior:

```mermaid
graph TB
    config["config.toml"]
    
    subgraph "Site Properties"
        baseURL["baseURL: docs.suitecrm.com"]
        theme["theme: hugo-theme-learn"]
        timeout["timeout: 47000"]
        gitinfo["enableGitInfo: true"]
    end
    
    subgraph "Content Processing"
        metadata["metaDataFormat: yaml"]
        errors["ignoreErrors: error-remote-getjson"]
        outputs["outputs: HTML, RSS, JSON"]
    end
    
    subgraph "Parameters"
        github["GitHubRepo"]
        editurl["editURL"]
        issueurl["issueURL"]
        variant["themeVariant: suitecrm"]
    end
    
    config --> baseURL
    config --> theme
    config --> timeout
    config --> gitinfo
    config --> metadata
    config --> errors
    config --> outputs
    config --> github
    config --> editurl
    config --> issueurl
    config --> variant
```

**Sources:** [config.toml:1-27]()

### Multi-language Architecture

The configuration supports three languages with distinct URL structures and menu configurations:

```mermaid
graph TB
    languages["Languages Configuration"]
    
    subgraph "English (Default)"
        en_config["Languages.en"]
        en_weight["weight: 1"]
        en_dir["languageDir: /"]
        en_menus["menu.shortcuts"]
    end
    
    subgraph "Spanish"
        es_config["Languages.es"]
        es_weight["weight: 2"]
        es_dir["languageDir: /es/"]
        es_menus["menu.shortcuts"]
    end
    
    subgraph "Russian"
        ru_config["Languages.ru"]
        ru_weight["weight: 3"]
        ru_dir["languageDir: /ru/"]
        ru_menus["menu.shortcuts"]
    end
    
    languages --> en_config
    languages --> es_config
    languages --> ru_config
    
    en_config --> en_weight
    en_config --> en_dir
    en_config --> en_menus
    
    es_config --> es_weight
    es_config --> es_dir
    es_config --> es_menus
    
    ru_config --> ru_weight
    ru_config --> ru_dir
```

**Sources:** [config.toml:28-118]()

## Build Dependencies and Process

The build system combines Hugo's static site generation with AsciiDoctor processing for enhanced documentation formatting capabilities.

### Dependency Management

The system uses Ruby's Bundler to manage AsciiDoctor dependencies:

| Component | Version | Purpose |
|-----------|---------|---------|
| `asciidoctor` | ~> 2.0, >= 2.0.17 | AsciiDoc processing |
| `hugo` | 0.85.0 | Static site generation |

**Sources:** [Gemfile:1-5](), [Gemfile.lock:1-10](), [netlify.toml:11]()

### Build Process Flow

```mermaid
flowchart TD
    trigger["Build Trigger"]
    
    subgraph "Dependency Setup"
        bundle["bundle install"]
        ruby["Ruby 2.6.2"]
        asciidoctor["AsciiDoctor 2.0.17"]
    end
    
    subgraph "Content Processing"
        adoc_files["*.adoc files"]
        md_files["*.md files"]
        hugo_process["Hugo Processing"]
        asciidoc_render["AsciiDoc Rendering"]
    end
    
    subgraph "Asset Generation"
        css_compile["CSS Compilation"]
        js_bundle["JavaScript Bundling"]
        image_opt["Image Processing"]
    end
    
    subgraph "Output"
        public_dir["public/ directory"]
        html_files["Generated HTML"]
        static_assets["Static Assets"]
    end
    
    trigger --> bundle
    bundle --> ruby
    ruby --> asciidoctor
    
    asciidoctor --> adoc_files
    adoc_files --> asciidoc_render
    md_files --> hugo_process
    asciidoc_render --> hugo_process
    
    hugo_process --> css_compile
    hugo_process --> js_bundle
    hugo_process --> image_opt
    
    css_compile --> public_dir
    js_bundle --> public_dir
    image_opt --> public_dir
    hugo_process --> html_files
    html_files --> public_dir
    static_assets --> public_dir
```

**Sources:** [netlify.toml:3-7](), [Gemfile:1-5]()

### Build Script Configuration

The build process uses a custom `build.sh` script with different configurations for various deployment contexts:

```mermaid
graph TB
    build_script["build.sh"]
    
    subgraph "Production Context"
        prod_cmd["./build.sh"]
        prod_env["HUGO_ENV=production"]
        prod_baseurl["HUGO_BASEURL=docs.suitecrm.com"]
    end
    
    subgraph "Deploy Preview"
        preview_cmd["./build.sh -b $DEPLOY_PRIME_URL"]
        preview_env["HUGO_VERSION=0.85.0"]
    end
    
    subgraph "Branch Deploy"
        branch_cmd["./build.sh -b $DEPLOY_PRIME_URL"]
        branch_env["HUGO_VERSION=0.85.0"]
    end
    
    build_script --> prod_cmd
    build_script --> preview_cmd
    build_script --> branch_cmd
    
    prod_cmd --> prod_env
    prod_cmd --> prod_baseurl
    
    preview_cmd --> preview_env
    branch_cmd --> branch_env
```

**Sources:** [netlify.toml:3-28]()

## Content Processing Pipeline

The system processes multiple content formats and applies transformations during the build process.

### Content Source Processing

```mermaid
flowchart LR
    subgraph "Content Sources"
        adoc["AsciiDoc Files (.adoc)"]
        md["Markdown Files (.md)"]
        images["Static Images"]
        i18n["Translation Files (i18n/*.toml)"]
    end
    
    subgraph "Processing Layer"
        asciidoctor_proc["AsciiDoctor Processor"]
        hugo_md["Hugo Markdown Processor"]
        hugo_i18n["Hugo i18n Processor"]
        image_proc["Image Processor"]
    end
    
    subgraph "Output Generation"
        html_pages["HTML Pages"]
        css_assets["CSS Assets"]
        js_assets["JavaScript Assets"]
        optimized_images["Optimized Images"]
    end
    
    adoc --> asciidoctor_proc
    md --> hugo_md
    images --> image_proc
    i18n --> hugo_i18n
    
    asciidoctor_proc --> html_pages
    hugo_md --> html_pages
    hugo_i18n --> html_pages
    image_proc --> optimized_images
    
    html_pages --> css_assets
    html_pages --> js_assets
```

**Sources:** [config.toml:7](), [i18n/ru.toml:1-142]()

### Theme Integration

The system uses a customized version of `hugo-theme-learn` with SuiteCRM-specific styling:

| Configuration | Value | Purpose |
|---------------|-------|---------|
| `theme` | "hugo-theme-learn" | Base theme framework |
| `themeVariant` | "suitecrm" | Custom styling variant |
| `showVisitedLinks` | true | Track user navigation |

**Sources:** [config.toml:6](), [config.toml:22]()

## GitHub Integration Configuration

The system integrates with GitHub for content editing and issue reporting:

```mermaid
graph TB
    github_integration["GitHub Integration"]
    
    subgraph "Repository Configuration"
        repo_url["GitHubRepo: salesagility/SuiteDocs"]
        edit_url["editURL: /edit/master/content/"]
        issue_url["issueURL: /issues/new?"]
    end
    
    subgraph "Generated Links"
        edit_button["Edit Page Button"]
        issue_button["Report Issue Button"]
        git_info["Git Commit Information"]
    end
    
    github_integration --> repo_url
    github_integration --> edit_url
    github_integration --> issue_url
    
    repo_url --> edit_button
    edit_url --> edit_button
    issue_url --> issue_button
    git_info --> edit_button
```

**Sources:** [config.toml:14-16]()

## Menu System Configuration

Each language has its own menu configuration with shortcuts to external resources:

### Common Menu Items

All languages include shortcuts to:
- GitHub repository
- Blog section  
- Credits page

### Language-specific Menu Structure

```mermaid
graph TB
    menu_system["Menu System"]
    
    subgraph "English Menus"
        en_github["GitHub: salesagility/SuiteCRM"]
        en_blog["Blog"]
        en_credits["Credits"]
    end
    
    subgraph "Spanish Menus"
        es_github["GitHub: salesagility/SuiteCRM"]
        es_blog["Blog"]
        es_credits["Gracias"]
    end
    
    subgraph "Russian Menus"
        ru_menus["Russian menu configurations"]
    end
    
    menu_system --> en_github
    menu_system --> en_blog
    menu_system --> en_credits
    menu_system --> es_github
    menu_system --> es_blog
    menu_system --> es_credits
    menu_system --> ru_menus
```

**Sources:** [config.toml:42-95]()