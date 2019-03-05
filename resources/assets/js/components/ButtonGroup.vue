<template>
    <div class = "">
        <div class = "" v-for = "(company, index) in companies" :key = "index">
            <div class="tw-max-w-sm tw-rounded tw-overflow-hidden tw-shadow-lg">
                <div class="tw-flex tw-items-center">
                    <img :src="'/logos/' + company.logo" alt=":(" class="tw-w-24 tw-h-24 tw-rounded-full tw-m-8"> 
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
                    </div>                   
                </div>

                <div class="tw-mx-8">
                    <button class="tw-bg-green-dark hover:tw-bg-green tw-text-white tw-font-bold tw-py-2 tw-px-4 tw-rounded"
                        @click ="visitCompany(company.id)">
                        Check Us Out!
                    </button>
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
                    
            <br />
        </div>
    </div>
</template>

<script>
    export default {
		mounted() {
			this.retrieveCompanies();	
        },
        
        data(){
            return{
                companies: [],
            }
        },

        methods:{
            retrieveCompanies() {
	            axios.get('/api/company')
				.then((res) => {	
                    this.companies = res.data.company;					
				}).catch((error) => {
					console.log(error)
				})
            },

            visit(url) {
                window.location.href = url;
            },
            
            visitCompany(id) {
                this.$router.push({name: 'company' ,params:{Cid:id}});
            },
        }
    }
</script>