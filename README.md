# EasyClean - User Management Corebase

This repository contains the core user management functionality for the EasyClean platform, built with CodeIgniter 3 and Bootstrap 5.

## 🎯 Purpose

This corebase provides a complete user management system that can be extended for various projects. It includes:

- User registration and authentication
- Admin panel with user management
- User approval/rejection workflow
- Modern, responsive UI design
- Role-based access control

## 🚀 Features

### User Management
- ✅ **User Registration** with role selection (Cleaner/Host)
- ✅ **User Authentication** with secure login
- ✅ **User Approval System** - New users are auto-banned until admin approval
- ✅ **User Rejection System** - Track and manage rejected users
- ✅ **User Restoration** - Restore rejected users if needed

### Admin Panel
- ✅ **Modern Dashboard** with statistics and quick actions
- ✅ **User Management Table** with advanced filtering, sorting, and pagination
- ✅ **User Details View** with complete user information
- ✅ **User Creation Form** with comprehensive user data fields
- ✅ **User Editing** with validation and security
- ✅ **User Banning/Unbanning** functionality
- ✅ **User Deletion** with confirmation dialogs

### UI/UX
- ✅ **Modern Design** matching contemporary web standards
- ✅ **Responsive Layout** works on all devices
- ✅ **AdminLTE Integration** for professional admin interface
- ✅ **Bootstrap 5** for modern components
- ✅ **FontAwesome 6** for consistent icons

## 🏗️ Architecture

### User Levels
- **Cleaner** (Level 3) - Service providers
- **Host** (Level 6) - Service requesters  
- **Administrator** (Level 9) - System administrators

### Database Schema
- Users table with comprehensive fields
- Pending verification system
- Rejection tracking system
- Audit trail for user actions

## 📁 Project Structure

```
application/
├── controllers/
│   ├── Admin.php          # Admin panel controller
│   ├── App.php            # Main app controller (login/register)
│   └── Examples.php       # Example controller
├── models/
│   ├── M_users.php        # User management model
│   └── User.php           # Base user model
├── views/
│   ├── admin/
│   │   ├── users/         # User management views
│   │   └── template/      # Admin layout templates
│   └── app/               # Login/register views
└── config/
    └── authentication.php # User roles and permissions
```

## 🔧 Installation

1. Clone the repository
2. Set up your database and update `application/config/database.php`
3. Run the SQL migrations to create the users table with all fields
4. Configure your web server to point to the project directory
5. Access the admin panel at `/admin` (default credentials needed)

## 🎨 UI Components

### Login Page
- Modern gradient design
- Smooth animations
- Success/error messaging
- Responsive layout

### Admin Dashboard
- Statistics cards with gradients
- Quick action buttons
- Pending users alerts
- Modern card-based layout

### User Management
- Advanced filtering system
- Sortable columns
- Pagination
- Bulk operations
- Modern table design

## 🔒 Security Features

- Password hashing with bcrypt
- Session management
- CSRF protection
- Input validation
- SQL injection prevention
- Role-based access control

## 📱 Responsive Design

- Mobile-first approach
- Tablet optimization
- Desktop enhancement
- Touch-friendly interfaces
- Adaptive layouts

## 🚀 Future Extensions

This corebase is designed to be extended for specific projects while maintaining the core user management functionality. The modular structure allows for:

- Additional user roles
- Extended user profiles
- Integration with other systems
- Custom business logic
- Additional admin features

## 📝 License

This corebase is provided as a foundation for user management systems. Customize and extend as needed for your specific project requirements.

---

**Note**: This is a corebase repository designed to maintain the essential user management functionality. When branching to specific projects, create new repositories that extend this corebase with project-specific features.
