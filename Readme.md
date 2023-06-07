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

## Backend

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

When a user is logged in, the app will ask if they want be a passenger or a driver. We'll define two routes, one for checking if the user is an existing driver. If yes, we'll fetch their details, if no then other route will be used to save the user's driver-related details.

## Trip related functionality

After a selects that they want to be a passenger, they'll have to put in information about where they want to go and where they currently are. So we'll create a route (`store` method in `TripController`) to save this info in the database.
We also define a route to `show` method which will fetch the trips associated with the currently authenticated user.
We also need to define a route in order to adjust the trip from user (passenger) asthey proceed through the different screens in our frontend app. So we'll need a route for,

- when a driver **accepts** the trip
- when a driver **starts** the trip
- when a driver **ends** the trip
- update the driver's location

## Adding `Websockets` events

The last thing that we should do for the backend before it's fully functional, is push out `events` for some of these API endpoints.
To do this, we'll have to dispatch `Event` classes for each of the events that we need to push down.

```bash
php artisan make:event TripAccepted
php artisan make:event TripStarted
php artisan make:event TripEnded
php artisan make:event TripLocationUpdated
```

After making the `Event` classes, we need to install a package to actually push these events out so that they can be consumed by our frontend. The two most common ones are: 

- soketi/soketi
- beyondcode/laravel-websockets

For this project, we are going with **laravel-websockets**.

```bash
composer require beyondcode/laravel-websockets

// this threw an error in my case, maybe try running the below code to install the package with dependencies
composer require beyondcode/laravel-websockets -W
```

See the [Docs](https://beyondco.de/docs/laravel-websockets/getting-started/installation) for the rest of the steps.
Refer to the **Basic Usage** section [here](https://beyondco.de/docs/laravel-websockets/basic-usage/pusher) to implement the laravel-websockets.

To make use of the Laravel WebSockets package in combination with Pusher, you first need to install the official Pusher PHP SDK.
If you are not yet familiar with the concept of Broadcasting in Laravel, please take a look at the [Laravel documentation](https://laravel.com/docs/6.0/broadcasting).

```bash
composer require pusher/pusher-php-server "~3.0"
// since my PHP version is 8.1.13, I face error here, so I ran the below command
composer require pusher/pusher-php-server
```

To start the `websockets server`, run: 

```bash
php artisan websockets:serve
```

## Frontend

Open a terminal inside the `frontend` folder and run below command to install Vue.

```bash
npm init vue@latest .

// teminal output
C:\GitHub\cabie\frontend> npm init vue@latest .
Need to install the following packages:
  create-vue@3.6.4
Ok to proceed? (y) y
npm WARN EBADENGINE Unsupported engine {
npm WARN EBADENGINE   package: 'create-vue@3.6.4',
npm WARN EBADENGINE   required: { node: '>=v16.20.0' },
npm WARN EBADENGINE   current: { node: 'v16.19.0', npm: '9.5.1' }
npm WARN EBADENGINE }

Vue.js - The Progressive JavaScript Framework

√ Package name: ... cabie
√ Add TypeScript? ... No
√ Add JSX Support? ... No
√ Add Vue Router for Single Page Application development? ... Yes
√ Add Pinia for state management? ... Yes
√ Add Vitest for Unit Testing? ... No
√ Add an End-to-End Testing Solution? » No
√ Add ESLint for code quality? ... No

Scaffolding project in C:\GitHub\cabie\frontend...

Done. Now run:

  npm install
  npm run dev
```

We'll be using Tailwind CSS in this project. Open a terminal inside `frontend` directory and run the below command to install Tailwind and some other dependencies:

```bash
// npm install --save-dev tailwindcss postcss autoprefixer
npm install --D tailwindcss postcss autoprefixer

// to initialize a Tailwind skeleton, run the below command
npx tailwindcss init -p
```

### [Maska](https://github.com/beholdr/maska) - Simple zero-dependency input mask for Vue.js and vanilla JS

```bash
npm i -D maska

// install axios
npm install -D axios
```

### Adding a middleware/gaurd to Routes in Vue

`beforeEach()` method hooks into every navigation **to** and **from** a route in our application.

## Known Bugs

- When trying to login with digits less then 10, the backend validationis not happening. Eg. if I enter a 5 digit number then it is creating a user with the 5-digit number. This should not be happening.

## Reference

- [Build A Ride Share App with Laravel and Vue | Full Stack Application Tutorial](https://www.youtube.com/watch?v=iFOEU6YNBzw)
- [Laravel Notification Channels - Twilio](https://laravel-notification-channels.com/twilio/)
- [Twilio - Tool to send/receive text messages](https://www.twilio.com/en-us)
- [Laravel Websockets - Github](https://github.com/beyondcode/laravel-websockets)
- [Laravel Websockets Docs](https://beyondco.de/docs/laravel-websockets/getting-started/introduction)
