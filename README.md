# Forum API
> This is the forum API. It has been built using Laravel.

## Introduction
Forum API is a delightfully simple discussion api for your website. It has been built using Laravel.

## Installation

### 1. Clone the repository
```bash
git clone https://github.com/outama-othmane/forum_api.git
```

### 2. Create an .env file
```bash
composer run post-root-package-install  
```

### 3. Generate APP_KEY
```bash
php artisan key:generate
```

### 4. Update the .env file
Update the .env lignes with your information. Ex. enter your database crendtiels.

### 5. Push the migrations to your database
This command will create the necessary tables in your database in order to run the application.
```bash
php artisan migrate 
``` 

### 6. Serve the API in your localhost
```bash
php artisan serve 
``` 

## Available Routes
Check the **routes/api.php** file.

## Contributing
Thank you for considering contributing to **forum_api**.

All you have to do is:

- Fork the repository
- Push your updates to your forked repo
- Make a pull request

## License
Forum API is open-source software licensed under the **MIT License**.