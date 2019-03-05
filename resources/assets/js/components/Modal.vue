<template>
    <!-- Custom Edit Modal Component -->
    <div v-if = "hasAppointment">
        <b-modal
            ref="myModalRef"
            hide-footer
            title="Booking Appointment"
            no-close-on-backdrop
        >
            <div>
                <div v-show = "requirePayment">
                    <div class = "tw-flex tw-text-2xl sm:tw-text-3xl tw-pb-8">
                        <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                                    tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                                    tw-w-10 tw-h-10 
                                    sm:tw-h-12 sm:tw-w-12">1</div>    
                        <span class = "tw-font-bold">Booking Details</span>
                    </div>
                </div>

                
                <div class="form-group row tw-flex tw-justify-center" >
                    <label class="col-md-3 col-form-label text-md-right">
                        Title:
                    </label>
                    <input type="text" :value = "appointment.Subject" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>
                
                <div class="form-group row tw-flex tw-justify-center" v-if = "appointment.Description">
                    <label class="col-md-3 col-form-label text-md-right">
                        Description : 
                    </label>
                    <input type="text" :value = "appointment.Description" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>
                
                <div class="form-group row tw-flex tw-justify-center" v-if = "appointment.Location">
                    <label class="col-md-3 col-form-label text-md-right">
                        Location : 
                    </label>
                    <input type="text" :value = "appointment.Location" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>

                <div class="form-group row tw-flex tw-justify-center" v-if = "appointment.StartTime">
                    <label class="col-md-3 col-form-label text-md-right">
                        Date : 
                    </label>
                    <input type="text" :value = "date" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>
                
                <div class="form-group row tw-flex tw-justify-center" v-if = "appointment.StartTime">
                    <label class="col-md-3 col-form-label text-md-right">
                        Start Time : 
                    </label>
                    <input type="text" :value = "startTime" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>
                
                 <div class="form-group row tw-flex tw-justify-center" v-if = "appointment.EndTime">
                    <label class="col-md-3 col-form-label text-md-right">
                        End Time : 
                    </label>
                    <input type="text" :value = "endTime" 
                        class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                        disabled
                    >
                </div>
             
            </div>
            <div>
                <div v-show = "requirePayment">
                    <div class = "tw-flex tw-text-2xl sm:tw-text-3xl tw-pb-8">
                        <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                                    tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                                    tw-w-10 tw-h-10 
                                    sm:tw-h-12 sm:tw-w-12">2</div>    
                        <span class = "tw-font-bold">Your Payment Information</span>
                    </div>
                    <div class="form-group row tw-flex tw-justify-center">
                        <label class="col-md-3 col-form-label text-md-right">
                            Price : 
                        </label>
                        <input type="text" :value = "price" 
                            class="col-md-7 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                            disabled
                        >
                    </div>
                    <stripe-form></stripe-form>
                    <div class = "tw-flex tw-justify-center">
                        <img src = "/assets/img/stripe-payment.png" alt = "Payment Through Stripe">
                    </div>
                </div>

                <div class="form-group row tw-my-6 tw-flex tw-justify-center">
                    <div class="">
                        <div v-if = "!isLoading">
                            <button type="submit" class="btn btn-primary tw-mr-4" @click ="book()" >
                                Book
                            </button>
                            <button type="submit" class="btn btn-secondary tw-ml-4" @click = "closeModal" >
                                Cancel
                            </button>
                        </div>
                        <div v-else>
                            <img src = "/assets/img/loader.gif" alt = "Loading...">
                        </div>
                    </div>
                </div>
            </div>

           
        </b-modal>
    
    </div>
</template>

<script>
	import { Modal } from "bootstrap-vue/es/components";
    import { createToken, Card } from 'vue-stripe-elements-plus'
	import StripeForm from './StripeForm';

    export default {

        components: {
    		'stripe-form': StripeForm,
        },

        props:['showModal', 'appointment'],
        
        mounted() {
         
        },

        data(){
            return{
                stripeToken: '',
                error: [],
                isLoading: false,
            }
        },

        watch:{
            showModal(){
                if (this.showModal == true)
				    this.$refs.myModalRef.show();
                else 
				    this.$refs.myModalRef.hide();
            },

            
        },

        computed: {
			requirePayment() {
                if (this.hasAppointment)
                    return (this.appointment.Price != 0);
                return false;
            },

            hasAppointment() {
                return (this.appointment != null);
            },

            date() {
                if (this.hasAppointment)
                    return this.appointment.StartTime.substr(0, 15);
                return '';
            },

            startTime() {
                if (this.hasAppointment)
                    return this.appointment.StartTime.substr(16, 9);
                return '';
            },

            endTime() {
                if (this.hasAppointment)
                    return this.appointment.EndTime.substr(16, 9);
                return '';
            },

            price() {
                if (this.hasAppointment)
                    return "$" + this.appointment.Price;
                return '';
            }
            
        },

        methods:{

            book() {
                if (this.requirePayment)
                    this.pay();
                else
                    this.makeAppointment(null);
            },

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

				axios.post('/api/appointment', {
                    timetable_id: this.appointment.Id,
                    start_time: this.appointment.StartTime,
                    end_time: this.appointment.EndTime,
                    stripeToken: token,
				})
				.then(res => {
                    // window.location.href = res.data.url;
                })
				.catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
				});
               
            },

            closeModal() {
				this.$emit('hideModal');
            },
        }
    }
</script>

<style>
    .close{
        visibility: hidden;
    }
</style>

      


       

        
     