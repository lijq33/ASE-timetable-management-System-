## TBD

# HOME PAGE
FILLED UP HOMEPAGE WITH SOMETHING



# Timetable
Import timetable(store)
update timetable(update)

# Appointment
Only Company can create appointment
Create Appointment (store) -> for others to "make appointment"
Make Appointment(store) 

#Notification


## COMPLETED FUNCTIONS
Registration for Both Individual and company
Login for both individual and company
Create timetable
Delete timetable


## BUGS



## TODO
Sync google calendar
Stripe
CRUD APPOINTMENT AND TIMETABLE



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
     Timetable (other information, user ID, Appointment ID (ID in appointment table))
            |
     Appointment (ID, Timetable ID and user ID)


# Design 1
A single timetable can have many time slot, hence it can have many appointment
An appointment belongs to a single timetable
Thus, One to Many relationship.

Timetable
id user_id(user that created the timetable) start_time end_time
1    2(JH-COMPANY)        
1    2(JH)         

Appointment
ID   timetable_id   user_id(user that make the appointment) 
1         1              3(JQ-INDIVIDUAL)

<!-- # Design 2
Timetable
id  user_id                   ............
1    2(JH-COMPANY)        
1    3(JQ)     

Appointment
id   appointer_id      appointee_id      start_time   end_time     subject      date     .....
1    2(JH-COMPANY)     3(JQ-Individual)    xx            xx -->


USER TABLE
USER ID COMPANY ID INDIVIDUAL ID
    1        1          NULL
    2      NULL          1
    3        2          NULL

