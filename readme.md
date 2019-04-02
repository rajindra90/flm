**Features**
****
1. Laravel 5.7
2. Laravel Dusk
2. Vue + VueRouter + Vuex + axios
3. Login, Register,Send friend request,Accept New Request,Delete Friends
4. Authentication with Passport
5. Bootstrap 4 

**Installation**
****
1. Clone this repository (or download and extract the .zip).
2. Run **`composer install`** from inside the project directory to download PHP dependencies.
3. Open **`.env`** file in your favorite text editor and set the database credentials.
4. Run npm install to download JS dependencies.
5. **`php artisan migrate`**  run this command to migrate the database. ( Make sure that you're inside the app's root directory ).
6. Install passport **`php artisan passport:install`**
7. Install NPM globally if you haven't installed that already.
8. After installing NPM globally , run **`npm install`** inside your webroot , it will download all the required dependencies.
9. Run **`npm run dev`** for compiling sass and js files.
10.  You are ready to launch!

**Technologies**
****
1.  Laravel 5.7 (**REST API** - Backend).
2.  Vuejs (FrentEnd)
3.  Laravel Dusk (Testing)