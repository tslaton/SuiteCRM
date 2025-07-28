# Netlify Deployment

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [LICENSE.md](LICENSE.md)
- [README.md](README.md)
- [archetypes/blog.md](archetypes/blog.md)
- [archetypes/default.md](archetypes/default.md)
- [config.toml](config.toml)
- [content/community/contributing-code/Forking.adoc](content/community/contributing-code/Forking.adoc)
- [i18n/ru.toml](i18n/ru.toml)
- [netlify.toml](netlify.toml)

</details>



This document describes the Netlify deployment configuration and process for the SuiteDocs documentation system. It covers the automated deployment pipeline, build contexts, preview deployments, and integration with the Hugo static site generator.

The deployment system handles both production releases and preview deployments for pull requests, supporting multiple languages and automated builds triggered by GitHub commits. For information about the Hugo build process itself, see [Hugo Configuration and Build Process](#2.1). For details about multi-language content structure, see [Multi-language Support](#2.2).

## Deployment Architecture Overview

The SuiteDocs deployment system uses Netlify's continuous deployment platform to automatically build and deploy the Hugo-generated documentation site from the GitHub repository.

**Netlify Deployment Pipeline**
```mermaid
flowchart TD
    subgraph "GitHub Repository"
        master["master branch"]
        pr["Pull Request branches"]
        content["Content (.adoc, .md files)"]
        config["config.toml"]
        netlify_config["netlify.toml"]
        build_script["build.sh"]
    end
    
    subgraph "Netlify Build Process"
        trigger["Git Push Trigger"]
        build_env["Build Environment<br/>Hugo 0.85.0<br/>Ruby 2.6.2"]
        build_cmd["chmod +x build.sh && ./build.sh"]
        hugo_build["Hugo Static Generation"]
        asciidoc["AsciiDoctor Processing"]
        public_dir["public/ directory"]
    end
    
    subgraph "Deployment Contexts"
        production["Production Context<br/>docs.suitecrm.com"]
        preview["Deploy Preview Context<br/>deploy-preview-XX.netlify.app"]
        branch["Branch Deploy Context<br/>branch-name.netlify.app"]
    end
    
    subgraph "Multi-language Sites"
        en_site["English Site<br/>/"]
        es_site["Spanish Site<br/>/es/"]
        ru_site["Russian Site<br/>/ru/"]
    end
    
    master --> trigger
    pr --> trigger
    trigger --> build_env
    build_env --> build_cmd
    build_script --> build_cmd
    config --> hugo_build
    netlify_config --> build_env
    content --> asciidoc
    build_cmd --> hugo_build
    hugo_build --> asciidoc
    asciidoc --> public_dir
    
    public_dir --> production
    public_dir --> preview
    public_dir --> branch
    
    production --> en_site
    production --> es_site
    production --> ru_site
    
    style build_env fill:#f9f9f9
    style public_dir fill:#e8f5e8
    style production fill:#e8f0ff
```

Sources: [README.md:13-17](), [netlify.toml:1-32](), [config.toml:1-43]()

## Netlify Configuration Structure

The deployment configuration is defined in `netlify.toml` which specifies build settings, deployment contexts, and environment variables for different deployment scenarios.

| Configuration Section | Purpose | Key Settings |
|----------------------|---------|--------------|
| `[build]` | Base build configuration | `publish = "public"`, build command |
| `[context.production.environment]` | Production environment variables | `HUGO_VERSION`, `HUGO_ENV`, `HUGO_BASEURL` |
| `[context.deploy-preview]` | Pull request preview settings | Custom build command with `$DEPLOY_PRIME_URL` |
| `[context.branch-deploy]` | Non-master branch deployments | Environment-specific base URL handling |

**Build Configuration Details**
```mermaid
graph TB
    subgraph "netlify.toml Structure"
        build_section["[build]<br/>publish = 'public'<br/>command = 'chmod +x build.sh && ./build.sh'<br/>HUGO_ENABLEGITINFO = 'true'"]
        
        prod_context["[context.production.environment]<br/>HUGO_VERSION = '0.85.0'<br/>HUGO_ENV = 'production'<br/>HUGO_BASEURL = 'https://docs.suitecrm.com'"]
        
        preview_context["[context.deploy-preview]<br/>command = 'chmod +x build.sh && ./build.sh -b $DEPLOY_PRIME_URL'"]
        
        branch_context["[context.branch-deploy]<br/>command = 'chmod +x build.sh && ./build.sh -b $DEPLOY_PRIME_URL'"]
        
        preview_env["[context.deploy-preview.environment]<br/>HUGO_VERSION = '0.85.0'"]
        
        branch_env["[context.branch-deploy.environment]<br/>HUGO_VERSION = '0.85.0'"]
    end
    
    subgraph "Environment Variables"
        hugo_version["HUGO_VERSION = '0.85.0'"]
        hugo_env["HUGO_ENV = 'production'"]
        base_url["HUGO_BASEURL"]
        deploy_url["DEPLOY_PRIME_URL (Netlify-provided)"]
        git_info["HUGO_ENABLEGITINFO = 'true'"]
    end
    
    build_section --> hugo_version
    build_section --> git_info
    prod_context --> hugo_env
    prod_context --> base_url
    preview_context --> deploy_url
    branch_context --> deploy_url
    
    style build_section fill:#f0f8ff
    style prod_context fill:#f0fff0
    style preview_context fill:#fff8f0
```

Sources: [netlify.toml:3-32]()

## Build Process and Commands

The build process executes a shell script that handles Hugo compilation with AsciiDoc processing. The build command varies by deployment context to handle different base URL configurations.

### Build Command Execution Flow

**Production Build Command:**
```bash
chmod +x build.sh && ./build.sh
```

**Preview/Branch Build Command:**
```bash
chmod +x build.sh && ./build.sh -b $DEPLOY_PRIME_URL
```

The `build.sh` script (referenced but not provided in the file listing) processes the Hugo site generation with AsciiDoctor for `.adoc` file conversion. The `-b` flag allows dynamic base URL setting for preview deployments.

**Build Environment Configuration**
```mermaid
graph LR
    subgraph "Build Environment Setup"
        ruby["Ruby 2.6.2<br/>(Environment Variable)"]
        hugo["Hugo 0.85.0<br/>(netlify.toml)"]
        asciidoctor["AsciiDoctor<br/>(Auto-installed by Netlify)"]
        git["Git Info Enabled<br/>HUGO_ENABLEGITINFO = true"]
    end
    
    subgraph "Build Script Execution"
        chmod["chmod +x build.sh"]
        execute["./build.sh execution"]
        baseurl_param["-b $DEPLOY_PRIME_URL<br/>(preview/branch only)"]
    end
    
    subgraph "Hugo Processing"
        content_processing["Content Processing<br/>AsciiDoc → HTML"]
        theme_processing["Theme Processing<br/>hugo-theme-learn"]
        multilang["Multi-language Site Generation<br/>en, es, ru"]
        output["public/ directory output"]
    end
    
    ruby --> execute
    hugo --> execute
    asciidoctor --> content_processing
    git --> content_processing
    chmod --> execute
    execute --> content_processing
    baseurl_param --> execute
    content_processing --> theme_processing
    theme_processing --> multilang
    multilang --> output
    
    style execute fill:#fff2e6
    style output fill:#e6ffe6
```

Sources: [netlify.toml:5-6](), [netlify.toml:17](), [netlify.toml:23](), [README.md:1-2]()

## Deployment Contexts and Environments

Netlify provides three distinct deployment contexts, each with specific configuration for different use cases in the documentation workflow.

### Production Context

The production context deploys from the `master` branch to the primary domain `docs.suitecrm.com`. This context includes full environment configuration for the live documentation site.

**Production Environment Variables:**
- `HUGO_VERSION = "0.85.0"`
- `HUGO_ENV = "production"`
- `HUGO_BASEURL = "https://docs.suitecrm.com"`

### Deploy Preview Context

Deploy previews are automatically generated for pull requests, providing a staging environment to review documentation changes before merging to master.

**Preview URL Pattern:** `deploy-preview-{PR_NUMBER}--suitedocs.netlify.app`

The preview context uses the `$DEPLOY_PRIME_URL` environment variable to ensure all internal links work correctly in the preview environment.

### Branch Deploy Context

Branch deployments create preview sites for any non-master branch pushed to GitHub, useful for long-running feature branches or collaborative documentation work.

**Branch URL Pattern:** `{BRANCH_NAME}--suitedocs.netlify.app`

**Deployment Context Flow**
```mermaid
flowchart TD
    subgraph "Git Events"
        master_push["Push to master branch"]
        pr_create["Pull Request created/updated"]
        branch_push["Push to feature branch"]
    end
    
    subgraph "Netlify Context Selection"
        production_check{"Is master branch?"}
        pr_check{"Is Pull Request?"}
        context_production["Production Context<br/>[context.production.environment]"]
        context_preview["Deploy Preview Context<br/>[context.deploy-preview]"]
        context_branch["Branch Deploy Context<br/>[context.branch-deploy]"]
    end
    
    subgraph "Build Execution"
        prod_build["./build.sh<br/>HUGO_BASEURL = docs.suitecrm.com"]
        preview_build["./build.sh -b $DEPLOY_PRIME_URL<br/>Preview URL as base"]
        branch_build["./build.sh -b $DEPLOY_PRIME_URL<br/>Branch URL as base"]
    end
    
    subgraph "Deployment Targets"
        prod_site["docs.suitecrm.com"]
        preview_site["deploy-preview-XX.netlify.app"]
        branch_site["branch-name.netlify.app"]
    end
    
    master_push --> production_check
    pr_create --> pr_check
    branch_push --> production_check
    
    production_check -->|Yes| context_production
    production_check -->|No| pr_check
    pr_check -->|Yes| context_preview
    pr_check -->|No| context_branch
    
    context_production --> prod_build
    context_preview --> preview_build
    context_branch --> branch_build
    
    prod_build --> prod_site
    preview_build --> preview_site
    branch_build --> branch_site
    
    style context_production fill:#e6f3ff
    style context_preview fill:#fff3e6
    style context_branch fill:#f0f8f0
```

Sources: [netlify.toml:9-14](), [netlify.toml:15-21](), [netlify.toml:22-28]()

## Integration with Hugo Configuration

The Netlify deployment system integrates closely with the Hugo site configuration defined in `config.toml`, particularly for multi-language support and theme processing.

### Hugo-Netlify Integration Points

| Hugo Configuration | Netlify Usage | Purpose |
|-------------------|---------------|---------|
| `baseURL = "https://docs.suitecrm.com"` | Overridden by `HUGO_BASEURL` | Production URL configuration |
| `theme = "hugo-theme-learn"` | Used in build process | Theme selection for Hugo |
| `timeout = 47000` | Build timeout setting | Long build time accommodation |
| `enableGitInfo = true` | Set via `HUGO_ENABLEGITINFO` | Git metadata in pages |
| Multi-language `[Languages]` | Deployed as subdirectories | `/`, `/es/`, `/ru/` paths |

**Hugo Configuration Integration**
```mermaid
graph TB
    subgraph "config.toml Settings"
        base_url["baseURL = 'https://docs.suitecrm.com'"]
        theme_config["theme = 'hugo-theme-learn'"]
        timeout["timeout = 47000"]
        git_info["enableGitInfo = true"]
        languages["[Languages.en/es/ru]"]
    end
    
    subgraph "Netlify Environment Variables"
        netlify_baseurl["HUGO_BASEURL<br/>(context-dependent)"]
        netlify_gitinfo["HUGO_ENABLEGITINFO = 'true'"]
        hugo_version["HUGO_VERSION = '0.85.0'"]
        hugo_env["HUGO_ENV = 'production'"]
    end
    
    subgraph "Hugo Build Process"
        url_resolution["URL Resolution"]
        theme_processing["Theme Processing"]
        content_build["Content Building"]
        multilang_build["Multi-language Site Generation"]
    end
    
    subgraph "Output Structure"
        public_root["public/"]
        en_output["public/ (English)"]
        es_output["public/es/ (Spanish)"]
        ru_output["public/ru/ (Russian)"]
    end
    
    base_url --> netlify_baseurl
    git_info --> netlify_gitinfo
    netlify_baseurl --> url_resolution
    theme_config --> theme_processing
    timeout --> content_build
    netlify_gitinfo --> content_build
    languages --> multilang_build
    
    url_resolution --> public_root
    theme_processing --> public_root
    content_build --> public_root
    multilang_build --> en_output
    multilang_build --> es_output
    multilang_build --> ru_output
    
    style netlify_baseurl fill:#ffe6e6
    style public_root fill:#e6ffe6
```

Sources: [config.toml:1](), [config.toml:6](), [config.toml:10](), [config.toml:9](), [config.toml:28-118](), [netlify.toml:6](), [netlify.toml:13]()

## Status Monitoring and GitHub Integration

The deployment system includes status monitoring through GitHub badges and direct integration with the GitHub repository for automated builds and content editing workflows.

### GitHub Integration Features

The `config.toml` file defines several GitHub integration points that work with the Netlify deployment:

- **Edit Links:** `editURL = "https://github.com/salesagility/SuiteDocs/edit/master/content/"`
- **Issue Creation:** `issueURL = "https://github.com/salesagility/SuiteDocs/issues/new?"`
- **Repository Reference:** `GitHubRepo = "https://github.com/salesagility/SuiteDocs/"`

### Deployment Status Badge

The README displays a Netlify status badge that shows real-time deployment status:
```
[![Netlify Status](https://api.netlify.com/api/v1/badges/2cb9437a-4da2-47de-9dc7-11477d85c3ae/deploy-status)](https://app.netlify.com/sites/suitedocs/deploys)
```

This badge provides immediate visibility into the deployment state and links to the Netlify deployment dashboard for detailed build information.

Sources: [README.md:1-2](), [config.toml:14-16]()