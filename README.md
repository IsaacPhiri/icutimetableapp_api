# ICU Timetable API

A RESTful API for managing university timetables, courses, programs, and users. Built with PHP.

## Features

- User Management (Students, Lecturers, Admins)
- Course Management
- Program Management
- Timetable Management
- Lecturer-Course Assignments
- Student Enrollments
- Role-based Access Control

## Prerequisites

- PHP 7.4+
- MySQL 5.7+
- Composer
- Web Server (Apache/Nginx)

## Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/icu-timetable-api.git
cd icu-timetable-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
```
Edit `.env` with your database credentials and other settings.

4. **Set up database**
- Create a MySQL database
- Import the schema from `database/schema.sql`

5. **Configure web server**
- Point your web server to the `public` directory
- Ensure `mod_rewrite` is enabled for Apache

## API Documentation

### Authentication

All endpoints require Bearer token authentication:
```
Authorization: Bearer <your_token>
```

### Base URL
```
http://your-domain.com/api/v1
```

### Endpoints Overview

#### Users
- `GET /users` - List all users
- `GET /users/{id}` - Get user details
- `POST /users` - Create user
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

#### Courses
- `GET /courses` - List all courses
- `GET /courses/{id}` - Get course details
- `POST /courses` - Create course
- `PUT /courses/{id}` - Update course
- `DELETE /courses/{id}` - Delete course

#### Programs
- `GET /programs` - List all programs
- `GET /programs/{id}` - Get program details
- `POST /programs` - Create program
- `PUT /programs/{id}` - Update program
- `DELETE /programs/{id}` - Delete program

#### Timetables
- `GET /timetable` - List all timetable entries
- `GET /timetable/{id}` - Get timetable entry
- `POST /timetable` - Create timetable entry
- `PUT /timetable/{id}` - Update timetable entry
- `DELETE /timetable/{id}` - Delete timetable entry

#### Special Endpoints
- `GET /student_timetable?user_id={id}` - Get student's timetable
- `GET /lecturer_timetable?user_id={id}` - Get lecturer's timetable
- `GET /assignedcourses?user_id={id}` - Get lecturer's assigned courses
- `GET /enrollments?user_id={id}` - Get student's enrolled courses

### Detailed API Documentation

#### Create User
```http
POST /users
Content-Type: application/json

{
    "username": "john_doe",
    "password": "secure_password",
    "email": "john@example.com",
    "first_name": "John",
    "last_name": "Doe",
    "role": "lecturer",
    "phone_number": "1234567890",
    "date_of_birth": "1990-01-01",
    "address": "123 Main St"
}
```

#### Create Course
```http
POST /courses
Content-Type: application/json

{
    "course_id": 1,
    "course_name": "Introduction to Programming",
    "program_id": 1
}
```

#### Create Timetable Entry
```http
POST /timetable
Content-Type: application/json

{
    "course_id": 1,
    "day": "Monday",
    "time": "09:00:00",
    "room": "Room 101",
    "user_id": 1
}
```

## Database Schema

### Users
| Column        | Type    | Description                |
|--------------|---------|----------------------------|
| user_id      | INT     | Primary key                |
| username     | VARCHAR | Unique username            |
| password     | VARCHAR | Hashed password            |
| email        | VARCHAR | User's email               |
| first_name   | VARCHAR | User's first name          |
| last_name    | VARCHAR | User's last name           |
| role         | ENUM    | admin/student/lecturer     |
| phone_number | VARCHAR | Contact number             |
| date_of_birth| DATE    | Birth date                 |
| address      | VARCHAR | Physical address           |

### Courses
| Column      | Type    | Description                |
|-------------|---------|----------------------------|
| course_id   | INT     | Primary key                |
| course_name | VARCHAR | Course name                |
| program_id  | INT     | Foreign key to Programs    |

### Programs
| Column       | Type    | Description                |
|--------------|---------|----------------------------|
| program_id   | INT     | Primary key                |
| program_name | VARCHAR | Program name               |
| description  | TEXT    | Program description        |

### Timetable
| Column       | Type    | Description                |
|--------------|---------|----------------------------|
| timetable_id | INT     | Primary key                |
| course_id    | INT     | Foreign key to Courses     |
| day          | VARCHAR | Day of the week            |
| time         | TIME    | Time slot                  |
| room         | VARCHAR | Room number/name           |
| user_id      | INT     | Foreign key to Users       |

## Error Handling

The API returns standard HTTP status codes:

- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Server Error

Error response format:
```json
{
    "status": 400,
    "message": "Error description",
    "error": "Detailed error message"
}
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, email support@example.com or open an issue in the repository.
```