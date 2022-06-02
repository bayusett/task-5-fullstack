## Virtual Internship Experience (Investree) - Fullstack - Bayu Setyadji
## Local installation

## your PHP version must be 8.0 or above

1. Clone the github repo:

    ```bash
    git clone https://github.com/bayusett/task-5-fullstack.git
    ```

2. Go the project directory:

    ```bash
    cd task-5-fullstack
    ```

3. Install the project dependencies:
    ```bash
    composer install
    ```
4. Copy the .env.example to .env or simly rename it:
   </br>If linux:
    ```bash
    cp .env.example .env
    ```
    If Windows:
    ```bash
    copy .env.example .env
    ```
5. Run XAMPP and create an empty Database named task-5-fullstack
6. Create tables into database using Laravel migration and seeder:
    ```bash
    php artisan migrate:fresh --seed
    ```
7. Create the application key:
    ```bash
    php artisan key:generate
    ```
8. Create the encryption keys needed to generate secure access tokens:
    ```bash
    php artisan passport:install
    ```
9. Start the laravel server:
    ```bash
    php artisan serve
    ```
    If css/js doesn't work:
    ```bash
    php -S localhost:8000 -t public
    ```
