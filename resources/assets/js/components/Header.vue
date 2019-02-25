<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                <img src = "/assets/img/TL.jpg" class = "tw-w-10">
                TeamLost
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    
                    <!-- EVERYONE CAN SEE -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            Health Services <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <router-link to="/HealthServices/Hospital" class="nav-link">Hospital</router-link>
                            <router-link to="/HealthServices/Polyclinic" class="nav-link">Polyclinic</router-link>
                            <router-link to="/HealthServices/Pharmacy" class="nav-link">Pharmacy</router-link>
                            <router-link to="/HealthServices/Chas Clinic" class="nav-link">Chas Clinic</router-link>
                            <router-link to="/HealthServices/Dental" class="nav-link">Dentals</router-link>
                        </div>
                    </li>

                    <!-- Only when not logged in -->
                    <template v-if = "!currentUser"> 
                        <li>
                            <router-link to="/calendar1" class="nav-link">Calendar</router-link>
                        </li>

                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li>
                            <router-link to="/register" class="nav-link">Register</router-link>
                        </li> 
                    </template>

                    <template v-else>
                         <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="tw-capitalize nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ currentUser.name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </template>

                    <!-- Only for non-admin user-->
                    <template v-if = "!isAdmin && currentUser">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                Appointment <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/Appointment/Create" class="nav-link">Book Appointment</router-link>
                                <router-link to="/Appointment/Manage" class="nav-link">Manage Appointment</router-link>
                            </div>
                        </li>
                       
                        <li>
                            <router-link to="/help" class="nav-link">Help</router-link>
                        </li>
                    </template>

                    <!-- For admin -->
                    <template v-if = "isAdmin">
                        <li>
                            <router-link to="/feedback/show" class="nav-link">View Feedbacks</router-link>
                        </li>
                    </template>
                    
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
            isAdmin() {
                return this.$store.getters.isAdmin
            }
        }
    }
</script>
