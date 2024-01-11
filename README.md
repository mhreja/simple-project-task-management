# A Simple Project/Task/Assignment Management System

Welcome to the Simple Project/Task/Assignment Management System repository!

## Getting Started

To get started with this project, follow these steps:

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/mhreja/simple-project-task-management.git

2. Install Packages
   ```bash
   composer install
3. Create env file
   ```bash
   cp .env-example .env
4. Generate App Key
   ```bash
   php artisan key:generate
5. Set App Url, Change the database keys and Mail settings accordingly
6. Migrate and Seed Admin User
   ```bash
   php artisan migrate --seed
7. Now ready to go
   ```bash
   php artisan serve
