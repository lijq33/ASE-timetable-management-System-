

export const routes = [
    {
        path : '/',
        component: require('./views/Home')
    },
    {
        path : '/login',
        component: require('./views/Login.vue')
    },
    {
        path : '/register',
        component: require('./views/Register')
    }, 
    {
        path : '/calendar',
        component: require('./views/Calendar')
    },    
    {
        path : '/test',
        component: require('./views/test')
    },
    {
        path : '/calendar1',
        component: require('./views/Calendar1')
    },     
    // {
    //     path : '/HealthServices/Hospital',
    //     component: require('./views/HealthServices/Hospital'),
    // },
    // {
    //     path : '/HealthServices/Polyclinic',
    //     component: require('./views/HealthServices/Polyclinic'),
    // },
    // {
    //     path : '/HealthServices/Dental',
    //     component: require('./views/HealthServices/Dental'),
    // },   
    // {
    //     path : '/HealthServices/ChasClinic',
    //     component: require('./views/HealthServices/ChasClinic'),
    // },
    // {
    //     path : '/HealthServices/Pharmacy',
    //     component: require('./views/HealthServices/Pharmacy'),
    // },
    // {
    //     path : '/Map',
    //     component: require('./views/Map'),
    // },

    // //requires login
    // {
    //     path : '/Appointment/Create',
    //     component: require('./views/Appointment/Create'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
    // {
    //     path : '/Appointment/Manage',
    //     component: require('./views/Appointment/Manage'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
    // {
    //     path : '/Help',
    //     component: require('./views/Help'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },

    // //admin
    // {
    //     path : '/feedback/show',
    //     component: require('./views/ViewFeedback'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
];
