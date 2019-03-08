## TBD


## TODO

#calendar
Sync google calendar - do with a real account
xy - legend

view all appointments?

Make it such that ppl can book 

timetable id, invitation url


## COMPLETED FUNCTIONS

# HOME PAGE
Display all companies registered
Able to access each individual company

#registration
Registration for Both Individual and company
Login for both individual and company

#timetable
Create timetable
Read timetable
Update timetable
Delete timetable

#Appointment
Create Appointment - Make payment if required (w/ flash message), Stripe
delete APPOINTMENT 



## Commands 
# Starting services
php artisan serve
npm run dev/watch

# Creating model, table and controller
php artisan make:model <model_name> --mc

# Composer
Composer update
Composer install

# npm
npm install

# migration
php artisan migrate:xxx 
php artisan migrate --step


##Others!
Only company can make appointment!
User can book appointments.
if a user book appointment that requires payment, use test card, 4242 4242 4242 4242 dd/dd CVC(any number) to test


## Flow of Web
Frontend(Website)(test.vue)
->request(API.php/WEB.php)
->direct the request to a specific controller(registercontroller.php)
->do anything u wan
->object/model
