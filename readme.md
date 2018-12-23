This project was made by laravel, so by following those steps everything should work as expected:

1. Clone this project to your own machine
2. Run "npm install" in the root directory
3. Run "composer install" in the root directory
4. Run "php artisan jwt:secret" in the root directory to generate your own JWT secret
5. Create a MySql database called "wisecredit" and a MySql username called "wisecredit" with password "1a2b3c4d" (you may change the username/password as you like but it must be changed in the .env file in the root directory as well)
6. Run "php artisan migrate" in the root directory to generate the project's database tables
7. Now we can serve the application by running "php artisan serve" in the root directory and the laravel project should be available by accessing the URL that was outputted by this command.
8. All endpoints are available in a JSON file attached to the root directory in the project, named "wisecredit.postman_collection.json" and should be imported to postman.
9. Tests are available by running "phpunit" in the root directory
10. If there are any issues contact me at any time.

The entities contain the following fields: id, user_id, name, created_at and updated_at. The only relevant field for this task is the name. The user_id will be provided internally, as long as the auth token will be in the header

The auth token will be provided in the "token" route (in case email and password are matching a user) and in the "register" route for new users.
With every entity request, along with the "account" request, the user must pass the token in the header with this syntax:
- header name - "Authorization"
- header body - "Bearer \<put token here\>"
