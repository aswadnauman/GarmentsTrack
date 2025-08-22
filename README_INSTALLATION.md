# GarmentsTrack ERP - Installation Guide

## System Requirements

- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.2+)
- **Web Server**: Apache or Nginx
- **Extensions**: PDO, PDO_MySQL

## Installation Methods

### Method 1: Web-based Installation (Recommended)

1. **Download/Clone the project** to your web server directory
2. **Set permissions** (if on Linux/Mac):
   ```bash
   chmod 755 -R /path/to/garmentstrack
   chmod 777 config/
   ```
3. **Access the installer** in your web browser:
   ```
   http://your-domain.com/garmentstrack/install.php
   ```
4. **Follow the installation wizard**:
   - Step 1: Configure database connection
   - Step 2: Create database tables
   - Step 3: Set up admin user
   - Step 4: Complete installation

5. **Security**: Delete or rename `install.php` after installation

### Method 2: Manual Installation

1. **Create MySQL Database**:
   ```sql
   CREATE DATABASE garments_erp;
   ```

2. **Import Database Schema**:
   ```bash
   mysql -u username -p garments_erp < database/schema.sql
   ```

3. **Configure Database Connection**:
   Edit `config/database.php` with your database credentials:
   ```php
   $host = 'localhost';
   $dbname = 'garments_erp';
   $username = 'your_username';
   $password = 'your_password';
   ```

4. **Set File Permissions** (Linux/Mac):
   ```bash
   chmod 755 -R /path/to/garmentstrack
   chmod 777 logs/ tmp/
   ```

## Default Login Credentials

After installation, use these credentials to login:
- **Username**: admin
- **Password**: admin123

**Important**: Change the default password immediately after first login!

## Directory Structure

```
garmentstrack/
├── api/                    # API endpoints
├── assets/                 # CSS, JS, images
├── config/                 # Configuration files
├── database/              # Database schema and migrations
├── includes/              # Common PHP includes
├── workers/               # Worker management module
├── orders/                # Order management module
├── inventory/             # Inventory management (to be created)
├── production/            # Production management (to be created)
├── payroll/              # Payroll management (to be created)
├── reports/              # Reports module (to be created)
├── samples/              # Sample management (to be created)
├── settings/             # System settings (to be created)
├── index.php             # Dashboard
├── login.php             # Login page
└── install.php           # Installation wizard
```

## Configuration

### Database Configuration
Located in `config/database.php` - automatically created during installation.

### Environment Variables
You can set environment-specific configurations by creating a `.env` file in the root directory.

## Features Included

### ✅ Completed Features
- User authentication and session management
- Dashboard with key metrics
- Worker management (add, view, list)
- Order management (create, view, list)
- Database schema for all modules
- Responsive UI with Bootstrap

### 🚧 In Development
- Inventory management
- Production planning and tracking
- Payroll processing
- Sample management
- Financial reporting
- Advanced user roles and permissions

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL service is running
   - Check database credentials in `config/database.php`
   - Ensure database user has proper permissions

2. **Permission Denied Errors**
   - Set proper file permissions (755 for directories, 644 for files)
   - Ensure web server can write to config/ directory

3. **Blank Page or PHP Errors**
   - Check PHP error logs
   - Ensure all required PHP extensions are installed
   - Verify PHP version compatibility (7.4+)

### Getting Help

1. Check the error logs in your web server
2. Verify all system requirements are met
3. Ensure database schema was imported correctly

## Security Considerations

1. **Remove install.php** after installation
2. **Change default passwords** immediately
3. **Set proper file permissions**
4. **Use HTTPS** in production
5. **Regular backups** of database and files
6. **Keep PHP and MySQL updated**

## Next Steps

After installation:

1. Login with admin credentials
2. Change default password
3. Add your workers and customers
4. Configure company settings
5. Start creating orders and managing production

## Development

To contribute to this project:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
