# 🏋️‍♂️ Gym Management System

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-success.svg)]()

## 📋 Overview

A comprehensive **Gym Management System** built with PHP that streamlines gym operations, member management, trainer scheduling, and payment processing. This system provides an intuitive interface for gym administrators to efficiently manage all aspects of their fitness facility.

## ✨ Key Features

### 👥 **Member Management**
- **Member Registration**: Easy onboarding process for new gym members
- **Member Profiles**: Detailed member information and fitness tracking
- **Membership Plans**: Flexible membership options and renewals
- **Member Directory**: Quick search and filter capabilities

### 🏃‍♂️ **Trainer Management**
- **Trainer Profiles**: Comprehensive trainer information and certifications
- **Schedule Management**: Training session booking and availability
- **Performance Tracking**: Monitor trainer effectiveness and member satisfaction

### 💰 **Payment System**
- **Payment Processing**: Secure payment handling for memberships and services
- **Payment History**: Complete transaction records and receipts
- **Automated Billing**: Recurring payment management
- **Financial Reports**: Revenue tracking and analytics

### 📊 **Session Management**
- **Class Scheduling**: Group fitness classes and personal training sessions
- **Attendance Tracking**: Monitor member participation and engagement
- **Session Reports**: Detailed analytics on class popularity and attendance

### 🛠️ **Equipment Management**
- **Equipment Registry**: Complete inventory of gym equipment
- **Maintenance Tracking**: Schedule and monitor equipment servicing
- **Usage Analytics**: Track equipment utilization and performance

### 📈 **Admin Dashboard**
- **Real-time Analytics**: Key performance indicators and metrics
- **Member Statistics**: Growth trends and membership insights
- **Revenue Reports**: Financial performance and forecasting
- **System Overview**: Quick access to all management functions

## 🚀 Getting Started

### Prerequisites

Before you begin, ensure you have the following installed:
- **PHP 7.4 or higher**
- **MySQL 5.7 or higher**
- **Apache/Nginx Web Server**
- **Composer** (for dependency management)

### Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/gym-management-system.git
   cd gym-management-system
   ```

2. **Database Setup**
   - Create a new MySQL database for the gym management system
   - Import the database schema (if provided)
   - Update database configuration in your config files

3. **Configure Environment**
   - Copy `.env.example` to `.env`
   - Update database credentials and other configuration settings
   - Set up proper file permissions

4. **Install Dependencies**
   ```bash
   composer install
   ```

5. **Launch the Application**
   - Point your web server to the project directory
   - Access the system through your browser
   - Use the admin credentials to log in

## 📁 Project Structure

```
gym-management-system/
├── 📄 admin_login.php          # Administrator authentication
├── 📄 admin_profile.php        # Admin profile management
├── 📄 dashboard_admin.html     # Admin dashboard interface
├── 📄 dashboard_admin.php      # Admin dashboard logic
├── 📊 Member Management/
│   ├── 📄 add_new_member.php   # New member registration
│   ├── 📄 edit_member.php      # Member profile editing
│   └── 📄 delete_member.php    # Member removal
├── 👨‍🏫 Trainer Management/
│   ├── 📄 add_new_trainer.php  # Trainer registration
│   ├── 📄 edit_trainer.php     # Trainer profile editing
│   └── 📄 delete_trainer.php   # Trainer removal
├── 🏃‍♂️ Session Management/
│   ├── 📄 add_session.php      # Create training sessions
│   ├── 📄 edit_session.php     # Modify sessions
│   ├── 📄 delete_session.php   # Remove sessions
│   └── 📄 book_training.php    # Session booking
├── 🛠️ Equipment/
│   ├── 📄 add_equipment.php    # Equipment registration
│   ├── 📄 edit_equipment.php   # Equipment updates
│   └── 📄 delete_equipment.php # Equipment removal
├── 💳 Payments/
│   └── 📄 add_payment.php      # Payment processing
└── 📊 Attendance/
    └── 📄 attendance.php       # Attendance tracking
```

## 🔧 Configuration

### Database Configuration
Update your database connection settings:
```php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'gym_management_db';
```

### Security Settings
- Change default admin credentials
- Update password hashing settings
- Configure session security
- Set up proper file permissions

## 📱 Usage Guide

### For Administrators

1. **Login**: Access the admin panel using your credentials
2. **Dashboard**: Get an overview of gym operations and key metrics
3. **Member Management**: Add, edit, or remove gym members
4. **Trainer Management**: Manage trainer profiles and schedules
5. **Equipment Tracking**: Monitor and maintain gym equipment
6. **Payment Processing**: Handle member payments and billing
7. **Reports**: Generate analytics and performance reports

### For Members

1. **Profile Management**: Update personal information and preferences
2. **Session Booking**: Reserve training sessions and classes
3. **Payment History**: View payment records and membership status
4. **Attendance Tracking**: Monitor workout frequency and progress

## 🛡️ Security Features

- **Secure Authentication**: Protected login system with session management
- **Data Validation**: Input sanitization and validation
- **Password Security**: Encrypted password storage
- **Access Control**: Role-based permissions and restrictions
- **SQL Injection Protection**: Prepared statements and parameter binding

## 🤝 Contributing

We welcome contributions to improve the Gym Management System! Here's how you can help:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### Development Guidelines

- Follow PHP coding standards (PSR-12)
- Write clear, commented code
- Test your changes thoroughly
- Update documentation as needed

## 📧 Support

For support, bug reports, or feature requests:

- **Email**: deelakaherath113@gmail.com
- **GitHub Issues**: [Create an issue](https://github.com/yourusername/gym-management-system/issues)
- **Documentation**: Check our [Wiki](https://github.com/yourusername/gym-management-system/wiki)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Thanks to all contributors who helped build this system
- Special recognition to the PHP and MySQL communities
- Inspiration from modern gym management needs

## 🔄 Version History

- **v1.0.0** - Initial release with core functionality
- **v1.1.0** - Added payment processing and reporting features
- **v1.2.0** - Enhanced security and user interface improvements

---

<div align="center">

**Made with ❤️ for the fitness community**

[⬆ Back to Top](#gym-management-system)

</div>
