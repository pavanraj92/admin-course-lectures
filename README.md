# Admin Courses Package

A comprehensive Laravel package for managing educational courses with integration to categories and tags packages, featuring approval workflow and featured course management.

## Features

- **Course Management**: Create, read, update, and delete courses with soft delete functionality
- **Course Approval Workflow**: Pending/Approved/Rejected status management
- **Featured Courses**: Highlight selected courses on homepage
- **External Package Integration**: 
  - Uses `admin/categories` package for course categorization
  - Uses `admin/tags` package for course tagging
- **Rich Course Information**: Title, short description, description, difficulty level, language, thumbnails, promo videos
- **Filtering & Sorting**: Advanced filtering by status, level, language with sortable columns
- **Responsive Design**: Modern Bootstrap-based interface

## Package Dependencies

This package requires the following packages to be installed:
- `admin/categories` - For course categorization
- `admin/tags` - For course tagging

## Database Structure

### Tables Created

1. **courses** - Main course information
   - id, title, slug, short_description, description, language, level, is_highlight, thumbnail_url, promo_video_url, status, timestamps, soft deletes

2. **course_category** - Course-Category pivot table (references categories package)
   - id, course_id, category_id, timestamps

3. **course_tag** - Course-Tag pivot table (references tags package)
   - id, course_id, tag_id, timestamps

### External Tables Used
- **categories** - From `admin/categories` package
- **tags** - From `admin/tags` package

## Models & Relationships

- **Course Model**: 
  - Belongs to many Categories (via `admin\categories\Models\Category`)
  - Belongs to many Tags (via `admin\tags\Models\Tag`)
  - Sortable & Filterable capabilities
  - Soft delete functionality

## Controllers & Views

- **CourseManagerController**: Full CRUD operations with status management
- **Responsive Views**: Index, create/edit, and detail views with proper integration
- **AJAX Status Updates**: Real-time status and highlight toggles
- **External Model Integration**: Properly references Category and Tag models from their respective packages

## Installation Requirements

Before installing this package, ensure these packages are installed:
1. `admin/categories` - Required for course categorization
2. `admin/tags` - Required for course tagging

## Installation Commands

The package includes console commands for:
- `courses:publish` - Publish module files
- `courses:status` - Check installation status  
- `courses:debug` - Debug configuration
- `courses:test-views` - Test view resolution

## Routes

- `/admin/courses` - Course listing with filters
- `/admin/courses/create` - Create new course
- `/admin/courses/{id}` - View course details
- `/admin/courses/{id}/edit` - Edit course
- `/admin/courses/updateStatus` - AJAX status updates
- `/admin/courses/updateHighlight` - AJAX highlight toggles

## Configuration

Course-specific configuration in `config/course.php` includes status labels, level badges, and highlight indicators.

## Industry Package Integration

This package is included in the following industry configurations:
- **Education**: Core package for educational institutions and learning platforms

## Dependencies

- Laravel 8+
- Kyslik ColumnSortable package
- Bootstrap 4/5 for UI
- Select2 for multi-select functionality
- `admin/categories` package for categorization
- `admin/tags` package for tagging

## Usage

After installation, courses can be managed through the admin panel with:
- Full CRUD operations with validation
- Category assignment from categories package
- Tag assignment from tags package  
- Approval workflow management
- Featured course highlighting
