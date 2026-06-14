# Slider CRUD Backend

Plain PHP 8+, MySQL, PDO, Bootstrap 5, and jQuery CRUD backend for managing slider categories and slides.

## Features

- Categories CRUD
- Slides CRUD with category selection, title, description, image, and display order
- Image uploads to `uploads/`
- MIME validation for JPG, JPEG, PNG, and WEBP
- PDO prepared statements
- Escaped admin output
- JSON APIs for frontend slider consumption

## Setup

1. Copy the project to your web root, for example:

   ```text
   C:\xampp\htdocs\CRUD
   ```

2. Create the database and tables:

   ```bash
   mysql -u root < sql/schema.sql
   ```

   You can also import `sql/schema.sql` in phpMyAdmin.

3. Update database credentials in `config/database.php` if needed:

   ```php
   const DB_HOST = '127.0.0.1';
   const DB_NAME = 'slider_crud';
   const DB_USER = 'root';
   const DB_PASS = 'root';
   ```

4. Ensure `uploads/` is writable by PHP.

5. Open the admin:

   ```text
   http://localhost/CRUD/
   ```

## Admin Pages

- Categories: `admin/categories/index.php`
- Create category: `admin/categories/create.php`
- Edit category: `admin/categories/edit.php?id=1`
- Slides: `admin/slides/index.php`
- Create slide: `admin/slides/create.php`
- Edit slide: `admin/slides/edit.php?id=1`

## API Endpoints

### Categories

```text
GET /CRUD/api/categories.php
```

Example response:

```json
[
  {
    "id": 1,
    "name": "Technology"
  }
]
```

### Slides by Category

```text
GET /CRUD/api/slides.php?category_id=1
```

Example response:

```json
[
  {
    "id": 1,
    "title": "React",
    "description": "React Description",
    "image": "uploads/react.jpg",
    "display_order": 1
  }
]
```

### Full Slider Data

```text
GET /CRUD/api/slider-data.php
```

Example response:

```json
[
  {
    "category": {
      "id": 1,
      "name": "Technology"
    },
    "slides": []
  }
]
```

## File Structure

```text
project/
├── config/
│   └── database.php
├── uploads/
├── admin/
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   └── slides/
│       ├── index.php
│       ├── create.php
│       ├── edit.php
│       └── delete.php
├── api/
│   ├── categories.php
│   ├── slides.php
│   └── slider-data.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── functions.php
│   └── upload.php
├── sql/
│   └── schema.sql
├── index.php
├── style.css
└── README.md
```
