<template>
    <div>
        <div class="form-group row">
            <label for="nric" class="col-md-4 col-form-label text-md-right">
                NRIC 
                <popper trigger="hover" :options = "{placement: 'bottom'}">
                    <div class="popper tw-font-hairline tw-text-grey-dark">
                        This is your login ID
                    </div>
                    <button slot="reference">   
                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                    </button>
                </popper>
            </label>

            <div class="col-md-6">
                <input type = "text"
                    id="nric" 
                    v-model = "nric"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    :class = "{ 'tw-border-red-light' : error['nric'] != undefined}"
                    placeholder = "S4123451E"
                    required autofocus
                >

                <div class = "tw-text-red" v-if = "error['nric'] != undefined">
                    <span> {{this.error['nric'].toString()}} </span>   
                </div>
            </div>
        </div>

        <!-- NAME -->
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">
                Full Name
                <popper trigger="hover" :options = "{placement: 'bottom'}">
                    <div class="popper tw-font-hairline tw-text-grey-dark">
                        Full Name as per NRIC
                    </div>
                    <button slot="reference">   
                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                    </button>
                </popper>
            </label>

            <div class="col-md-6">
                <input type = "text"
                    id="name" 
                    v-model = "name"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    :class = "{ 'tw-border-red-light' : error['name'] != undefined}"
                    placeholder = "John Doe"
                    required autofocus
                >
            
                <div class = "tw-text-red" v-if = "error['name'] != undefined">
                    <span> {{this.error['name'].toString()}} </span>   
                </div>
            </div>
        </div>
        
        <!-- Password -->
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">
                Password
                <popper trigger="hover" :options = "{placement: 'bottom'}">
                    <div class="popper tw-font-hairline tw-text-grey-dark">
                        Your password should contain a minimum of 6 characters
                    </div>
                    <button slot="reference">   
                        <i class="fas fa-exclamation-circle tw-text-grey-dark tw-cursor-pointer"></i>
                    </button>
                </popper>
            </label>

            <div class="col-md-6">
                <input type = "password"
                    id="password" 
                    v-model = "password"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    :class = "{ 'tw-border-red-light' : error['password'] != undefined}"
                    oncopy="return false" oncut="return false" onpaste="return false"
                    required autofocus
                >

                <div class = "tw-text-red" v-if = "error['password'] != undefined">
                    <span> {{this.error['password'].toString()}} </span>   
                </div>
            </div>
        </div>

        <!-- password_confirmation -->
        <div class="form-group row">
            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

            <div class="col-md-6">
                <input type = "password"
                    id="password_confirmation" 
                    v-model = "password_confirmation"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    oncopy="return false" oncut="return false" onpaste="return false"
                    required autofocus
                >
            </div>
        </div>

            <!-- Email -->
        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

            <div class="col-md-6">
                <input type = "text"
                    id="email" 
                    v-model = "email"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    :class = "{ 'tw-border-red-light' : error['email'] != undefined}"
                    placeholder = "JohnDoe@gmail.com"
                    required autofocus
                >

                <div class = "tw-text-red" v-if = "error['email'] != undefined">
                    <span> {{this.error['email'].toString()}} </span>   
                </div>
            </div>
        </div>

        <!-- Telephone Number -->
        <div class="form-group row">
            <label for="telephone_number" class="col-md-4 col-form-label text-md-right">
                Telephone Number
                    <popper trigger="hover" :options = "{placement: 'bottom'}">
                    <div class="popper tw-font-hairline tw-text-grey-dark">
                        Your appointment details will be sent to you via SMS.
                    </div>
                    <button slot="reference">   
                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                    </button>
                </popper>    
            </label>

            <div class="col-md-6">
                <input type = "text"
                    id="telephone_number" 
                    v-model = "telephone_number"
                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                    :class = "{ 'tw-border-red-light' : error['telephone_number'] != undefined}"
                    placeholder = "9512 2314"
                    required autofocus
                >

                <div class = "tw-text-red" v-if = "error['telephone_number'] != undefined">
                    <span> {{this.error['telephone_number'].toString()}} </span>   
                </div>
            </div>
        </div>

        <div class="form-group row tw-my-6">
            <div class="col-md-6 offset-md-4">
                <div v-if = "!isLoading">
                    <button type="submit" class="btn btn-primary" @click ="register()" >
                        Register
                    </button>
                </div>
                <div v-else>
                    <img src = "/assets/img/loader.gif" alt = "Loading...">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
    
    export default {
        components: {
            'popper': Popper
        },

        data() {
            return {
                name: '',
                nric: '',
                password: '',
                password_confirmation: '',
                email: '',
                telephone_number: '',
                error: [],
                isLoading: false,
                registration_type:'individual',
            }
        }, 

        methods: {
            register() {
                this.isLoading = true;
                this.error=[];

                axios.post("/api/auth/register", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    nric: this.nric,
                    telephone_number: this.telephone_number,
                    registration_type: this.registration_type
                })
                .then(response => {
                   this.$router.replace( "/login");
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                });
            },
        }
    }
</script>