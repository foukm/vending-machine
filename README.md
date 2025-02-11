Laravel framework used to create this project called "Vending Machine"

- Requirements
  - Laravel 11
  - php 8.3+
  - mySql

- Url developed in localhost is http://127.0.0.1:8000/ (after php artisan serve). Change .env according to the setup environment.

-  Instructions
  - create database with name vending-machine
  - create user with all privilleges for database with username vm-mysql and password empty
  - run command php artisan migrate to create tables in database 
  - run php artisan serve to serve project

Database used is Mysql, database name "vending-machine" with db_user: vm-mysql and db_password empty. Port is 3306. This configurations can be changed through .env file    
Used validation rules and strip_tags  function to prevent possible false or dangerous data in DB.

- Used Sanctum authorization system, which is a laravel built-in cookie based session authentication
- Sanctum token doesn't expire. (This can be edited from config/sanctum.php)
- All api calls use a Bearer Token as authentication, which is available after login
- User can login only if there is not active session, else a warning message is displayed

- CRUD for users
  - register is open to public
  - RUD works only for self
  - user can edit only username and password, not role
  - added logout/all api call for logging out from all active sessions the specified user(bonus)
  
- CRUD for products
  - show all products is open to public
  - RUD work only for specific seller added the product

- Buy Product
  - The buyer can deposit as many times as he wants before buying, amount of [5,10,20,50,100]
  - Then the buyer can select the item he wants to buy and the amount of the items    
  - After the transaction, the buyer sees a messages for successfull transaction and the change he takes back from the total deposit.
  - Then the deposit resets to 0 and then the buyer has to start all over the process to buy other products.

- Add Product
  - The seller sees a list of all the products added by him/her     
  - The seller can add a new product with the specified information needed
  - The seller can edit or delete a product that he/her is the seller.
    
