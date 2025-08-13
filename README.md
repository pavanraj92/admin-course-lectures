# Admin Course Manager

This package provides an Admin Course Manager for managing courses, lectures, purchases, reports, and transactions within your Laravel application.

## Features
- Create, edit, and delete courses
- Manage lectures and course sections
- Handle course purchases and transactions
- Generate purchase and transaction reports
- Tagging and categorization for courses
- Publishable migrations, views, and configuration for customization
- Fully namespaced and compatible with Modules/Courses structure
- Routes are auto-registered based on your admin slug

## Requirements
PHP >= 8.2
Laravel Framework >= 12.x

## Installation
### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-course-lectures.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/courses:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan courses:publish --force
    ```
---


## Usage
1. Create Course – Add a new course with title, description, category, tags, and price.
2. Manage Lectures – Create and organize lectures into course sections.
3. Handle Purchases – View and manage student purchases.
4. Track Transactions – View all related financial transactions.
5. View Reports – Access reports for purchases and transactions.

---

### Admin Panel Routes
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | `/courses` | List all courses |
| GET    | `/courses/create` | Create course form |
| POST   | `/courses` | Store new course |
| GET    | `/courses/{id}` | Show course details |
| GET    | `/courses/{id}/edit` | Edit course form |
| PUT    | `/courses/{id}` | Update course |
| DELETE | `/courses/{id}` | Delete course |
| POST | `/courses/updateStatus` | Update course status|
| POST | `/courses/updateHighlight` | Update course highlights|

| GET    | `/lectures` | List all lectures |
| GET    | `/lectures/create` | Create lecture form |
| POST   | `/lectures` | Store new lecture |
| GET    | `/lectures/{id}` | Show lecture details |
| GET    | `/lectures/{id}/edit` | Edit lecture form |
| PUT    | `/lectures/{id}` | Update lecture |
| DELETE | `/lectures/{id}` | Delete lecture |
| POST | `/lectures/updateStatus` | Update lecture status|
| POST | `/lectures/updateHighlight` | Update lecture highlights|
| GET | `/fetch/course/section/{course}` | Fetch course sections|

| GET    | `/transactions` | List all transactions|
| GET | `/transactions/{id}` | Sgow transactions details |

| GET    | `/course-purchases` | List all course purchases |
| GET    | `/course-purchases/{id}` | Show course purchases details |

| GET | `/reports` | Get course report|

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin routes here
});
```
---

## Database Tables

- `courses` - Stores courses details.
- `course_category` - Pivot table linking courses to category.  
- `course_tag` - Pivot table linking courses to tag.
- `course_sections` - Pivot table linking courses to sections.
- `lectures` - Stores lectures details.
- `course_purchases` - Pivot table linking courses to purchases.
- `course_transactions` - Pivot table linking courses to transactions.

---

## License

This package is open-sourced software licensed under the MIT license.