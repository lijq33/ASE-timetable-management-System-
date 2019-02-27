<template>
    <div>
        <div>
            <div class = "tw-flex tw-text-2xl sm:tw-text-3xl tw-pb-8">
                <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                            tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                            tw-w-10 tw-h-10 
                            sm:tw-h-12 sm:tw-w-12">2</div>    
                <span class = "tw-font-bold">Your Payment Information</span>
            </div>
            <label for="card-element" class="tw-leading-none">Credit Card</label>
            <stripe-form></stripe-form>
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
    import { createToken, Card } from 'vue-stripe-elements-plus'
	import StripeForm from '../components/StripeForm';
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
		
    export default {
        name: "class-booking-form",

        components: {
    		'stripe-form': StripeForm,
            'popper': Popper
        },

        props: ['timetableId'],

        mounted() {
            axios.get(this.target, {
                    timetable_id: this.timetableId,
                    user_id: this.userId,
                    stripeToken: token,
            })
            .then(res => {
                // window.location.href = res.data.url;
            })
            .catch((error) => {
                console.log("Caught Error! GG");
                this.error = error.response.data.errors;
                this.isLoading = false;
            });
        },

        data() {
            return {

                stripeToken: '',
                error: [],
                isLoading: false,
            }
        }, 
        
        methods: {
            pay() {
                this.isLoading = true;
                createToken().then(result => {

                    if (result.error != null) {
                        console.log (result.error);
                        this.isLoading = false;
                        return ;
                    }

                    this.makeAppointment(result.token.id);
                })
          
            },

            makeAppointment(token) {
                this.isLoading = true;
                console.log("Booking Trigger");

				axios.post(this.target, {
                    timetable_id: this.timetableId,
                    user_id: this.userId,
                    stripeToken: token,
				})
				.then(res => {
                    // window.location.href = res.data.url;
                })
				.catch((error) => {
					console.log("Caught Error! GG");
                    this.error = error.response.data.errors;
                    this.isLoading = false;
				});
               
            },
        }
    }
</script>