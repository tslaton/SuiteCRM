# Theme Management

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [include/javascript/tiny_mce/plugins/style/readme.txt](include/javascript/tiny_mce/plugins/style/readme.txt)
- [modules/Administration/action_view_map.php](modules/Administration/action_view_map.php)
- [modules/Administration/templates/themeConfigSettings.tpl](modules/Administration/templates/themeConfigSettings.tpl)
- [modules/Administration/templates/themeSettings.tpl](modules/Administration/templates/themeSettings.tpl)
- [modules/Administration/views/view.themeconfigsettings.php](modules/Administration/views/view.themeconfigsettings.php)
- [themes/SuiteP/include/DetailView/DetailView.tpl](themes/SuiteP/include/DetailView/DetailView.tpl)
- [themes/SuiteP/include/DetailView/footer.tpl](themes/SuiteP/include/DetailView/footer.tpl)
- [themes/SuiteP/include/DetailView/header.tpl](themes/SuiteP/include/DetailView/header.tpl)
- [themes/SuiteP/include/DetailView/tab_panel_content.tpl](themes/SuiteP/include/DetailView/tab_panel_content.tpl)
- [themes/SuiteP/include/DetailView/test.tpl](themes/SuiteP/include/DetailView/test.tpl)
- [themes/SuiteP/include/EditView/QuickCreate.tpl](themes/SuiteP/include/EditView/QuickCreate.tpl)
- [themes/SuiteP/js/style.js](themes/SuiteP/js/style.js)
- [themes/SuiteP/modules/Studio/TabGroups/EditViewTabs.tpl](themes/SuiteP/modules/Studio/TabGroups/EditViewTabs.tpl)
- [themes/SuiteP/tpls/_headerModuleList.tpl](themes/SuiteP/tpls/_headerModuleList.tpl)
- [themes/SuiteP/tpls/footer.tpl](themes/SuiteP/tpls/footer.tpl)

</details>



This document covers SuiteCRM's theme management system, which provides the presentation layer architecture for customizing the user interface. The theme system manages visual styling, layout templates, and responsive behavior across different devices and screen sizes.

For information about the underlying MVC framework that renders these themes, see [MVC Framework](#2.1). For configuration management that themes integrate with, see [Configuration System](#2.3).

## Overview

SuiteCRM's theme management system consists of theme directories containing Smarty templates, CSS stylesheets, JavaScript files, and configuration definitions. The system supports multiple themes with the primary themes being SuiteP (modern responsive theme) and SuiteR (legacy theme). Administrators can configure theme settings, enable/disable themes, and customize theme-specific parameters through the Administration panel.

## Theme Architecture

The theme system follows a structured architecture where themes are self-contained packages with their own templates, assets, and configuration.

```mermaid
graph TD
    ThemeRegistry["SugarThemeRegistry"] --> SuiteP["SuiteP Theme"]
    ThemeRegistry --> SuiteR["SuiteR Theme"]
    
    SuiteP --> SuitePTemplates["Templates (.tpl)"]
    SuiteP --> SuitePCSS["Stylesheets (.css)"]
    SuiteP --> SuitePJS["JavaScript (.js)"]
    SuiteP --> SuitePConfig["theme.php Config"]
    
    SuitePTemplates --> HeaderTpl["_headerModuleList.tpl"]
    SuitePTemplates --> DetailViewTpl["DetailView.tpl"]
    SuitePTemplates --> FooterTpl["footer.tpl"]
    
    SuitePJS --> StyleJS["style.js"]
    
    AdminViews["Administration Views"] --> ThemeSettings["ThemeSettings"]
    AdminViews --> ThemeConfigSettings["ThemeConfigSettings"]
    
    ThemeSettings --> ThemeRegistry
    ThemeConfigSettings --> SuitePConfig
```

Sources: [themes/SuiteP/tpls/_headerModuleList.tpl:1-600](), [themes/SuiteP/js/style.js:1-537](), [modules/Administration/templates/themeSettings.tpl:1-116](), [modules/Administration/views/view.themeconfigsettings.php:1-120]()

## Template System Integration

The theme system integrates with SuiteCRM's Smarty templating engine to render views. Each theme provides specific templates for different view types.

```mermaid
graph LR
    SugarView["SugarView"] --> TemplateAssign["Template Assignment"]
    TemplateAssign --> SmartyEngine["Smarty Engine"]
    
    SmartyEngine --> HeaderTemplate["Header Templates"]
    SmartyEngine --> DetailTemplate["Detail View Templates"]
    SmartyEngine --> FooterTemplate["Footer Templates"]
    
    HeaderTemplate --> HeaderModuleList["_headerModuleList.tpl"]
    DetailTemplate --> DetailViewMain["DetailView.tpl"]
    DetailTemplate --> TabPanelContent["tab_panel_content.tpl"]
    FooterTemplate --> FooterMain["footer.tpl"]
    
    Variables["Template Variables"] --> SmartyEngine
    Variables --> ModuleData["$module, $fields"]
    Variables --> UIData["$config, $APP"]
    Variables --> NavigationData["$moduleTopMenu, $groupTabs"]
```

Sources: [themes/SuiteP/include/DetailView/DetailView.tpl:1-365](), [themes/SuiteP/include/DetailView/tab_panel_content.tpl:1-213](), [themes/SuiteP/include/DetailView/header.tpl:1-104]()

## SuiteP Theme Components

The SuiteP theme is the primary modern theme with responsive design capabilities and Bootstrap integration.

### Navigation System

The header navigation system provides the main user interface for module access and quick actions.

```mermaid
graph TD
    NavBar["navbar navbar-inverse"] --> MobileToggle["Mobile Toggle Button"]
    NavBar --> DesktopToolbar["Desktop Toolbar"]
    NavBar --> MobileBar["Mobile Bar"]
    
    DesktopToolbar --> ModuleNavigation["Module Navigation"]
    DesktopToolbar --> GroupTabs["Group Tabs"]
    
    ModuleNavigation --> CurrentTab["Current Module Tab"]
    ModuleNavigation --> RecentRecords["Recent Records Dropdown"]
    ModuleNavigation --> FavoriteRecords["Favorite Records Dropdown"]
    
    MobileBar --> QuickCreate["Quick Create Dropdown"]
    MobileBar --> SearchButton["Search Button"]
    MobileBar --> UserActions["User Actions"]
    
    GroupTabs --> AllModules["All Modules Group"]
    GroupTabs --> CustomGroups["Custom Module Groups"]
```

Sources: [themes/SuiteP/tpls/_headerModuleList.tpl:42-489]()

### JavaScript Framework

The SuiteP theme includes comprehensive JavaScript functionality for responsive behavior and user interactions.

```mermaid
graph TD
    StyleJS["style.js"] --> SugarMeasurements["SUGAR.measurements"]
    StyleJS --> SugarThemes["SUGAR.themes"]
    StyleJS --> ResponsiveBehavior["Responsive Behavior"]
    
    SugarMeasurements --> Breakpoints["Breakpoint Definitions"]
    Breakpoints --> XSmall["x-small: 750px"]
    Breakpoints --> Small["small: 768px"]
    Breakpoints --> Medium["medium: 992px"]
    Breakpoints --> Large["large: 1130px"]
    
    SugarThemes --> ModuleListLoader["loadModuleList()"]
    SugarThemes --> ActionMenuHandler["actionMenu()"]
    SugarThemes --> TabSetter["setModuleTabs()"]
    
    ResponsiveBehavior --> SidebarToggle["loadSidebar()"]
    ResponsiveBehavior --> WindowResize["Window Resize Handlers"]
    ResponsiveBehavior --> CheckboxInit["Bootstrap Checkbox Init"]
```

Sources: [themes/SuiteP/js/style.js:40-537]()

## Administration Interface

The theme management system provides administrative interfaces for configuring themes and their settings.

### Theme Settings View

```mermaid
graph TD
    AdminPanel["Administration Panel"] --> ThemeSettingsAction["ThemeSettings Action"]
    ThemeSettingsAction --> ThemeSettingsView["ThemeSettings View"]
    
    ThemeSettingsView --> AvailableThemes["Available Themes List"]
    ThemeSettingsView --> ThemePreview["Theme Preview Images"]
    ThemeSettingsView --> ThemeStatus["Enable/Disable Controls"]
    ThemeSettingsView --> DefaultTheme["Default Theme Selection"]
    
    AvailableThemes --> SuitePEntry["SuiteP Theme Entry"]
    AvailableThemes --> SuiteREntry["SuiteR Theme Entry"]
    
    ThemeConfigButton["Theme Config Button"] --> ThemeConfigSettings["ThemeConfigSettings Action"]
    ThemeConfigSettings --> ConfigurableParams["Configurable Parameters"]
    ConfigurableParams --> ColorParams["Color Parameters"]
    ConfigurableParams --> BooleanParams["Boolean Parameters"]
```

Sources: [modules/Administration/templates/themeSettings.tpl:43-116](), [modules/Administration/views/view.themeconfigsettings.php:49-119]()

### Configuration Management

The theme configuration system allows administrators to customize theme-specific parameters.

```mermaid
graph LR
    ConfigForm["themeConfigSettings Form"] --> ConfigSubmit["Form Submission"]
    ConfigSubmit --> ConfiguratorClass["Configurator Class"]
    
    ConfiguratorClass --> ThemeConfig["theme_settings Array"]
    ThemeConfig --> ThemeSpecificConfig["Theme-Specific Config"]
    
    ThemeSpecificConfig --> ColorSettings["Color Settings"]
    ThemeSpecificConfig --> BooleanSettings["Boolean Settings"]
    
    ConfigSave["Config Save"] --> ConfigOverride["handleOverride()"]
    ConfigOverride --> RedirectToSettings["Redirect to ThemeSettings"]
    
    SugarThemeRegistry["SugarThemeRegistry"] --> GetThemeConfig["getThemeConfig()"]
    GetThemeConfig --> ThemeConfigArray["Theme Config Definition"]
```

Sources: [modules/Administration/templates/themeConfigSettings.tpl:43-84](), [modules/Administration/views/view.themeconfigsettings.php:79-101]()

## Detail View System

The detail view template system provides structured layouts for displaying record information with tabs and panels.

### Tab and Panel Structure

```mermaid
graph TD
    DetailViewTpl["DetailView.tpl"] --> TabSystem["Tab System Check"]
    TabSystem --> UseTabs{"useTabs?"}
    
    UseTabs -->|Yes| TabContent["Tab Content Structure"]
    UseTabs -->|No| PanelContent["Panel Content Structure"]
    
    TabContent --> TabHeaders["Tab Headers (nav-tabs)"]
    TabContent --> TabPanes["Tab Panes (tab-content)"]
    
    TabHeaders --> TabCounter["Tab Counter Logic"]
    TabPanes --> TabPanelContent["tab_panel_content.tpl"]
    
    PanelContent --> PanelContainer["Panel Container"]
    PanelContent --> CollapsiblePanels["Collapsible Panels"]
    
    ActionMenu["Action Menu"] --> ActionMenuCheck{"enable_action_menu?"}
    ActionMenuCheck -->|Yes| ActionDropdown["Action Dropdown"]
    ActionMenuCheck -->|No| ActionButtons["Action Buttons"]
    
    TabPanelContent --> RowIteration["Row Iteration"]
    RowIteration --> ColumnIteration["Column Iteration"]
    ColumnIteration --> FieldIteration["Field Iteration"]
```

Sources: [themes/SuiteP/include/DetailView/DetailView.tpl:44-365](), [themes/SuiteP/include/DetailView/tab_panel_content.tpl:45-213]()

## Studio Integration

The theme system integrates with Studio for customizing tab groups and module organization.

```mermaid
graph TD
    StudioTabGroups["Studio Tab Groups"] --> EditViewTabs["EditViewTabs.tpl"]
    EditViewTabs --> DragDropInit["dragDropInit()"]
    
    DragDropInit --> YahooSlots["yahooSlots Array"]
    DragDropInit --> ModuleSlots["Module Slots"]
    DragDropInit --> TabSlots["Tab Slots"]
    
    YahooSlots --> ygDDSlot["ygDDSlot Instances"]
    YahooSlots --> ygDDListStudio["ygDDListStudio Instances"]
    
    TabGroupLanguage["Tab Group Language"] --> LanguageSelector["Language Selector"]
    LanguageSelector --> tabLanguageChange["tabLanguageChange()"]
    
    AddTabGroup["Add Tab Group"] --> addTabGroup["addTabGroup()"]
    addTabGroup --> NewTabCreation["Dynamic Tab Creation"]
    
    SavePublish["Save & Publish"] --> GenerateForm["studiotabs.generateForm()"]
    GenerateForm --> FormSubmission["Form Submission to SaveTabs"]
```

Sources: [themes/SuiteP/modules/Studio/TabGroups/EditViewTabs.tpl:47-300]()

## File Organization

The theme management system follows a structured file organization pattern for maintainability and extensibility.

| Component | Location | Purpose |
|-----------|----------|---------|
| Theme Templates | `themes/{theme}/tpls/` | Main template files |
| Detail View Templates | `themes/{theme}/include/DetailView/` | Record detail templates |
| Edit View Templates | `themes/{theme}/include/EditView/` | Record edit templates |
| JavaScript | `themes/{theme}/js/` | Theme-specific JavaScript |
| CSS | `themes/{theme}/css/` | Theme stylesheets |
| Images | `themes/{theme}/images/` | Theme image assets |
| Module Templates | `themes/{theme}/modules/{module}/` | Module-specific overrides |
| Admin Templates | `modules/Administration/templates/` | Theme administration |
| Admin Views | `modules/Administration/views/` | Theme management views |

Sources: [themes/SuiteP/tpls/_headerModuleList.tpl:1](), [themes/SuiteP/js/style.js:1](), [modules/Administration/templates/themeSettings.tpl:1](), [modules/Administration/action_view_map.php:42-43]()

## Responsive Design Implementation

The SuiteP theme implements responsive design through JavaScript breakpoint management and CSS media queries.

```mermaid
graph LR
    WindowResize["Window Resize Event"] --> ResponsiveLogic["Responsive Logic"]
    
    ResponsiveLogic --> SidebarToggle["Sidebar Toggle Logic"]
    ResponsiveLogic --> NavigationCollapse["Navigation Collapse"]
    ResponsiveLogic --> FormOptimization["Form Optimization"]
    
    SidebarToggle --> CookieStorage["Cookie Storage"]
    SidebarToggle --> BootstrapClasses["Bootstrap Class Management"]
    
    NavigationCollapse --> OverflowMenu["Overflow Menu"]
    NavigationCollapse --> MobileMenu["Mobile Menu"]
    
    FormOptimization --> EmptyFieldHiding["Empty Field Hiding"]
    FormOptimization --> TabletOptimization["Tablet Optimization"]
    
    Breakpoints["Breakpoint System"] --> WindowResize
    Breakpoints --> MediaQueries["CSS Media Queries"]
```

Sources: [themes/SuiteP/js/style.js:292-537]()