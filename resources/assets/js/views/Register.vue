<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="tw-flex tw-pb-4 tw-mb-4">
                    <div class="tw-w-full">
                        <div class = "tw-w-full tw-pb-2 tw-text-lg tw-font-semibold tw-font-montserrat tw-text-grey-darkest">
                            I am registering as an 
                        </div>
                        <div class = "tw-w-full">
                            <button @click = "Individual"
                                class = " hover:tw-bg-blue-dark tw-text-white tw-font-bold tw-py-3 tw-px-6" 
                                :class = " {'tw-bg-blue-dark': isIndividual , 'tw-bg-blue' : !isIndividual}"
                            >
                                Individual
                            </button>
                            <button @click = "Company"
                                class = "hover:tw-bg-blue-dark tw-text-white tw-font-bold tw-py-3 tw-px-6" 
                                :class = " {'tw-bg-blue-dark': isCompany , 'tw-bg-blue' : !isCompany}"
                            >
                                Company
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header tw-text-grey-darker">
                        Create Your Account
                    </div>
                    <div class="card-body">
                        <div class="tw-flex tw-justify-center ">
                            <div class="tw-w-full">
                                <div v-show = "isIndividual">
                                    <user-register>
                                    </user-register>
                                </div>
                                <div v-show = "isCompany">
                                    <company-register>
                                    </company-register>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserRegister  from '../components/UserRegister';
    import CompanyRegister from '../components/CompanyRegister';

    export default {
        components: {
            'user-register' : UserRegister,
            'company-register' : CompanyRegister,
        },

        mounted() {
            this.groups.forEach(group => {
                this.filterGroup(group);
            });
        },
        
        data () {
            return {
                isIndividual: false,
                isCompany: false,
            }
        },

        methods: {

            Individual(){
                this.isIndividual = true; 
                this.isCompany = false;
            },

            Company(){
                this.isIndividual = false; 
                this.isCompany = true;
            },
        }

    }

</script>


