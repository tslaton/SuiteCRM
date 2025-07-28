# Real Estate Lead Scoring

## High-Level Description

This feature implements an intelligent lead scoring system specifically designed for real estate. It automatically scores leads based on their property viewing behavior, budget range, timeline urgency, engagement level, and demographic factors. The scoring system helps agents prioritize high-value leads and automate follow-up actions. It integrates with existing Leads and Contacts modules while leveraging property interaction data.

## Relevant Files and Architecture Pieces

### Core Modules to Extend
- `modules/Leads/` - Lead management module
- `modules/Contacts/` - Contact management
- `modules/REProperties/` - Property module (from Feature 02)
- `modules/Activities/` - Activity tracking

### Scoring Engine Components
- `modules/AOW_WorkFlow/` - Workflow engine for automation
- `include/SugarQueue/` - Background job processing
- `modules/Schedulers/` - Scheduled tasks for score updates

### Database and Logic
- `data/SugarBean.php` - Base class for scoring logic
- `include/utils/LogicHook.php` - Hook system for real-time scoring
- `modules/Administration/` - Admin configuration

### UI Components
- `include/ListView/` - List view customization for scores
- `themes/SuiteP/css/` - Visual indicators for lead scores
- `include/Dashlets/` - Lead scoring dashlets

## Step-by-Step Todo List

### 1. Create Lead Scoring Module
- [ ] Create `modules/RELeadScoring/` directory structure
- [ ] Create `RELeadScore.php` extending SugarBean
- [ ] Define table `re_lead_scores` for scoring history
- [ ] Create relationship with Leads and Contacts modules

### 2. Define Scoring Criteria Fields
- [ ] Extend Leads vardefs with scoring fields:
  - `re_score_total` (integer 0-100)
  - `re_score_grade` (enum: A, B, C, D)
  - `re_budget_min` (currency)
  - `re_budget_max` (currency)
  - `re_timeline` (dropdown: immediate, 3months, 6months, 1year+)
  - `re_property_preferences` (multiselect)
  - `re_financing_status` (dropdown: pre-approved, cash, needs_financing)
- [ ] Add scoring metadata fields for tracking

### 3. Build Scoring Engine
- [ ] Create `modules/RELeadScoring/ScoringEngine.php` class
- [ ] Implement scoring factors:
  - Budget alignment (20 points)
  - Timeline urgency (20 points)
  - Property views count (15 points)
  - Email engagement (15 points)
  - Form completeness (10 points)
  - Phone/meeting activity (10 points)
  - Document uploads (10 points)
- [ ] Create weighted scoring algorithm
- [ ] Add score decay over time logic

### 4. Track Property Interactions
- [ ] Create `re_property_views` relationship table
- [ ] Add logic hooks to track:
  - Property detail views
  - Virtual tour views
  - Document downloads
  - Inquiry form submissions
- [ ] Store interaction timestamps and duration
- [ ] Count repeat views of same property

### 5. Implement Real-time Scoring Updates
- [ ] Create logic hooks in `custom/modules/Leads/logic_hooks.php`:
  - `after_save` - Recalculate on lead update
  - `after_relationship_add` - Score on new activity
- [ ] Add hooks for Activities module
- [ ] Create hooks for email opens/clicks
- [ ] Implement score caching for performance

### 6. Create Scoring Rules Configuration
- [ ] Add admin panel in `modules/Administration/`:
  - Scoring weight configuration
  - Score threshold settings
  - Automation rules setup
- [ ] Create `modules/RELeadScoring/metadata/scoring_rules.php`
- [ ] Build UI for rule management
- [ ] Add import/export for scoring rules

### 7. Build Score Visualization
- [ ] Create custom field type `LeadScoreGauge`
- [ ] Add visual score indicator (thermometer/gauge)
- [ ] Implement color coding (red/yellow/green)
- [ ] Add score trend sparkline
- [ ] Create score history chart

### 8. Add List View Enhancements
- [ ] Modify `modules/Leads/metadata/listviewdefs.php`:
  - Add score column with visual indicator
  - Enable sorting by score
  - Add score-based row highlighting
- [ ] Create quick filters for score ranges
- [ ] Add bulk actions for score groups

### 9. Create Lead Scoring Dashlets
- [ ] Build "Top Scored Leads" dashlet
- [ ] Create "Lead Score Distribution" chart
- [ ] Add "Recent Score Changes" dashlet
- [ ] Implement "My Team's Hot Leads" dashlet

### 10. Implement Automated Actions
- [ ] Create workflow templates for score-based actions:
  - Auto-assign high-score leads
  - Send alerts for score increases
  - Trigger email campaigns by score
  - Create tasks for score thresholds
- [ ] Add to `modules/AOW_WorkFlow/` conditions

### 11. Build Reporting Components
- [ ] Create lead scoring reports:
  - Score distribution report
  - Score trend analysis
  - Agent performance by lead score
  - Conversion rate by score range
- [ ] Add to `modules/AOR_Reports/` templates

### 12. Testing and Optimization
- [ ] Test scoring calculation accuracy
- [ ] Verify real-time update performance
- [ ] Test workflow automations
- [ ] Validate score history tracking
- [ ] Load test with high volume of leads
- [ ] Test UI components across browsers
- [ ] Verify mobile responsiveness of score displays