# Theme Customization

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [content/blog/Customizing-Subthemes.adoc](content/blog/Customizing-Subthemes.adoc)
- [layouts/partials/custom-footer.html](layouts/partials/custom-footer.html)
- [layouts/partials/custom-header.html](layouts/partials/custom-header.html)
- [layouts/partials/menu-footer.html](layouts/partials/menu-footer.html)
- [layouts/partials/suitecrm.com-menu.html](layouts/partials/suitecrm.com-menu.html)
- [static/images/en/developer/Admin-OAuth2Clients-2.png](static/images/en/developer/Admin-OAuth2Clients-2.png)
- [static/images/en/developer/Admin-OAuth2Clients-3.png](static/images/en/developer/Admin-OAuth2Clients-3.png)
- [static/images/repo-forked.svg](static/images/repo-forked.svg)
- [static/images/star.svg](static/images/star.svg)

</details>



This document covers the customization of SuiteCRM themes, specifically focusing on the SuiteP theme system and its sub-themes. It provides guidance on creating custom sub-themes, modifying styles through SCSS, and organizing theme files in an upgrade-safe manner.

For information about frontend extensions and Angular development, see [Frontend Extensions](#6.2). For backend development including custom modules, see [Backend Development](#6.3).

## SuiteP Theme Architecture

SuiteCRM 7.10+ uses the SuiteP theme system with four built-in sub-themes: Dawn, Day, Dusk, and Night. The theme architecture follows a hierarchical structure where custom modifications are placed in the `custom/` directory to ensure upgrade safety.

```mermaid
graph TB
    SuiteP["SuiteP Base Theme"]
    
    subgraph "Built-in Sub-themes"
        Dawn["Dawn"]
        Day["Day"] 
        Dusk["Dusk"]
        Night["Night"]
    end
    
    subgraph "Core Theme Files"
        ThemeDef["themes/SuiteP/themedef.php"]
        Variables["themes/SuiteP/css/variables.scss"]
        ColorPalette["themes/SuiteP/css/color-palette.scss"]
        MainStyle["themes/SuiteP/css/style.scss"]
    end
    
    subgraph "Sub-theme Directories"
        DawnCSS["themes/SuiteP/css/Dawn/"]
        DayCSS["themes/SuiteP/css/Day/"]
        DuskCSS["themes/SuiteP/css/Dusk/"]
        NightCSS["themes/SuiteP/css/Night/"]
    end
    
    subgraph "Custom Override Structure"
        CustomThemeDef["custom/themes/SuiteP/themedef.php"]
        CustomLang["custom/modules/Users/language/en_us.lang.php"]
        CustomAppLang["custom/Extension/application/Ext/Language/en_us.*.php"]
    end
    
    SuiteP --> Dawn
    SuiteP --> Day
    SuiteP --> Dusk
    SuiteP --> Night
    
    SuiteP --> ThemeDef
    ThemeDef --> Variables
    ThemeDef --> ColorPalette
    ThemeDef --> MainStyle
    
    Dawn --> DawnCSS
    Day --> DayCSS
    Dusk --> DuskCSS
    Night --> NightCSS
    
    ThemeDef -.-> CustomThemeDef
    CustomThemeDef --> CustomLang
    CustomThemeDef --> CustomAppLang
```

**Theme Component Overview**

| Component | Purpose | File Location |
|-----------|---------|---------------|
| `themedef.php` | Theme definition and sub-theme registration | `themes/SuiteP/themedef.php` |
| `variables.scss` | SCSS variables and configuration | `themes/SuiteP/css/variables.scss` |
| `color-palette.scss` | Color definitions for themes | `themes/SuiteP/css/color-palette.scss` |
| `style.scss` | Main stylesheet compilation entry point | `themes/SuiteP/css/style.scss` |

Sources: [content/blog/Customizing-Subthemes.adoc:22-31](), [content/blog/Customizing-Subthemes.adoc:78-82]()

## Creating Custom Sub-themes

Custom sub-themes are created by copying existing sub-theme directories and registering them through the custom theme definition system. This process involves modifying theme definitions, language files, and CSS directories.

```mermaid
flowchart TD
    Start["Start Custom Sub-theme Creation"]
    
    subgraph "Theme Registration"
        CopyThemeDef["Copy themes/SuiteP/themedef.php to custom/themes/SuiteP/themedef.php"]
        AddSubtheme["Add new sub-theme entry to themedef.php"]
        RegisterLabel["Register LBL_SUBTHEME_OPTIONS_* label"]
    end
    
    subgraph "Language Configuration"
        ModLang["Create custom/modules/Users/language/en_us.lang.php"]
        AppLang["Create custom/Extension/application/Ext/Language/en_us.*.php"]
        DefineLabels["Define theme name labels"]
    end
    
    subgraph "CSS Directory Setup" 
        CopyCSS["Copy existing sub-theme CSS directory"]
        CreateNew["cp -R themes/SuiteP/css/Day themes/SuiteP/css/Noon"]
        QuickRepair["Run Quick Repair and Rebuild"]
    end
    
    subgraph "SCSS Compilation Setup"
        InstallSASS["composer require leafo/scssphp"]
        CompileCmd["./vendor/bin/pscss -f compressed"]
        StyleOutput["themes/SuiteP/css/Noon/style.css"]
    end
    
    Start --> CopyThemeDef
    CopyThemeDef --> AddSubtheme
    AddSubtheme --> RegisterLabel
    RegisterLabel --> ModLang
    ModLang --> AppLang
    AppLang --> DefineLabels
    DefineLabels --> CopyCSS
    CopyCSS --> CreateNew
    CreateNew --> QuickRepair
    QuickRepair --> InstallSASS
    InstallSASS --> CompileCmd
    CompileCmd --> StyleOutput
```

**Required File Modifications**

The custom sub-theme registration requires specific file modifications:

1. **Theme Definition**: Add sub-theme entry in `custom/themes/SuiteP/themedef.php`
2. **Module Language**: Define labels in `custom/modules/Users/language/en_us.lang.php`  
3. **Application Language**: Create extension file in `custom/Extension/application/Ext/Language/`

Sources: [content/blog/Customizing-Subthemes.adoc:22-42](), [content/blog/Customizing-Subthemes.adoc:53-59]()

## SCSS Compilation Process

The SuiteP theme system uses SCSS for stylesheet compilation. The compilation process transforms SCSS source files into optimized CSS output using the `leafo/scssphp` compiler.

```mermaid
graph LR
    subgraph "SCSS Source Files"
        MainSCSS["style.scss"]
        VarSCSS["variables.scss"] 
        PaletteSCSS["color-palette.scss"]
        ComponentSCSS["*.scss components"]
    end
    
    subgraph "Compilation Process"
        Composer["composer require leafo/scssphp"]
        PSCSSCmd["./vendor/bin/pscss"]
        CompressFlag["-f compressed"]
        InputFile["themes/SuiteP/css/Noon/style.scss"]
    end
    
    subgraph "Output"
        CompiledCSS["themes/SuiteP/css/Noon/style.css"]
        MinifiedCSS["Compressed CSS Output"]
    end
    
    VarSCSS --> MainSCSS
    PaletteSCSS --> MainSCSS  
    ComponentSCSS --> MainSCSS
    MainSCSS --> PSCSSCmd
    
    Composer --> PSCSSCmd
    PSCSSCmd --> CompressFlag
    CompressFlag --> InputFile
    InputFile --> CompiledCSS
    CompiledCSS --> MinifiedCSS
```

**SCSS Compilation Command Structure**

The compilation command follows this pattern:
```bash
./vendor/bin/pscss -f compressed themes/SuiteP/css/[SubTheme]/style.scss > themes/SuiteP/css/[SubTheme]/style.css
```

This command processes the main `style.scss` file which imports all other SCSS dependencies, regardless of which specific SCSS file was modified.

Sources: [content/blog/Customizing-Subthemes.adoc:61-74](), [content/blog/Customizing-Subthemes.adoc:77-82]()

## Style Customization Framework

SuiteCRM's theming system provides multiple levels of customization through SCSS variables and color palette modifications. The framework supports targeted changes to specific UI components while maintaining overall design consistency.

**Key SCSS Files for Customization**

| File | Purpose | Customization Level |
|------|---------|-------------------|
| `variables.scss` | Global SCSS variables and mixins | System-wide settings |
| `color-palette.scss` | Color definitions with variable mapping | Color scheme control |
| `style.scss` | Main import file and component organization | Structural modifications |

**Color Palette Variable System**

The color palette uses a numbered variable system (`$color-1` through `$color-82`) with semantic mappings. Each color variable serves specific UI components:

- `$color-7`: List view elements with foreground color `$fg-color-7`
- `$color-12`: Sidebar elements  
- `$color-44`, `$color-50`, `$color-80`, `$color-82`: White variants for backgrounds
- `$color-49`: Red for error states

```mermaid
graph TB
    subgraph "SCSS Variable Structure"
        GlobalVars["variables.scss - Global Variables"]
        ColorPalette["color-palette.scss - Color Definitions"]
        ComponentStyles["Component-specific SCSS files"]
    end
    
    subgraph "Color Variable Mapping"
        Color7["$color-7: #4B97C4 - List Views"]
        FGColor7["$fg-color-7: #111 - List View Text"]
        Color12["$color-12: #666666 - General UI"]
        Color13["$color-13: #303D44 - Sidebar"]
        Color49["$color-49: #FF0000 - Error States"]
    end
    
    subgraph "UI Component Application"
        TopMenus["Top Navigation Menus"]
        Sidebars["Sidebar Navigation"]
        ListViews["Data List Views"]
        ErrorStates["Error Messages"]
    end
    
    GlobalVars --> ColorPalette
    ColorPalette --> ComponentStyles
    
    Color7 --> ListViews
    FGColor7 --> ListViews
    Color12 --> TopMenus
    Color13 --> Sidebars
    Color49 --> ErrorStates
```

**Example Color Customization Workflow**

For increased contrast modifications, specific color values can be updated in `color-palette.scss`:

- Top menus: `#C2C3C4` → `#555555` (darker background)
- Text lettering: `#817C8D` → `#111` (darker text)  
- Sidebar elements: `#929798` → `#626768`, `#707d84` → `#303d44` (increased contrast)

Sources: [content/blog/Customizing-Subthemes.adoc:83-97](), [content/blog/Customizing-Subthemes.adoc:99-188]()

## File Organization and Upgrade Safety

SuiteCRM's customization system follows the custom directory pattern to ensure modifications survive system upgrades. All theme customizations should be placed within the `custom/` directory structure to maintain upgrade compatibility.

```mermaid
graph TD
    subgraph "Core Theme Structure"
        CoreThemes["themes/SuiteP/"]
        CoreThemeDef["themes/SuiteP/themedef.php"]
        CoreCSS["themes/SuiteP/css/"]
        CoreSubthemes["themes/SuiteP/css/[Dawn|Day|Dusk|Night]/"]
    end
    
    subgraph "Custom Override Structure"
        CustomRoot["custom/"]
        CustomThemes["custom/themes/SuiteP/"]
        CustomThemeDef["custom/themes/SuiteP/themedef.php"]
        CustomModules["custom/modules/Users/language/"]
        CustomExtensions["custom/Extension/application/Ext/Language/"]
    end
    
    subgraph "SASS Compilation Tools"
        Vendor["vendor/bin/pscss"]
        ComposerDeps["composer.json - leafo/scssphp"]
        CompileScript["Compilation command"]
    end
    
    subgraph "User Profile Integration"
        UserProfile["User Profile - Layout Options"]
        ThemeSelector["Sub-theme Selection Interface"]
        QuickRepair["Admin - Quick Repair and Rebuild"]
    end
    
    CoreThemes -.->|"Override via"| CustomThemes
    CoreThemeDef -.->|"Customized in"| CustomThemeDef
    
    CustomThemeDef --> CustomModules
    CustomThemeDef --> CustomExtensions
    
    ComposerDeps --> Vendor
    Vendor --> CompileScript
    CompileScript --> CoreCSS
    
    CustomThemeDef --> QuickRepair
    QuickRepair --> UserProfile
    UserProfile --> ThemeSelector
```

**Upgrade-Safe Customization Checklist**

1. **Theme Definition**: Use `custom/themes/SuiteP/themedef.php` instead of modifying core files
2. **Language Files**: Place labels in `custom/modules/Users/language/` and `custom/Extension/application/Ext/Language/`
3. **CSS Compilation**: Compile directly to core CSS directories (acceptable as they regenerate)
4. **Version Control**: Track custom SCSS source files, not compiled CSS output
5. **Documentation**: Maintain compilation commands and color change documentation

**Required System Operations**

After theme modifications, specific system operations ensure proper integration:

- **Quick Repair and Rebuild**: Required after theme definition changes
- **Cache Clear**: May be needed for language file updates
- **User Profile Update**: Users can select new themes from Layout Options

Sources: [content/blog/Customizing-Subthemes.adoc:22-59](), [content/blog/Customizing-Subthemes.adoc:190-191]()