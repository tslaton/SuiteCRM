# Reports and Analytics

<details>
<summary>Relevant source files</summary>

The following files were used as context for generating this wiki page:

- [include/SuiteGraphs/RGraphIncludes.php](include/SuiteGraphs/RGraphIncludes.php)
- [modules/AOR_Charts/AOR_Chart.php](modules/AOR_Charts/AOR_Chart.php)
- [modules/AOR_Reports/AOR_Report.js](modules/AOR_Reports/AOR_Report.js)
- [modules/AOR_Reports/AOR_Report.php](modules/AOR_Reports/AOR_Report.php)
- [modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.js](modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.js)
- [modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.php](modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.php)
- [modules/AOR_Reports/Dashlets/AORReportsDashlet/dashlet.tpl](modules/AOR_Reports/Dashlets/AORReportsDashlet/dashlet.tpl)
- [modules/AOR_Reports/Dashlets/AORReportsDashlet/dashletConfigure.tpl](modules/AOR_Reports/Dashlets/AORReportsDashlet/dashletConfigure.tpl)
- [modules/AOR_Reports/Menu.php](modules/AOR_Reports/Menu.php)
- [modules/AOR_Reports/aor_utils.php](modules/AOR_Reports/aor_utils.php)
- [modules/AOR_Reports/controller.php](modules/AOR_Reports/controller.php)
- [modules/AOR_Reports/language/en_us.lang.php](modules/AOR_Reports/language/en_us.lang.php)
- [modules/AOR_Reports/metadata/detailviewdefs.php](modules/AOR_Reports/metadata/detailviewdefs.php)
- [modules/AOR_Reports/tpls/report.tpl](modules/AOR_Reports/tpls/report.tpl)
- [modules/AOR_Reports/vardefs.php](modules/AOR_Reports/vardefs.php)
- [modules/AOR_Reports/views/view.detail.php](modules/AOR_Reports/views/view.detail.php)
- [modules/AOW_WorkFlow/aow_utils.php](modules/AOW_WorkFlow/aow_utils.php)

</details>



This document covers the Advanced OpenReports (AOR) system in SuiteCRM, which provides comprehensive reporting and data visualization capabilities. The system allows users to create custom reports from any module data, apply filtering conditions, generate various chart types, and export results in multiple formats.

For general search functionality, see page [5.3](#5.3). For campaign management and email analytics, see page [4.3](#4.3).

## System Architecture

The Reports and Analytics system is built around several core modules that work together to provide end-to-end reporting functionality.

```mermaid
graph TB
    subgraph "User Interface Layer"
        UI["AOR_ReportsViewDetail"]
        CTL["AOR_ReportsController"] 
        TPL["report.tpl"]
        DASH["AORReportsDashlet"]
    end
    
    subgraph "Core Business Logic"
        REP["AOR_Report"]
        CHART["AOR_Chart"]
        FIELD["AOR_Fields"]
        COND["AOR_Conditions"]
    end
    
    subgraph "Rendering Engines"
        PCHART["pChart Library"]
        RGRAPH["RGraph Library"]
        CHARTJS["Chart.js Library"]
    end
    
    subgraph "Export Systems"
        CSV["CSV Export"]
        PDF["PDF Export"]
        PROS["Prospect List Integration"]
    end
    
    subgraph "Utility Layer"
        UTILS["aor_utils.php"]
        AOWUTILS["aow_utils.php"]
    end
    
    UI --> CTL
    CTL --> REP
    REP --> FIELD
    REP --> COND
    REP --> CHART
    
    CHART --> PCHART
    CHART --> RGRAPH
    CHART --> CHARTJS
    
    REP --> CSV
    REP --> PDF
    REP --> PROS
    
    REP --> UTILS
    CTL --> AOWUTILS
    
    DASH --> REP
    DASH --> CHART
```

**System Architecture Overview**

Sources: [modules/AOR_Reports/AOR_Report.php:1-80](), [modules/AOR_Reports/controller.php:1-50](), [modules/AOR_Charts/AOR_Chart.php:1-60]()

## Core Entities

### AOR_Report Class

The `AOR_Report` class serves as the primary entity for report definitions and execution.

```mermaid
classDiagram
    class AOR_Report {
        +string report_module
        +int graphs_per_row
        +array user_parameters
        +save()
        +build_report_html()
        +build_group_report()
        +build_report_chart()
        +build_report_csv()
        +getReportFields()
        +ACLAccess()
    }
    
    class AOR_Chart {
        +string type
        +string x_field
        +string y_field
        +string name
        +buildChartHTML()
        +buildChartImage()
        +save_lines()
    }
    
    class AOR_Fields {
        +string field
        +string label
        +string field_function
        +boolean display
        +boolean group_display
        +int field_order
    }
    
    class AOR_Conditions {
        +string field
        +string operator
        +string value_type
        +string value
        +boolean parameter
        +int condition_order
    }
    
    AOR_Report ||--o{ AOR_Fields : "has many"
    AOR_Report ||--o{ AOR_Conditions : "has many"
    AOR_Report ||--o{ AOR_Chart : "has many"
```

**Core Entity Relationships**

Sources: [modules/AOR_Reports/AOR_Report.php:48-80](), [modules/AOR_Charts/AOR_Chart.php:26-65](), [modules/AOR_Reports/vardefs.php:122-150]()

## Report Generation Process

The report generation system follows a multi-stage process to transform database queries into formatted output.

```mermaid
flowchart TD
    START["User Request"] --> PARAM["requestToUserParameters()"]
    PARAM --> QUERY["build_report_query()"]
    QUERY --> GROUP{"Grouped Report?"}
    
    GROUP -->|Yes| MULTIGROUP["buildMultiGroupReport()"]
    GROUP -->|No| HTML["build_report_html()"]
    
    MULTIGROUP --> GROUPHTML["build_group_report()"]
    GROUPHTML --> HTML
    
    HTML --> PAGINATION["Pagination Logic"]
    PAGINATION --> TOTALS["getTotalHTML()"]
    TOTALS --> RENDER["HTML Rendering"]
    
    RENDER --> CHARTS["build_report_chart()"]
    CHARTS --> CHARTTYPE{"Chart Type"}
    
    CHARTTYPE -->|PCHART| PIMG["buildChartImage()"]
    CHARTTYPE -->|RGRAPH| RGRAPHHTML["buildChartHTMLRGraph()"]
    CHARTTYPE -->|CHARTJS| CHARTJSHTML["buildChartHTMLChartJS()"]
    
    PIMG --> OUTPUT["Final Output"]
    RGRAPHHTML --> OUTPUT
    CHARTJSHTML --> OUTPUT
```

**Report Generation Flow**

The core report generation process begins with parameter processing through `requestToUserParameters()` [modules/AOR_Reports/aor_utils.php:96-173](), followed by query building and HTML rendering via `build_report_html()` [modules/AOR_Reports/AOR_Report.php:624-875]().

Sources: [modules/AOR_Reports/AOR_Report.php:302-361](), [modules/AOR_Reports/aor_utils.php:96-173](), [modules/AOR_Reports/views/view.detail.php:75-85]()

## Chart System

The system supports multiple chart rendering engines and various visualization types.

### Chart Types and Rendering

```mermaid
graph LR
    subgraph "Chart Configuration"
        TYPE["Chart Type"]
        XFIELD["X-Axis Field"]
        YFIELD["Y-Axis Field"]
        TITLE["Chart Title"]
    end
    
    subgraph "Supported Types"
        BAR["bar"]
        LINE["line"] 
        PIE["pie"]
        RADAR["radar"]
        ROSE["rose"]
        GROUPED["grouped_bar"]
        STACKED["stacked_bar"]
    end
    
    subgraph "Rendering Engines"
        PCHART_ENG["pChart Engine"]
        RGRAPH_ENG["RGraph Engine"]
        CHARTJS_ENG["Chart.js Engine"]
    end
    
    TYPE --> BAR
    TYPE --> LINE
    TYPE --> PIE
    TYPE --> RADAR
    TYPE --> ROSE
    TYPE --> GROUPED
    TYPE --> STACKED
    
    BAR --> PCHART_ENG
    BAR --> RGRAPH_ENG
    BAR --> CHARTJS_ENG
    
    LINE --> PCHART_ENG
    LINE --> RGRAPH_ENG
    
    PIE --> PCHART_ENG
    PIE --> RGRAPH_ENG
    
    RADAR --> RGRAPH_ENG
    ROSE --> RGRAPH_ENG
    GROUPED --> RGRAPH_ENG
    STACKED --> RGRAPH_ENG
```

**Chart Type Support Matrix**

Chart rendering is handled by the `buildChartHTML()` method [modules/AOR_Charts/AOR_Chart.php:232-243]() which delegates to specific engines based on the `chartType` parameter.

Sources: [modules/AOR_Charts/AOR_Chart.php:93-96](), [modules/AOR_Charts/AOR_Chart.php:181-184](), [modules/AOR_Charts/AOR_Chart.php:266-351]()

## Export Functionality

### Export Actions and Controllers

```mermaid
flowchart TD
    USER["User Export Request"] --> ACTION{"Export Type"}
    
    ACTION -->|CSV| CSV_ACT["action_export()"]
    ACTION -->|PDF| PDF_ACT["action_downloadPDF()"]
    ACTION -->|Prospect List| PROS_ACT["action_addToProspectList()"]
    
    CSV_ACT --> CSV_CHECK["ACL Check"]
    PDF_ACT --> PDF_CHECK["ACL Check"]
    PROS_ACT --> PROS_CHECK["ACL Check"]
    
    CSV_CHECK --> CSV_PARAMS["requestToUserParameters()"]
    PDF_CHECK --> PDF_PARAMS["requestToUserParameters()"]
    PROS_CHECK --> PROS_QUERY["build_report_query()"]
    
    CSV_PARAMS --> CSV_BUILD["build_report_csv()"]
    PDF_PARAMS --> PDF_BUILD["build_group_report()"]
    PROS_QUERY --> PROS_REL["Relationship Processing"]
    
    CSV_BUILD --> CSV_OUT["CSV Download"]
    PDF_BUILD --> PDF_WRAP["PDFWrapper"]
    PROS_REL --> PROS_OUT["Target List Update"]
    
    PDF_WRAP --> PDF_OUT["PDF Download"]
```

**Export Process Flow**

The export system provides three main output formats controlled by dedicated controller actions. CSV export uses `build_report_csv()` while PDF export leverages the `PDFWrapper` class for document generation.

Sources: [modules/AOR_Reports/controller.php:175-186](), [modules/AOR_Reports/controller.php:192-272](), [modules/AOR_Reports/controller.php:133-166]()

## Dashboard Integration

### AORReportsDashlet Architecture

The dashlet system allows reports to be embedded in user dashboards with configurable parameters and chart display options.

```mermaid
graph TB
    subgraph "Dashlet Configuration"
        CONFIG["dashletConfigure.tpl"]
        PARAMS["Parameter Configuration"]
        CHARTS["Chart Selection"]
        ONLYCHARTS["Charts Only Mode"]
    end
    
    subgraph "Dashlet Runtime"
        DASHLET["AORReportsDashlet"]
        DISPLAY["display()"]
        CHARTHTML["getChartHTML()"]
        SAVE["saveOptions()"]
    end
    
    subgraph "Report Integration"
        REPORT["AOR_Report Instance"]
        USERPARAMS["user_parameters"]
        CHARTBUILD["build_report_chart()"]
    end
    
    CONFIG --> DASHLET
    PARAMS --> USERPARAMS
    CHARTS --> CHARTHTML
    
    DASHLET --> DISPLAY
    DISPLAY --> CHARTHTML
    CHARTHTML --> REPORT
    
    REPORT --> USERPARAMS
    REPORT --> CHARTBUILD
    
    ONLYCHARTS --> DISPLAY
```

**Dashlet Integration Architecture**

The `AORReportsDashlet` class [modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.php:11-169]() manages report embedding with configurable parameters and chart filtering capabilities.

Sources: [modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.php:18-50](), [modules/AOR_Reports/Dashlets/AORReportsDashlet/AORReportsDashlet.php:73-81](), [modules/AOR_Reports/Dashlets/AORReportsDashlet/dashletConfigure.tpl:1-200]()

## User Interface Components

### Report Display Template System

| Component | Template File | JavaScript Controller | Purpose |
|-----------|---------------|----------------------|---------|
| Main Report View | `report.tpl` | `AOR_Report.js` | Primary report display |
| Parameter Panel | Embedded in `report.tpl` | `conditionLines.js` | Dynamic filtering |
| Pagination | Generated in `build_report_html()` | `changeReportPage()` | Result navigation |
| Chart Container | Dynamic HTML | RGraph/Chart.js | Visualization display |

The main report interface uses `report.tpl` [modules/AOR_Reports/tpls/report.tpl:1-117]() which integrates parameter controls, chart rendering areas, and tabular data display.

### JavaScript Integration

```mermaid
graph LR
    subgraph "Client-Side Scripts"
        MAIN["AOR_Report.js"]
        COND["conditionLines.js"] 
        DASHLET["AORReportsDashlet.js"]
        RGRAPH["RGraphIncludes.php"]
    end
    
    subgraph "Key Functions"
        PARAMS["addParametersToForm()"]
        PAGE["changeReportPage()"]
        PDF["PDF Export Handler"]
        CSV["CSV Export Handler"]
    end
    
    subgraph "Chart Libraries"
        RGRAPHLIB["RGraph Libraries"]
        CHARTJSLIB["Chart.js Library"]
        PCHARTLIB["pChart (Server-side)"]
    end
    
    MAIN --> PARAMS
    MAIN --> PAGE
    MAIN --> PDF
    MAIN --> CSV
    
    COND --> PARAMS
    DASHLET --> PAGE
    
    RGRAPH --> RGRAPHLIB
    MAIN --> CHARTJSLIB
```

**JavaScript Architecture**

The client-side functionality centers around parameter management through `addParametersToForm()` [modules/AOR_Reports/AOR_Report.js:117-128]() and dynamic report updates via `changeReportPage()` [modules/AOR_Reports/AOR_Report.js:164-200]().

Sources: [modules/AOR_Reports/AOR_Report.js:24-53](), [modules/AOR_Reports/tpls/report.tpl:69-96](), [include/SuiteGraphs/RGraphIncludes.php:1-181]()

## Data Processing Pipeline

### Field and Condition Processing

The system processes report definitions through a sophisticated pipeline that handles field selection, condition evaluation, and data aggregation.

```mermaid
flowchart TD
    MODULE["Module Selection"] --> FIELDS["Field Configuration"]
    FIELDS --> FIELDPATH["Module Path Resolution"]
    FIELDPATH --> CONDITIONS["Condition Setup"]
    
    CONDITIONS --> CONDEVAL["Condition Evaluation"]
    CONDEVAL --> PARAMS["Parameter Substitution"]
    PARAMS --> QUERY["SQL Query Building"]
    
    QUERY --> JOINS["Relationship Joins"]
    JOINS --> WHERE["WHERE Clause Assembly"]
    WHERE --> GROUPBY["GROUP BY Processing"]
    GROUPBY --> ORDERBY["ORDER BY Application"]
    
    ORDERBY --> EXECUTE["Query Execution"]
    EXECUTE --> POSTPROC["Post-processing"]
    POSTPROC --> FORMAT["Field Formatting"]
    FORMAT --> OUTPUT["Final Output"]
```

**Data Processing Pipeline**

Field processing utilizes `getModuleFields()` [modules/AOW_WorkFlow/aow_utils.php:45-126]() for dynamic field discovery, while condition evaluation leverages `getConditionsAsParameters()` [modules/AOR_Reports/aor_utils.php:175-222]() for parameter-based filtering.

Sources: [modules/AOR_Reports/AOR_Report.php:168-179](), [modules/AOW_WorkFlow/aow_utils.php:149-170](), [modules/AOR_Reports/aor_utils.php:51-94]()