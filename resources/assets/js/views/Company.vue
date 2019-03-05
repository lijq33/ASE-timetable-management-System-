<template>
    <div>
        <div class = "tw-flex tw-justify-center tw-my-8">
            <div class="tw-w-5/6 tw-rounded tw-overflow-hidden tw-shadow-lg">
                    <div class="tw-flex tw-items-center">
                            <img :src="'/logos/' + company.logo" alt=":(" class="tw-w-24 tw-h-24 tw-rounded-full tw-my-8 tw-mx-24"> 
                            <div class="tw-mr-8">
                                <div class="tw-font-bold tw-text-xl tw-mb-2">
                                    {{company.company_name}} 

                                    <span class="tw-mx-2 tw-text-sm tw-font-normal tw-text-grey-dark">
                                        ({{company.company_type}})
                                    </span>
                                </div> 
                                <p v-if = "company.tagline !== null" class = "tw-text-grey-darker tw-text-base tw-italic">
                                    - {{company.tagline}}
                                </p>
                                <p class = "tw-text-grey-darkest tw-text-base">
                                    <!-- You have {{no_of_appointments}} with {{company.company_name}} -->
                                </p>
                            </div> 
                    </div>
                
                    <div class="tw-px-6 tw-py-4">
                        <button v-if = "company.website !== null" class="tw-inline-block tw-bg-grey-lighter tw-rounded-full tw-px-3 
                            tw-py-1 tw-text-sm tw-font-semibold tw-text-blue tw-mr-2 hover:tw-underline"
                            @click = "visit(company.website)">
                            Visit Our Website
                        </button>
                        <button v-if = "company.email !== null" class="tw-inline-block tw-bg-grey-lighter tw-rounded-full tw-px-3 
                            tw-py-1 tw-text-sm tw-font-semibold tw-text-blue tw-mr-2">
                            <a :href="'mailto:' + company.email">
                                Email Us
                            </a>
                        </button>
                    </div>
                </div>
            </div>
       <calendar 
           :appointments = "appointments"
        >
        </calendar>
    </div>
</template>

<script>
import calendar from './Calendar'
    export default {
        components:{
            'calendar': calendar
        },

        mounted(){
            axios.get('/api/company/' + this.$route.params.Cid)
            .then((res) => {	
                this.appointments = res.data.timetables;	
                this.company = res.data.company;				
            }).catch((error) => {
                console.log(error)
            });
              
            // axios.get('/api/appointment/company/' + this.$route.params.Cid)
            // .then((res) => {	
            //     console.log("appointments", res.data.appointments);
            //     // console.log(res.data.timetables);
            //     console.log("users", res.data.user);
            // }).catch((error) => {
            //     console.log(error)
            // });
        },

        data() {
            return {
                appointments: [],
                company: [],
                no_of_appointments: '',
            }
        }, 
    }
</script>