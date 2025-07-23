# Commission Tracking Dashboard

## High-Level Description

This feature provides a comprehensive commission tracking system for real estate agents and brokers. It tracks commission structures, calculates splits between agents and brokers, monitors payment status, and provides visual dashboards for commission analytics. The system integrates with the Properties module to automatically calculate commissions when properties are sold and includes forecasting capabilities based on pipeline deals.

## Relevant Files and Architecture Pieces

### Core Integration Points
- `modules/REProperties/` - Property sale data
- `modules/Opportunities/` - Deal pipeline
- `modules/Users/` - Agent information
- `modules/Accounts/` - Brokerage data

### Financial Components
- `modules/Currencies/` - Multi-currency support
- `include/SugarCurrency/` - Currency calculations
- `modules/AOS_Invoices/` - Invoice generation

### Dashboard and Reporting
- `modules/Home/Dashlets/` - Dashboard components
- `modules/AOR_Reports/` - Reporting engine
- `modules/AOR_Charts/` - Chart generation
- `themes/SuiteP/css/` - Dashboard styling

### Calculation Engine
- `modules/AOW_WorkFlow/` - Automated calculations
- `include/SugarMath/` - Mathematical operations
- `data/SugarBean.php` - Data persistence

## Step-by-Step Todo List

### 1. Create Commission Module
- [ ] Create `modules/RECommissions/` directory structure
- [ ] Create `RECommission.php` extending SugarBean
- [ ] Define table `re_commissions` with fields:
  - `property_id` (relate to property)
  - `sale_price` (currency)
  - `commission_rate` (decimal percentage)
  - `gross_commission` (currency)
  - `listing_side` (currency)
  - `selling_side` (currency)
  - `status` (pending, paid, cancelled)
  - `closing_date` (date)

### 2. Build Commission Structure Management
- [ ] Create `modules/RECommissionStructures/` for templates
- [ ] Define commission structure fields:
  - `structure_name` (varchar)
  - `agent_split` (percentage)
  - `broker_split` (percentage)
  - `tier_thresholds` (JSON for volume tiers)
  - `bonus_structure` (JSON)
- [ ] Create UI for structure management
- [ ] Add validation for split totals

### 3. Extend Property Module
- [ ] Add commission fields to Properties:
  - `commission_rate` (decimal)
  - `listing_agent_id` (relate to User)
  - `selling_agent_id` (relate to User)
  - `co_listing_agent_id` (optional)
  - `referral_fee` (currency)
  - `closing_date` (date)
- [ ] Add sale status workflow

### 4. Create Commission Calculator
- [ ] Build `modules/RECommissions/CommissionCalculator.php`
- [ ] Implement calculation logic:
  - Base commission from sale price
  - Agent/broker split calculation
  - Co-listing agent splits
  - Referral fee deductions
  - Tiered commission rates
- [ ] Handle edge cases (dual agency, team splits)

### 5. Build Commission Dashboard
- [ ] Create main commission dashboard view
- [ ] Add visual components:
  - YTD earnings gauge
  - Monthly commission chart
  - Pending vs paid pie chart
  - Average commission indicator
  - Commission pipeline forecast
- [ ] Implement date range filters

### 6. Create Commission Dashlets
- [ ] Build "My Commissions" dashlet
- [ ] Create "Team Performance" dashlet
- [ ] Add "Commission Pipeline" dashlet
- [ ] Implement "Recent Closings" list
- [ ] Create "Commission Goals" tracker

### 7. Implement Payment Tracking
- [ ] Add payment fields to commissions:
  - `payment_date` (date)
  - `payment_method` (dropdown)
  - `check_number` (varchar)
  - `payment_notes` (text)
- [ ] Create payment status workflow
- [ ] Build payment history view

### 8. Add Forecasting Features
- [ ] Create pipeline analysis tool
- [ ] Calculate expected commissions from:
  - Opportunities in pipeline
  - Probability-weighted values
  - Historical close rates
- [ ] Build forecast visualization
- [ ] Add goal comparison

### 9. Build Commission Reports
- [ ] Create standard reports:
  - Agent ranking by commission
  - Commission by property type
  - Monthly/quarterly summaries
  - Tax year reports
  - Brokerage commission totals
- [ ] Add export capabilities

### 10. Implement Split Agreements
- [ ] Create split agreement templates
- [ ] Handle complex scenarios:
  - Team splits
  - Mentor/mentee arrangements
  - Referral agreements
  - Special bonus structures
- [ ] Add agreement history tracking

### 11. Create Mobile Dashboard
- [ ] Build responsive commission view
- [ ] Add mobile-optimized charts
- [ ] Create quick commission calculator
- [ ] Implement push notifications for payments
- [ ] Add offline capability

### 12. Testing and Validation
- [ ] Test commission calculations accuracy
- [ ] Verify split agreement logic
- [ ] Test multi-currency handling
- [ ] Validate dashboard performance
- [ ] Test report generation
- [ ] Verify payment tracking workflow
- [ ] Test mobile responsiveness