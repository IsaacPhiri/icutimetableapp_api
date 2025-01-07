# ICUTimetableApp

## Getting Started

### Navigate to the project directory:
```bash
cd /c:/wamp64/www/icutimetableapp
```

### Install Composer dependencies:
```bash
composer install
```

### Create a .env file in the root directory and add your environment variables:
```bash
cp .env.example .env
```
Edit the `.env` file and update your environment variables.

### Import the database schema:
```bash
php artisan migrate
```

### Start your local server (e.g., WAMP, XAMPP).

## Usage

### Access the application in your web browser:
Navigate to `http://localhost/icutimetableapp`.

### Use the API endpoints to manage courses, programs, and timetables.

## API Endpoints

### Courses
- `GET /courses`: Retrieve all courses.
- `GET /courses/{id}`: Retrieve a specific course by ID.
- `POST /courses`: Create a new course.
- `PUT /courses/{id}`: Update a specific course by ID.
- `DELETE /courses/{id}`: Delete a specific course by ID.

### Programs
- `GET /programs`: Retrieve all programs.
- `GET /programs/{id}`: Retrieve a specific program by ID.
- `POST /programs`: Create a new program.
- `PUT /programs/{id}`: Update a specific program by ID.
- `DELETE /programs/{id}`: Delete a specific program by ID.

### Timetable
- `GET /timetable`: Retrieve all timetable entries.
- `GET /timetable/{id}`: Retrieve a specific timetable entry by ID.
- `POST /timetable`: Create a new timetable entry.
- `PUT /timetable/{id}`: Update a specific timetable entry by ID.
- `DELETE /timetable/{id}`: Delete a specific timetable entry by ID.

## Database Schema

### Users Table
| Column     | Type    | Description                |
|------------|---------|----------------------------|
| user_id    | INT     | Primary key                |
| first_name | VARCHAR | User's first name          |
| last_name  | VARCHAR | User's last name           |
| role       | ENUM    | User's role (admin, student, lecturer) |

### Courses Table
| Column      | Type    | Description                |
|-------------|---------|----------------------------|
| course_id   | INT     | Primary key                |
| course_name | VARCHAR | Name of the course         |
| program_id  | INT     | Foreign key to Programs table |

### Programs Table
| Column       | Type    | Description                |
|--------------|---------|----------------------------|
| program_id   | INT     | Primary key                |
| program_name | VARCHAR | Name of the program        |
| description  | TEXT    | Description of the program |

### Timetable Table
| Column       | Type    | Description                |
|--------------|---------|----------------------------|
| timetable_id | INT     | Primary key                |
| course_id    | INT     | Foreign key to Courses table |
| day          | VARCHAR | Day of the timetable entry |
| time         | TIME    | Time of the timetable entry |
| room         | VARCHAR | Room of the timetable entry |
| user_id      | INT     | Foreign key to Users table |

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add some feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## License

This project is licensed under the MIT License.