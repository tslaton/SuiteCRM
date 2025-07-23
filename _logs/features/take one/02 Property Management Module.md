# Property Management Module

## High-Level Description

The Property Management Module is the foundation for all real estate functionality in SuiteCRM. It creates a new module called "Properties" that manages real estate listings with all essential property details like address, price, bedrooms, bathrooms, square footage, and property type. This module integrates seamlessly with existing CRM modules like Contacts (buyers/sellers), Accounts (real estate agencies), and Activities (showings, open houses).

## Relevant Files and Architecture Pieces

### Core SuiteCRM Components
- `modules/` - Module directory structure
- `include/SugarObjects/templates/basic/Basic.php` - Base template for new modules
- `data/SugarBean.php` - ORM base class
- `include/MVC/Controller/SugarController.php` - Controller base class
- `include/MVC/View/SugarView.php` - View base class
- `modules/ModuleBuilder/` - Module builder system
- `custom/Extension/` - Extension framework for customizations

### Database Components
- `metadata/` - Relationship metadata definitions
- `include/database/DBManagerFactory.php` - Database abstraction layer
- `modules/Administration/updater_utils.php` - Database update utilities

### UI Components
- `themes/SuiteP/` - Theme system
- `include/EditView/EditView2.php` - Edit view framework
- `include/ListView/ListView.php` - List view framework
- `include/DetailView/DetailView2.php` - Detail view framework

## Step-by-Step Todo List

### 1. Create Module Structure
- [ ] Create directory `modules/REProperties/`
- [ ] Create subdirectories: `Dashlets/`, `language/`, `metadata/`, `views/`
- [ ] Create `REProperties.php` extending SugarBean
- [ ] Define module icon and color in module config

### 2. Define Property Bean Class
- [ ] Create `modules/REProperties/REProperty.php` with class extending Basic template
- [ ] Set table name to `re_properties`
- [ ] Set module name to `REProperties`
- [ ] Define ACL type as 'module'
- [ ] Enable importable, unified search, and optimistic locking

### 3. Create Variable Definitions (Vardefs)
- [ ] Create `modules/REProperties/vardefs.php`
- [ ] Define core fields:
  - `name` (property title)
  - `property_type` (dropdown: house, condo, apartment, commercial)
  - `status` (dropdown: active, pending, sold, rented)
  - `listing_price` (currency field)
  - `address_street`, `address_city`, `address_state`, `address_postalcode`, `address_country`
  - `bedrooms` (integer)
  - `bathrooms` (decimal)
  - `square_feet` (integer)
  - `lot_size` (decimal)
  - `year_built` (integer)
  - `property_description` (text)
  - `mls_number` (varchar)
- [ ] Add audit fields for tracking changes
- [ ] Define indices for performance

### 4. Create Language Files
- [ ] Create `modules/REProperties/language/en_us.lang.php`
- [ ] Define module labels and field labels
- [ ] Create dropdown lists for property_type and status
- [ ] Add module to global module list in `custom/Extension/application/Ext/Language/`

### 5. Create Metadata Files
- [ ] Create `modules/REProperties/metadata/listviewdefs.php` for list view columns
- [ ] Create `modules/REProperties/metadata/detailviewdefs.php` for detail view layout
- [ ] Create `modules/REProperties/metadata/editviewdefs.php` for edit view layout
- [ ] Create `modules/REProperties/metadata/searchdefs.php` for search filters
- [ ] Create `modules/REProperties/metadata/SearchFields.php` for advanced search

### 6. Define Relationships
- [ ] Create relationship with Contacts (property inquiries)
- [ ] Create relationship with Users (listing agents)
- [ ] Create relationship with Accounts (listing agencies)
- [ ] Create relationship with Activities (showings, open houses)
- [ ] Add relationship metadata files in `metadata/`

### 7. Create Views
- [ ] Create `modules/REProperties/views/view.list.php` for custom list view logic
- [ ] Create `modules/REProperties/views/view.detail.php` for detail view enhancements
- [ ] Create `modules/REProperties/views/view.edit.php` for edit view customizations
- [ ] Add map view for property locations (using existing mapping functionality)

### 8. Add Menu Items
- [ ] Create `modules/REProperties/Menu.php` for module menu
- [ ] Add "Create Property", "View Properties", "Import Properties" menu items
- [ ] Register module in `custom/Extension/application/Ext/Include/`

### 9. Create Dashlets
- [ ] Create "My Listings" dashlet in `modules/REProperties/Dashlets/`
- [ ] Create "Recent Properties" dashlet
- [ ] Add dashlet language files

### 10. Database Installation
- [ ] Create SQL install script in `modules/REProperties/sql/`
- [ ] Define table structure matching vardefs
- [ ] Add to manifest file for module loader
- [ ] Run database sync through Admin > Repair

### 11. Add Security and ACLs
- [ ] Define module ACLs in `modules/REProperties/REProperty.php`
- [ ] Create role-based permissions for property management
- [ ] Add field-level security for sensitive data

### 12. Testing and Validation
- [ ] Test CRUD operations on properties
- [ ] Verify relationships with Contacts, Users, and Accounts
- [ ] Test search and filter functionality
- [ ] Validate field types and constraints
- [ ] Test import/export functionality
- [ ] Verify ACLs and security settings