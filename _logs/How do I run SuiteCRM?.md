⏺ How to Run SuiteCRM Locally

  SuiteCRM is a traditional PHP web application that requires a LAMP/WAMP/MAMP stack. Here are your options:

  Option 1: Local PHP Development Server (Quickest for Development)

  # 1. Install PHP 7.4+ and required extensions
  # On macOS with Homebrew:
  brew install php@7.4 mysql

  # 2. Clone the repository
  cd /Users/trevor/code/gauntletai-p6/SuiteCRM

  # 3. Install dependencies
  composer install

  # 4. Start MySQL
  brew services start mysql

  # 5. Create database
  mysql -u root -e "CREATE DATABASE suitecrm;"

  # 6. Run PHP built-in server
  php -S localhost:8000

  # 7. Navigate to http://localhost:8000/install.php

  Option 2: Full Local Stack (MAMP/XAMPP/LAMP)

  For macOS:
  - MAMP (easiest): Download from mamp.info
  - Place SuiteCRM in /Applications/MAMP/htdocs/suitecrm/
  - Access via http://localhost:8888/suitecrm/install.php

  For Windows:
  - XAMPP: Download from apachefriends.org
  - Place SuiteCRM in C:\xampp\htdocs\suitecrm\
  - Access via http://localhost/suitecrm/install.php

  For Linux:
  # Install LAMP stack
  sudo apt-get install apache2 mysql-server php7.4 php7.4-mysql php7.4-curl php7.4-gd php7.4-json php7.4-zip php7.4-mbstring php7.4-xml

  # Enable Apache modules
  sudo a2enmod rewrite

  # Place SuiteCRM in web root
  sudo cp -r /path/to/SuiteCRM /var/www/html/suitecrm

  # Set permissions
  sudo chown -R www-data:www-data /var/www/html/suitecrm
  sudo chmod -R 755 /var/www/html/suitecrm
  sudo chmod -R 775 /var/www/html/suitecrm/cache /var/www/html/suitecrm/custom /var/www/html/suitecrm/modules /var/www/html/suitecrm/upload

  Option 3: Docker (Though not included, you can containerize it)

  Create a docker-compose.yml:

  version: '3'
  services:
    suitecrm:
      image: php:7.4-apache
      volumes:
        - .:/var/www/html
      ports:
        - "8080:80"
      environment:
        - APACHE_DOCUMENT_ROOT=/var/www/html
      depends_on:
        - mysql
      command: >
        bash -c "docker-php-ext-install mysqli pdo pdo_mysql &&
                 docker-php-ext-install gd &&
                 docker-php-ext-install zip &&
                 a2enmod rewrite &&
                 apache2-foreground"

    mysql:
      image: mysql:5.7
      environment:
        MYSQL_ROOT_PASSWORD: root
        MYSQL_DATABASE: suitecrm
      ports:
        - "3306:3306"
      volumes:
        - mysql_data:/var/lib/mysql

  volumes:
    mysql_data:

  System Requirements Summary

  Required:
  - PHP 7.4+ (8.0+ compatible)
  - MySQL 5.6+ or MariaDB
  - Web server (Apache/Nginx)
  - PHP Extensions: curl, gd, json, openssl, zip, mbstring, xml

  Recommended:
  - PHP memory_limit: 256M
  - PHP max_execution_time: 300
  - PHP upload_max_filesize: 20M

  Installation Process

  1. Access the installer: Navigate to http://localhost:PORT/install.php
  2. Follow the wizard:
    - Accept license
    - System check (will verify all requirements)
    - Database configuration
    - Site configuration
    - Admin user setup
  3. Post-installation:
    - Delete the install/ directory for security
    - Set up cron job for schedulers:
    * * * * * cd /path/to/suitecrm; php -f cron.php > /dev/null 2>&1

  Quick Start Commands

  # Check PHP version and extensions
  php -v
  php -m

  # Start MySQL
  mysql.server start  # macOS
  sudo service mysql start  # Linux

  # Create database
  mysql -u root -p -e "CREATE DATABASE suitecrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

  # Run built-in PHP server (for development only)
  php -S localhost:8000

  # Access installer
  open http://localhost:8000/install.php

  The easiest approach for local development is using the PHP built-in server with a local MySQL installation. For production-like environments, use a
   proper web server stack or containerize it.