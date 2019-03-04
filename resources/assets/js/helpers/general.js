export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.state.currentUser;

        if(requiresAuth && !currentUser) {
            next('/login');
        } else if( (to.path == '/login' || to.path == '/register' ) && currentUser) {
            next('/');
        } else {
            next();
        }
    });
    
    // axios.interceptors.request.use((request) => {
    //     // var str = "https://www.googleapis.com/calendar/v3/calendars/5105trob9dasha31vuqek6qgp0@group.calendar.google.com/events?key=AIzaSyD76zjMDsL_jkenM5AAnNsORypS1Icuqxg";
    //     var regex = RegExp(/https:\/\/www.googleapis.com\/calendar\/v3\/calendars\/.*/);

    //     // console.log(regex.test(request.url));

    //     if (regex.test(request.url)){
    //         delete axios.defaults.headers.common["Authorization"];
    //         console.log("here");

    //     }else if (store.getters.currentUser) {
    //         setAuthorization(store.getters.currentUser.token);
    //         console.log("here2");
        
    //     }
    //     return request;

    // })
      
    axios.interceptors.response.use(null, (error) => {
        if (error.response.status == 401) {
            store.commit('logout');
            router.push('/login');
        }

        return Promise.reject(error);
    });

    if (store.getters.currentUser) {
        setAuthorization(store.getters.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}

