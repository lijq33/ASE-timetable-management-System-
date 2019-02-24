## TBD

# Timetable
Import timetable(store)
View timetable(show)
update timetable(update)
create timetable(new timetable)
https://pusher.com/tutorials/calendar-vue

# Event
Create event (store) -> for others to "make appointment"

# Appointment
Make Appointment(store) 

#Notification


## COMPLETED FUNCTIONS
Registration for Both Individual and company
Login for both individual and company

## BUGS



## TODO




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




## Flow of Web
Frontend(Website)(test.vue)
->request(API.php/WEB.php)
->direct the request to a specific controller(registercontroller.php)
->do anything u wan
->object/model

## DISCUSSION
company - user - individual
            | 
         Timetable (Appointment ID (ID in appointment table))
            |
         Appointment (ID, Timetable ID and Timetable ID)


user_id 2 have an appointment with user_id 4

Timetable
id user_id appointment id
1    2          1
2    3          2
3    4          1

Appointment
ID   timetable_id_1 timetable_id_2  
1         1              3
2
3


USER TABLE
USER ID COMPANY ID INDIVIDUAL ID
    1        1          NULL
    2      NULL          1
    3        2          NULL

