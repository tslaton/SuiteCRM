# Virtual Property Tours Integration

## High-Level Description

This feature enhances the Property Management Module by adding support for virtual property tours through embedded videos, 360-degree images, and interactive floor plans. It integrates with popular virtual tour platforms (YouTube, Vimeo, Matterport) and allows agents to showcase properties remotely. The feature includes a media gallery for each property and tracking of tour engagement metrics.

## Relevant Files and Architecture Pieces

### Core Components to Modify
- `modules/REProperties/` - Property module to extend
- `include/SugarFields/Fields/` - Custom field types for media
- `themes/SuiteP/include/DetailView/` - Detail view templates
- `include/javascript/` - JavaScript libraries for media players

### UI and Template Components
- `themes/SuiteP/css/` - Styling for media galleries
- `include/EditView/EditView2.php` - Edit view for media upload
- `modules/REProperties/metadata/detailviewdefs.php` - Layout modifications
- `themes/SuiteP/js/` - Interactive tour controls

### Database Components
- `modules/REProperties/vardefs.php` - New field definitions
- `custom/Extension/modules/REProperties/` - Field extensions

### Integration Points
- `include/utils/file_utils.php` - File upload handling
- `include/SugarFields/Fields/Image/` - Image field handling
- `modules/Documents/` - Document relationship for media files

## Step-by-Step Todo List

### 1. Create Media Field Types
- [ ] Create custom field type `VirtualTourURL` in `include/SugarFields/Fields/VirtualTourURL/`
- [ ] Create `SugarFieldVirtualTourURL.php` for field logic
- [ ] Create `DetailView.tpl` for embedding tour players
- [ ] Create `EditView.tpl` for URL input with platform detection
- [ ] Add JavaScript validation for supported platforms

### 2. Extend Property Vardefs
- [ ] Add to `modules/REProperties/vardefs.php`:
  - `virtual_tour_url` (custom VirtualTourURL type)
  - `tour_platform` (dropdown: youtube, vimeo, matterport, none)
  - `tour_embed_code` (text field for custom embed codes)
  - `image_gallery` (relate field to Documents module)
  - `floor_plan_url` (URL field)
  - `tour_view_count` (integer for analytics)
- [ ] Create relationship with Documents module for media storage

### 3. Create Media Gallery Component
- [ ] Create `modules/REProperties/tpls/MediaGallery.tpl` template
- [ ] Implement image carousel using existing SuiteP carousel
- [ ] Add support for multiple image uploads
- [ ] Create thumbnail generation logic
- [ ] Implement lightbox view for full-size images

### 4. Build Tour Embed System
- [ ] Create `modules/REProperties/javascript/VirtualTourEmbed.js`
- [ ] Add platform detection logic:
  - YouTube URL parsing and iframe generation
  - Vimeo player API integration
  - Matterport showcase embed
- [ ] Implement responsive iframe sizing
- [ ] Add fullscreen toggle functionality

### 5. Update Property Views
- [ ] Modify `modules/REProperties/metadata/detailviewdefs.php`:
  - Add media gallery panel
  - Add virtual tour section
  - Include floor plan viewer
- [ ] Update `modules/REProperties/metadata/editviewdefs.php`:
  - Add media upload section
  - Include tour URL input
  - Add platform selector

### 6. Create Tour Analytics
- [ ] Create `modules/REProperties/TourAnalytics.php` class
- [ ] Track tour views with timestamp
- [ ] Log viewer information (if available)
- [ ] Create relationship with Contacts for tracking interest
- [ ] Add view counter increment logic

### 7. Implement 360-Degree Image Support
- [ ] Add JavaScript library for 360 image viewing (e.g., Photo Sphere Viewer)
- [ ] Create custom field type `Image360` 
- [ ] Add upload handling for panoramic images
- [ ] Implement viewer controls (pan, zoom, fullscreen)

### 8. Add Interactive Floor Plans
- [ ] Create `modules/REProperties/tpls/FloorPlanViewer.tpl`
- [ ] Add SVG or image map support for clickable areas
- [ ] Implement room information popups
- [ ] Add measurement display toggles
- [ ] Create print-friendly version

### 9. Mobile Optimization
- [ ] Ensure all media components are mobile-responsive
- [ ] Optimize video loading for mobile bandwidth
- [ ] Add touch gestures for 360 images
- [ ] Test virtual tour embeds on mobile browsers

### 10. Create Admin Configuration
- [ ] Add settings in `modules/Configurator/`:
  - Maximum file upload size for media
  - Allowed video platforms
  - Default tour platform
  - Image compression settings
- [ ] Create ACLs for media upload permissions

### 11. Add Language Support
- [ ] Update `modules/REProperties/language/en_us.lang.php`:
  - Add labels for new fields
  - Create help text for tour URLs
  - Add validation messages
  - Include analytics labels

### 12. Testing and Validation
- [ ] Test video embeds from all supported platforms
- [ ] Verify responsive behavior across devices
- [ ] Test large image uploads and gallery performance
- [ ] Validate tour view tracking accuracy
- [ ] Test relationship with Documents module
- [ ] Verify mobile gesture controls
- [ ] Check browser compatibility for 360 images