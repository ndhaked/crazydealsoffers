Crazy Deals & Offers
## Getting Started

Deal Listing APP is a admin panel


### Prerequisites

Application is built with Laravel 8.0 as php framework and MySQL database is used for db operation.

### Installing

A step by step series of examples that tell you how to get a development env running.
- First clone this project
- Run composer update
- Set configuration in .env file like database setting and your app url
- Run php artisan migrate
- Run php artisan module:migrate
- Run dump autoload
- Run php artisan serve
- Open http://127.0.0.1:8000

### Permission Seeder Migration (Run Following)
- php artisan db:seed --class=CreateAdminUserSeeder
- php artisan module:seed --class=DashboardDatabaseSeeder Dashboard
- php artisan module:seed --class=ConfigurationDatabaseSeeder Configuration
- php artisan module:seed --class=EmailTemplatesPermissionSeederTableSeeder EmailTemplates
- php artisan module:seed --class=RolesDatabaseSeeder Roles
- php artisan module:seed --class=PermissionsDatabaseSeeder Permissions
- php artisan module:seed --class=StaticPagesDatabaseSeeder StaticPages
- php artisan module:seed --class=UsersDatabaseSeeder Users
- php artisan module:seed --class=CategoriesDatabaseSeeder Categories
- php artisan module:seed --class=FaqDatabaseSeeder Faq
- php artisan module:seed --class=ProductsDatabaseSeeder Products
- php artisan module:seed --class=NotificationsDatabaseSeeder Notifications
- php artisan module:seed --class=BlogsDatabaseSeeder Blogs
- php artisan module:seed --class=AdvertisementsDatabaseSeeder Advertisements
- php artisan module:seed --class=StatisticsDatabaseSeeder Statistics
- php artisan module:seed --class=CreareCommentsSeederTableSeeder Products



### Using OOPS Pattern 
- Laravel Repository Pattern
- Laravel Model Observers
- Laravel Email Queues / Jobs
- Laravel View Composer
- Laravel Modules
- Laravel Eloquent: Relationships
- Laravel Migrations & Seeding