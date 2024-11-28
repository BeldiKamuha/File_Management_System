# File Management System - Backend

<p align="left">
  <a href="LICENSE"><img src="https://img.shields.io/github/license/BeldiKamuha/File_Management_System" alt="License"></a>
</p>

## Description

This is the backend system for the File Management System. The backend provides a RESTful API to manage files and directories, including creating, deleting, and listing files and directories. Files are stored in the local file system, and metadata is stored in a MySQL database.

## Features

•	RESTful API following REST standards.  
•	File Management: Upload, List, download, update, and delete files.  
•	Directory Management: Create, List, update, and delete directories.  
•	Nested Directories: Supports nested directories in a tree structure.  
•	Error Handling: Gracefully handles errors such as deleting non-existent files or directories that are not empty.  
•	Local File System Storage: Files and directories are stored on the local file system.  
•	Database Storage: Metadata stored in a MySQL database.  

## Technologies Used

• PHP (Laravel Framework)  
• MySQL Database  
• Composer for dependency management 

## Installation

### Prerequisites

#### PHP (version 8.0. or higher) 
• Installation on Windows:  
Download the latest PHP version from [PHP Downloads](https://windows.php.net/download/).    
 
#### Composer  
Download Composer from [Get Composer](https://getcomposer.org).
#### MySQL  
• Download MySQL from [MySQL Community Server](https://dev.mysql.com/downloads/installer/).

#### Apache server or XAMPP 
• Download XAMPP from [XAMPP Official Website](https://www.apachefriends.org/download.html).

#### Preferred IDE
• Visual Studio Code: [Download VS Code](https://code.visualstudio.com). 

#### Node.js and npm (for compiling frontend assets)
Download Node.js from [Node.js Official Website](https://nodejs.org/en).

#### Git
Git is required to clone and manage the project repository.  
• Download Git from [Git Official Website](https://git-scm.com).

## Steps

### 1. Clone the Repository
```
git clone https://github.com/BeldiKamuha/File_Management_System.git
```

### 2.	Navigate to the Project Directory
```
cd file-management-system-backend
 ```

### 3.	Install PHP Dependencies
```
composer install
```

### 4.	Copy the Environment File
```
cp .env.example .env
```

### 5.	Configure Environment Variables
Open .env and set your application and database configurations:
```
APP_NAME="File Management System"
APP_ENV=local
APP_KEY=base64:YourGeneratedAppKey
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:8080

```

### 6.	Generate Application Key
```
php artisan key:generate
```

### 7.	Run Database Migrations
```
php artisan migrate
```

### 8.	Create Storage Symlink
```
php artisan storage:link
```

### 9.	Set Directory Permissions
Ensure the storage and bootstrap/cache directories are writable by the web server:
```
chmod -R 775 storage bootstrap/cache
```

## Usage

Running the Development Server
```
php artisan serve
```
• The application will be accessible at http://localhost:8000  

## API Endpoints

### Files

• GET /api/files/ - List all files in the system.  
• GET /api/files/{id} - Get details of a specific file.  
• POST /api/files/ - Upload a new file.  
• PUT /api/files/{id} - Update a file.  
• DELETE /api/files/{id} - Delete a file.  
• GET /api/files/{id}/download - Download a file.  

### Directories

• GET /api/directories/ - List all directories in the system.  
• GET /api/directories/{id}/sub-directories - Get sub-directories of a directory.  
• GET /api/directories/{id}/files - Get files in a directory.  
• POST /api/directories/ - Create a new directory.  
• PUT /api/directories/{id} - Update a directory.  
• DELETE /api/directories/{id} - Delete a directory.  

## Testing the API

• You can use tools like Postman or cURL to test the API endpoints.   
• Ensure you include necessary headers, such as Content-Type: application/json for POST and PUT requests.   

example using cURL to Get files in a directory
```
curl -G 'http://localhost:8000/api/files' --data-urlencode 'directory_id=id'
```

## Error Handling

• The API returns appropriate HTTP status codes:  
• 200 OK for successful requests.  
• 201 Created for successful resource creation.  
• 400 Bad Request for invalid requests.  
• 404 Not Found when resources are not found.  
• 500 Internal Server Error for server-side errors.  
• Validation errors return a 422 Unprocessable Entity status with error details.  


## Database Schema
The database schema consists of two main tables: directories and files.  

To see the nested directories using a tree structure from the database. For example, the following structure:  
```
root
├── dir1
│   ├── dir2
│   │   ├── file1
│   │   └── file2
│   └── file4
└── file4
```

You can run:
```
php artisan directory:tree
```

## File Storage

• Files are stored in storage/app/public/files.  
• The application uses Laravel’s default filesystem configuration.  
• Ensure the storage symlink is created (php artisan storage:link).  

## CORS Configuration

To allow the frontend application to communicate with the backend API, configure CORS settings.

### Update config/cors.php:
```
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_methods' => ['*'],

'allowed_origins' => ['http://localhost:8080'],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => false,
```
## Database dump setup
Instructions on how to use the database dump file to set up the database

### 1. Import the database dump file into your local MySQL database:
bash
```
mysql -u [username] -p [database_name] < database_dump/database_dump.sql
```
Replace:  
• [username] with your MySQL username.  
• [database_name] with the name of the database you want to create.  

### 2. Ensure your .env file has the correct database credentials:  
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[database_name]
DB_USERNAME=[username]
DB_PASSWORD=[password]
```
### 3.	Run migrations to update or seed additional data (if necessary):
```
php artisan migrate --seed
```



   
## License
File Management System is completely free and released under the [MIT license](https://opensource.org/licenses/MIT).
