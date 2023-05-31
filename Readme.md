# Cabie - Ride share app built with Laravel & Vue

Begining with the Backend first. First thing that we got to do, is determine what Models are going to be used in this application.
We are not using *email-verification* for this project. Also, instead of **email** we'll be using **phone number** to authenticate a user. Instead of **password**, since we are using phone number, we change the "password" attribute in `users` table into **login_code** (which would ideally be the OTP).

On visiting the app, it will ask if the user wants to hail a ride as a **passenger** or wants to ride as a **driver**. We make a separate `driver` table to save this driver related information with a foreign key linking to the `users`table.

```bash
php artisan make:model Driver --migration
```

Each time a passenger requests a ride somewhere, we need to save this information in the database. We'll call this model `trip`. There will be multiple trips associated with a passenger or a driver.

Install `twilio` to help with the OTP functionality.

```bash
composer require laravel-notification-channels/twilio
```

The Twilio package apparently doesn't work for Indian numbers. For now, we'll directly look up the database to get the generated Login_code (OTP) and pass this as params in the `login/verify` API.

## Making use of the generated `Auth` token

We'll define a route to fetch the details of the authenticated user.
In order to get the `user()` object from the `$request->user()` we'll need to add the middleware, `auth:sanctum` into the request and we need to provide it with the **Authorization header** containing the `token` we generated earlier using `createToken()` method inside `verify@LoginController`.

Now, we are going to have multiple routes associated with the *Auth token*. So instead of tacking on the `->middleware('auth:sanctum')` to each of them routes, we define a **Route Group** that will group all the required routes inside this `auth:sanctum` middleware.

```bash
Route::group(['middleawre' => 'auth:sanctum'], function() {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });
});
```

## Driver related functionality

When a user is logged in, the app will ask if they want to hail a ride or work as a driver. We'll define two routes, one for checking if the user is an existing driver. If yes, we'll fetch their details, if no then other route will be used to save the user's driver-related details.

## Reference

- [Build A Ride Share App with Laravel and Vue | Full Stack Application Tutorial](https://www.youtube.com/watch?v=iFOEU6YNBzw)
- [Laravel Notification Channels - Twilio](https://laravel-notification-channels.com/twilio/)
- [Twilio - Tool to send/receive text messages](https://www.twilio.com/en-us)
