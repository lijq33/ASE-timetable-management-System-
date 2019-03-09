

export const routes = [
    {
        path : '/login',
        component: require('./views/Login.vue')
    },
    {
        path : '/register',
        component: require('./views/Register')
    }, 

    {
        path : '/',
        component: require('./views/Home'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/calendar',
        component: require('./views/Calendar'),
        meta: {
            requiresAuth: true
        }
    },     
    {
        path : '/googlecalendar',
        component: require('./views/GoogleCalendar'),
        meta: {
            requiresAuth: true
        }
    },   
    {
        path : '/Appointment/Manage',
        component: require('./views/ManageAppointment'),
        meta: {
            requiresAuth: true
        }
    },     
    {
        path: '/company/:Cid',
        name: 'company',
        component: require('./views/Company'),
        meta: {
            requiresAuth: true
        }
    },     
];
