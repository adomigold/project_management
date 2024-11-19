# **Project Management System**

A Laravel-based Project Management System designed to manage users, projects, and tasks with robust authentication, role-based access control, and RESTful APIs. The system provides an efficient way to handle project assignments and monitor task progress.

---

## **Features**
1. **Authentication and Authorization**:
   - JWT-based authentication using Laravel Sanctum.
   - Role-based access control with roles: Admin, Manager, and User.

2. **User Management**:
   - Admins can create, update, delete, and assign roles to users.

3. **Project Management**:
   - Create, view, update, and delete projects.
   - Only Managers and Admins can manage projects.

4. **Task Management**:
   - Create tasks under projects, assign them to users, and update their status.
   - Managers can create and assign tasks, while users can update their assigned tasks.

5. **Dashboard API**:
   - Provides a summary of total projects, tasks (grouped by status), and users.

6. **Notifications**:
   - Email notifications when:
     - A new task is assigned to a user.
     - A project is marked as completed.

7. **Bonus Features**:
   - Search and filter tasks by name and status.
   - Caching for frequently accessed data (Dashboard API).

---

## **Installation and Setup**

### Prerequisites
- PHP >= 8.0
- Composer
- PostgreSQL or any other supported database
- Mail server or service

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/adomigold/project_management.git
   cd project_management
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment variables:
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database and mail server credentials.

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

7. (Optional) Start the queue worker for email notifications:
   ```bash
   php artisan queue:work
   ```

---

## **API Documentation**

### Authentication Endpoints
- **Register**: `POST /api/register`
- **Login**: `POST /api/login`
- **Logout**: `POST /api/logout`

### Roles and Permissions Endpoints (Admin only)
- **View All Roles**: `GET /api/roles`
- **Create Role**: `POST /api/roles`
- **Update Role**: `PUT /api/roles/{id}`
- **Delete Role**: `DELETE /api/roles/{id}`
- **Assign Permission**: `PATCH /api/roles/{id}/assign-permissions`
- **Remove Permission**: `PATCH /api/roles/{id}/remove-permissions`

### User Management Endpoints (Admin only)
- **Create User**: `POST /api/users`
- **Update User**: `PUT /api/users/{id}`
- **Delete User**: `DELETE /api/users/{id}`
- **Assign Role**: `PATCH /api/users/{id}/assign-role`

### Project Management Endpoints
- **Create Project**: `POST /api/projects`
- **View All Projects**: `GET /api/projects`
- **View Single Project**: `GET /api/projects/{id}`
- **Update Project**: `PUT /api/projects/{id}`
- **Delete Project**: `DELETE /api/projects/{id}`

### Task Management Endpoints
- **Create Task**: `POST /api/projects/{id}/tasks`
- **View Tasks in Project**: `GET /api/projects/{id}/tasks`
- **Update Task**: `PUT /api/tasks/{id}`
- **Mark Task as Completed**: `PATCH /api/tasks/{id}/status`

### Dashboard Endpoint
- **Summary**: `GET /api/dashboard`

---

## **Roles and Permissions**

| Role   | Permissions                                                                 |
|--------|-----------------------------------------------------------------------------|
| Admin  | Full access to all resources.                                               |
| Manager| Create and manage projects, assign and manage tasks within their projects. |
| User   | View and update tasks assigned to them.                                     |

---

## **Notifications**
- **Task Assignment**:
  - Sent to a user when they are assigned a new task.
- **Project Completion**:
  - Sent to the project manager when a project is marked as completed.

---

## **External Libraries and Tools**
1. **Spatie Laravel Permission**: Role and permission management.
2. **Laravel Sanctum**: JWT-based authentication.
3. **Laravel Queue**: For email notifications.
4. **Google Mail**: For testing email notifications.
5. **Redis (Optional)**: For caching.

---

## **Testing**

### Automated Tests
Run automated tests to verify the application:
```bash
php artisan test
```

### Manual Testing
Use tools like Postman to test the APIs:
2. Test each endpoint using appropriate request bodies and headers.

---

## **Known Issues**
1. Notifications may be delayed if the queue worker is not running.
2. Search and filter functionalities may require optimization for large datasets.

---