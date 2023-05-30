# Cabie - Ride share app built with Laravel & Vue

Begining with the Backend first. First thing that we got to do, is determine what Models are going to be used in this application.
We are not using *email-verification* for this project. Also, instead of **email** we'll be using **phone number** to authenticate a user. Instead of **password**, since we are using phone number, we change the "password" attribute in `users` table into **login_code** (which would ideally be the OTP).

On visiting the app, it will ask if the user wants to hail a ride as a **passenger** or wants to ride as a **driver**. We make a separate `driver` table to save this driver related information with a foreign key linking to the `users`table.

```bash
php artisan make:model Driver --migration
```

## Reference

- [Build A Ride Share App with Laravel and Vue | Full Stack Application Tutorial](https://www.youtube.com/watch?v=iFOEU6YNBzw)