# Email & SMS Property Alerts

## High-Level Description

This feature implements an automated alert system that notifies clients about new properties matching their criteria, price changes, open houses, and market updates. It supports both email and SMS channels with customizable templates, delivery preferences, and engagement tracking. The system integrates with the property matching engine (Feature 05) and includes smart delivery optimization to prevent alert fatigue while ensuring timely notifications.

## Relevant Files and Architecture Pieces

### Communication Infrastructure
- `modules/Emails/` - Email system
- `modules/EmailMan/` - Email queue management
- `modules/EmailTemplates/` - Template system
- `include/SugarPHPMailer.php` - Email delivery

### SMS Integration
- `include/externalAPI/` - SMS provider integration
- `modules/Configurator/` - SMS configuration
- `include/connectors/` - External services

### Alert Engine
- `modules/Schedulers/` - Scheduled alerts
- `include/SugarQueue/` - Queue processing
- `modules/AOW_WorkFlow/` - Alert triggers
- `data/SugarBean.php` - Data access

### Tracking Components
- `modules/Campaigns/` - Engagement tracking
- `modules/CampaignTrackers/` - Click tracking
- `modules/CampaignLog/` - Delivery logs

## Step-by-Step Todo List

### 1. Create Property Alerts Module
- [ ] Create `modules/REPropertyAlerts/` directory
- [ ] Build `REPropertyAlert.php` extending SugarBean
- [ ] Define alert subscription table:
  - `contact_id` (subscriber)
  - `alert_type` (new_listing, price_change, open_house)
  - `frequency` (immediate, daily, weekly)
  - `criteria` (JSON search criteria)
  - `channel` (email, sms, both)
  - `status` (active, paused, unsubscribed)

### 2. Extend Contact Preferences
- [ ] Add alert preference fields:
  - `re_alert_email_enabled` (boolean)
  - `re_alert_sms_enabled` (boolean)
  - `re_alert_frequency` (dropdown)
  - `re_alert_time_preference` (time)
  - `re_alert_day_preference` (for weekly)
  - `re_mobile_number` (phone field)
- [ ] Create preference management UI

### 3. Build Alert Templates
- [ ] Create email templates:
  - New property alert
  - Price reduction alert
  - Open house invitation
  - Market update digest
  - Saved search results
- [ ] Design responsive HTML layouts
- [ ] Add dynamic content blocks

### 4. Implement SMS Integration
- [ ] Create SMS provider abstraction
- [ ] Add Twilio/SMS provider integration:
  - API credentials management
  - Message sending
  - Delivery status webhooks
  - Error handling
- [ ] Build SMS template system
- [ ] Implement character limits

### 5. Create Alert Generation Engine
- [ ] Build `modules/REPropertyAlerts/AlertEngine.php`
- [ ] Implement alert triggers:
  - New property detection
  - Price change monitoring
  - Open house scheduling
  - Saved search matching
- [ ] Add deduplication logic
- [ ] Create batch processing

### 6. Develop Smart Delivery System
- [ ] Implement delivery optimization:
  - Batch similar alerts
  - Respect frequency preferences
  - Time zone handling
  - Quiet hours enforcement
- [ ] Add alert prioritization
- [ ] Create throttling rules

### 7. Build Alert Queue Manager
- [ ] Create queue processing system
- [ ] Implement retry logic
- [ ] Add delivery status tracking
- [ ] Build error handling
- [ ] Create fallback mechanisms

### 8. Add Engagement Tracking
- [ ] Implement email tracking:
  - Open rates
  - Click tracking
  - Property view attribution
- [ ] Add SMS tracking:
  - Delivery confirmation
  - Link clicks
  - Reply handling
- [ ] Create engagement scoring

### 9. Create Alert Management Interface
- [ ] Build agent dashboard for:
  - Alert subscription overview
  - Delivery statistics
  - Engagement metrics
  - Failed delivery handling
- [ ] Add bulk management tools
- [ ] Create testing interface

### 10. Implement Unsubscribe System
- [ ] Create one-click unsubscribe
- [ ] Build preference center
- [ ] Add granular opt-out options
- [ ] Implement compliance tracking
- [ ] Create suppression lists

### 11. Build Analytics Dashboard
- [ ] Create alert performance metrics:
  - Delivery rates
  - Engagement rates
  - Conversion tracking
  - Alert type performance
- [ ] Add A/B testing framework
- [ ] Generate optimization insights

### 12. Testing and Compliance
- [ ] Test email deliverability
- [ ] Verify SMS delivery rates
- [ ] Test unsubscribe flows
- [ ] Validate CAN-SPAM compliance
- [ ] Test TCPA compliance for SMS
- [ ] Load test alert generation
- [ ] Test timezone handling