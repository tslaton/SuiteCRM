# User Interface System

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



The User Interface System encompasses SuiteCRM's presentation layer, providing the visual and interactive components that users interact with. This system includes the SuiteP theme framework, navigation components, view templates, JavaScript interactions, and responsive design patterns. The system manages the rendering of all user-facing pages including detail views, list views, edit forms, and navigation elements.

For information about specific view types like inline editing, see [Inline Editing](#3.3). For theme management and configuration, see [Theme Management](#3.1). For JavaScript functionality, see [JavaScript Framework](#3.2).

## Theme Architecture

The UI system is built around the SuiteP theme, which serves as the primary presentation framework. The theme system uses Smarty templating for server-side rendering and Bootstrap-based CSS for styling.

```mermaid
graph TB
    subgraph "Theme System"
        SuiteP["SuiteP Theme"]
        Config["Theme Configuration"]
        Templates["Smarty Templates"]
        Assets["CSS/JS Assets"]
    end
    
    subgraph "Template Types"
        Header["Header Templates"]
        DetailView["DetailView Templates"]
        ListView["ListView Templates"]
        EditView["EditView Templates"]
        Footer["Footer Templates"]
    end
    
    subgraph "JavaScript Components"
        StyleJS["style.js"]
        Responsive["Responsive Logic"]
        Sidebar["Sidebar Management"]
        Navigation["Navigation Controls"]
    end
    
    SuiteP --> Templates
    SuiteP --> Assets
    Config --> SuiteP
    
    Templates --> Header
    Templates --> DetailView
    Templates --> ListView
    Templates --> EditView
    Templates --> Footer
    
    Assets --> StyleJS
    StyleJS --> Responsive
    StyleJS --> Sidebar
    StyleJS --> Navigation
```

**Sources:** [themes/SuiteP/tpls/_headerModuleList.tpl:1-570](), [themes/SuiteP/js/style.js:1-537](), [modules/Administration/templates/themeSettings.tpl:1-115]()

## Navigation System

The navigation system provides hierarchical module access through a responsive header with dropdown menus, search functionality, and quick-create options.

```mermaid
graph TB
    subgraph "Header Navigation"
        Navbar["navbar-inverse navbar-fixed-top"]
        MobileToggle["Mobile Menu Toggle"]
        DesktopToolbar["Desktop Toolbar"]
    end
    
    subgraph "Module Navigation"
        GroupTabs["groupTabs"]
        ModuleList["moduleTopMenu"]
        RecentRecords["recentRecords"]
        Favorites["favoriteRecords"]
    end
    
    subgraph "Right Toolbar"
        QuickCreate["quickcreatetop"]
        SearchButton["searchbutton"]
        GlobalSearch["UnifiedSearch"]
    end
    
    subgraph "Responsive Behavior"
        WindowResize["windowResize()"]
        NavCollapse["Navigation Collapse"]
        OverflowMenu["overflow-menu"]
    end
    
    Navbar --> MobileToggle
    Navbar --> DesktopToolbar
    
    DesktopToolbar --> GroupTabs
    DesktopToolbar --> ModuleList
    
    ModuleList --> RecentRecords
    ModuleList --> Favorites
    
    DesktopToolbar --> QuickCreate
    DesktopToolbar --> SearchButton
    SearchButton --> GlobalSearch
    
    WindowResize --> NavCollapse
    NavCollapse --> OverflowMenu
```

The header navigation is implemented in `_headerModuleList.tpl` with responsive JavaScript that automatically collapses menu items when the window becomes too small. The system uses Bootstrap classes for responsive behavior and includes both desktop and mobile-specific navigation patterns.

**Sources:** [themes/SuiteP/tpls/_headerModuleList.tpl:42-489](), [themes/SuiteP/js/style.js:292-350]()

## View Rendering System

The view rendering system uses a hierarchical template structure to generate detail views, edit views, and other page types. The system supports both tabbed and panel-based layouts.

```mermaid
graph TB
    subgraph "DetailView Structure"
        DetailViewTpl["DetailView.tpl"]
        HeaderTpl["header.tpl"]
        TabPanelContent["tab_panel_content.tpl"]
        FooterTpl["footer.tpl"]
    end
    
    subgraph "Layout Components"
        Tabs["nav nav-tabs"]
        TabContent["tab-content"]
        Panels["panel panel-default"]
        ActionMenu["Actions Menu"]
    end
    
    subgraph "Field Rendering"
        SugarField["sugar_field"]
        InlineEdit["Inline Edit Icons"]
        FieldValidation["Field Validation"]
    end
    
    subgraph "JavaScript Control"
        SelectTab["selectTabDetailView()"]
        TabClick["Tab Click Handlers"]
        ResponsiveFields["hideEmptyFormCellsOnTablet()"]
    end
    
    DetailViewTpl --> HeaderTpl
    DetailViewTpl --> TabPanelContent
    DetailViewTpl --> FooterTpl
    
    DetailViewTpl --> Tabs
    Tabs --> TabContent
    TabContent --> Panels
    
    TabPanelContent --> SugarField
    SugarField --> InlineEdit
    
    SelectTab --> TabClick
    ResponsiveFields --> FieldValidation
```

The detail view system supports conditional tab rendering based on `useTabs` configuration and `tabDefs` metadata. Panels can be collapsed or expanded, and the system includes action menus when `enable_action_menu` is configured.

**Sources:** [themes/SuiteP/include/DetailView/DetailView.tpl:44-365](), [themes/SuiteP/include/DetailView/tab_panel_content.tpl:45-213](), [themes/SuiteP/include/DetailView/header.tpl:53-104]()

## JavaScript Framework

The JavaScript framework provides interactive behavior, responsive design logic, and UI component management through the `SUGAR` namespace and jQuery extensions.

```mermaid
graph TB
    subgraph "SUGAR Namespace"
        SUGARMeasurements["SUGAR.measurements"]
        SUGARThemes["SUGAR.themes"]
        SUGARLoaded["SUGAR.loaded_once"]
    end
    
    subgraph "Responsive System"
        Breakpoints["breakpoints"]
        WindowResize["windowResize handlers"]
        SidebarToggle["Sidebar Toggle"]
        MobileAdaptation["Mobile Adaptation"]
    end
    
    subgraph "UI Components"
        LoadSidebar["loadSidebar()"]
        ActionMenu["sugarActionMenu()"]
        BackToTop["backtotop animation"]
        CheckboxInit["Bootstrap Checkbox"]
    end
    
    subgraph "Theme Specific"
        ModuleList["loadModuleList()"]
        TabSelection["selectTab()"]
        FooterPopups["initFooterPopups()"]
        CookieState["Cookie State Management"]
    end
    
    SUGARMeasurements --> Breakpoints
    SUGARThemes --> ModuleList
    
    Breakpoints --> WindowResize
    WindowResize --> SidebarToggle
    WindowResize --> MobileAdaptation
    
    LoadSidebar --> CookieState
    SidebarToggle --> CookieState
    
    ActionMenu --> CheckboxInit
    TabSelection --> FooterPopups
```

The framework defines responsive breakpoints at x-small (750px), small (768px), medium (992px), large (1130px), and x-large (1250px). The sidebar toggle functionality uses cookies to persist state across sessions.

**Sources:** [themes/SuiteP/js/style.js:40-537](), [themes/SuiteP/tpls/footer.tpl:78-106]()

## Template Rendering Flow

The template system follows a hierarchical rendering pattern where main templates include sub-templates and apply conditional logic based on configuration and user preferences.

```mermaid
graph TB
    subgraph "Template Variables"
        SectionPanels["sectionPanels"]
        TabDefs["tabDefs"]
        Fields["fields"]
        ModuleData["module data"]
    end
    
    subgraph "Conditional Rendering"
        UseTabs["useTabs check"]
        NewTab["newTab check"]
        PanelDefault["panelDefault state"]
        EnableActionMenu["enable_action_menu"]
    end
    
    subgraph "Field Processing"
        FieldIteration["Field Iteration"]
        SugarEvalColumn["sugar_evalcolumn"]
        CustomCode["customCode"]
        DisplayParams["displayParams"]
    end
    
    subgraph "Output Generation"
        HTMLStructure["HTML Structure"]
        CSSClasses["CSS Classes"]
        JSHandlers["JavaScript Handlers"]
    end
    
    SectionPanels --> UseTabs
    TabDefs --> NewTab
    TabDefs --> PanelDefault
    
    UseTabs --> HTMLStructure
    NewTab --> HTMLStructure
    EnableActionMenu --> HTMLStructure
    
    Fields --> FieldIteration
    FieldIteration --> SugarEvalColumn
    SugarEvalColumn --> CustomCode
    
    HTMLStructure --> CSSClasses
    HTMLStructure --> JSHandlers
```

Templates use Smarty's `{counter}`, `{foreach}`, and `{capture}` directives extensively to manage complex rendering logic. The system supports inline editing through conditional class application and icon rendering.

**Sources:** [themes/SuiteP/include/DetailView/tab_panel_content.tpl:76-191](), [themes/SuiteP/include/DetailView/DetailView.tpl:177-317]()

## Responsive Design Implementation

The responsive system adapts the interface for different screen sizes using Bootstrap grid classes and JavaScript-based layout adjustments.

| Breakpoint | Width | Behavior |
|------------|--------|----------|
| x-small | ≤750px | Mobile menu, collapsed sidebar |
| small | 768px | Tablet layout, conditional sidebar |
| medium | 992px | Desktop layout, full sidebar |
| large | 1130px | Large desktop, expanded navigation |
| x-large | ≥1250px | Full-width layout |

The responsive system includes:
- **Mobile Navigation**: Dropdown menu with module shortcuts
- **Sidebar Management**: Collapsible with cookie persistence  
- **Field Hiding**: Empty form cells hidden on tablet
- **Navigation Overflow**: Menu items moved to overflow menu when space is limited

**Sources:** [themes/SuiteP/js/style.js:40-48](), [themes/SuiteP/js/style.js:442-486](), [themes/SuiteP/tpls/_headerModuleList.tpl:308-330]()

## Theme Configuration System

The theme configuration system allows administrators to customize theme settings through a dedicated interface that manages color schemes, layout options, and feature toggles.

```mermaid
graph TB
    subgraph "Administration Interface"
        ThemeSettings["ThemeSettings View"]
        ThemeConfigSettings["ThemeConfigSettings View"]
        ThemeSelection["Theme Selection"]
        ConfigForm["Configuration Form"]
    end
    
    subgraph "Configuration Storage"
        Configurator["Configurator Class"]
        ThemeConfig["theme_settings config"]
        SugarConfig["sugar_config array"]
        ConfigOverride["config_override.php"]
    end
    
    subgraph "Theme Registry"
        SugarThemeRegistry["SugarThemeRegistry"]
        AllThemes["allThemes()"]
        GetThemeConfig["getThemeConfig()"]
        ThemeValidation["Theme Validation"]
    end
    
    ThemeSettings --> ThemeSelection
    ThemeConfigSettings --> ConfigForm
    
    ConfigForm --> Configurator
    Configurator --> ThemeConfig
    ThemeConfig --> SugarConfig
    SugarConfig --> ConfigOverride
    
    ThemeSelection --> SugarThemeRegistry
    SugarThemeRegistry --> AllThemes
    SugarThemeRegistry --> GetThemeConfig
    GetThemeConfig --> ThemeValidation
```

Theme configuration supports both boolean and color field types, with validation to ensure theme compatibility and security. The system prevents unauthorized access through admin permission checks.

**Sources:** [modules/Administration/views/view.themeconfigsettings.php:67-119](), [modules/Administration/templates/themeConfigSettings.tpl:43-84](), [modules/Administration/templates/themeSettings.tpl:68-81]()