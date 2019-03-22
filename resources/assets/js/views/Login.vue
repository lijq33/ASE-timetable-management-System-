<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header tw-text-grey-darker">Log In</div>

                    <div class="card-body">
                        <!-- login -->
                        <div class="form-group row">
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
                                Email
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="login_id" 
                                    v-model = "form.email"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    placeholder = "JohnDoe@gmail.com"
                                    required autofocus
                                >
                            </div>
                        </div>

                        <!-- login_Password -->
                        <div class="form-group row">
                            <label for="login_password" class="col-md-4 col-form-label text-md-right">
                                Password
                            </label>

                            <div class="col-md-6">
                                <input type = "password"
                                    id="login_password" 
                                    v-model = "form.password"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    oncopy="return false" oncut="return false" onpaste="return false"
                                    placeholder = "******"
                                    required autofocus
                                >
                            </div>
                        </div>

                         <div class="form-group row" v-if="authError">
                            <div class="col-md-6 offset-md-4">
                                <span class="tw-text-red">
                                    {{ authError }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4 col-form-label text-md-right">
                                Logging in as
                            </div>

                            <div class="col-md-6">
                                <input type="radio" name="registration_type" v-model = "form.registration_type" value="individual" /> Individual<br>
                                <input type="radio" name="registration_type" v-model = "form.registration_type" value="company" /> Company<br>
                            </div>
                        </div>

                        <div class="form-group row tw-my-6">
                            <div class="col-md-6 offset-md-4">
                                <div v-if = "!isLoading">
                                    <button type="submit" class="btn btn-primary" @click = "authenticate()" >
                                        Login
                                    </button>
                                </div>
                                <div v-else>
                                    <img src = "/assets/img/loader.gif" alt = "Loading...">
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class = "tw-px-8 tw-pb-8"> 
                        No account? 
                        <span class = "tw-underline tw-text-blue tw-cursor-pointer"
                            @click = "register()"
                        >
                            You can create your account here.
                        </span>
                    </div>
                
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
    import {login} from '../helpers/auth';
    
    export default {
        name: "login",

        components: {
            'popper': Popper
        },
        
        data() {
            return {
                form: {
                    registration_type: 'individual',
                    email: '',
                    password: '',
                },
                error: null,
                isLoading: false,
            }
        },

        methods: {
            authenticate() {
                this.isLoading = true;
                this.$store.dispatch('login');

                login(this.$data.form)
                .then((res) => {
                    this.$store.commit("loginSuccess", res);
                    this.$router.push({path: '/'});
                })
                .catch((error) => {
                    this.$store.commit("loginFailed", {error});
                    this.isLoading = false;
                });
            },

            register() {
                this.$router.push({path: '/register'});
            },
        },

        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        }
    }
</script>

<style>
    .login_id {
        border-radius: 25px;
        border: 5px #ABA5A5;
        padding: 2px; 
        width: 100%;
    }
</style>
