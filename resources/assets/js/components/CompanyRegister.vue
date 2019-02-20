<template>
    <div>
        <div class = "">
            <div class = "tw-flex tw-text-xl tw-items-center tw-flex tw-ml-8 tw-border-b tw-pb-4 tw-m-8">
                <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                        tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                        tw-w-10 tw-h-10">
                    1
                </div>   
                <span class = "tw-font-bold">Company details</span>
            </div>

            <!-- Company name -->
            <div class="form-group row">
                <label for="company_name" class="col-md-4 col-form-label text-md-right">
                    Company name<span class = "tw-text-red">*</span>
                </label>

                <div class="col-md-6">
                    <input type = "text"
                        id="company_name" 
                        v-model = "company_name"
                        class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                        :class = "{ 'tw-border-red-light' : error['company_name'] != undefined}"
                        required autofocus
                    >
                
                    <div class = "tw-text-red" v-if = "error['company_name'] != undefined">
                        <span> {{this.error['company_name'].toString()}} </span>   
                    </div>
                </div>
            </div>

            <!-- Industry type -->
            <div class="form-group row">
                <label for="industry" class="col-md-4 col-form-label text-md-right">
                    Industry Type<span class = "tw-text-red">*</span> 
                </label>

                <div class="col-md-6">
                    <select v-model = "industry_type" class="form-control" required>
                        <option value="" disabled selected hidden>--Select your option--</option>
                        <option v-for="(industry_type, index) in industry_types" :key = "index">{{industry_type}}</option>
                    </select>
                                    
                    <div class = "tw-text-red" v-if = "error['industry_type'] != undefined">
                        <span> {{this.error['industry_type'].toString()}} </span>   
                    </div>
                </div>
            </div>

            <!-- Company type -->
            <div class="form-group row">
                <label for="company_type" class="col-md-4 col-form-label text-md-right">
                    Company Type<span class = "tw-text-red">*</span>
                </label>

                <div class="col-md-6">
                    <select v-model = "company_type" class="form-control" required>
                        <option value="" disabled selected hidden>--Select your option--</option>
                        <option v-for="(company_type, index) in company_types" :key = "index">{{company_type}}</option>
                    </select>         

                    <div class = "tw-text-red" v-if = "error['company_type'] != undefined">
                        <span> {{this.error['company_type'].toString()}} </span>   
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class = "tw-flex tw-text-xl tw-items-center tw-flex tw-ml-8 tw-border-b tw-pb-4 tw-m-8">
                <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                        tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                        tw-w-10 tw-h-10">
                    2
                </div>   
                <span class = "tw-font-bold">Profile details</span>
            </div>

            <div class="form-group row">
                <label for="website" class="col-md-4 col-form-label text-md-right">
                    Website 
                    <popper trigger="hover" :options = "{placement: 'bottom'}">
                        <div class="popper tw-font-hairline tw-text-grey-dark">
                            This is a link to your external website.
                        </div>
                        <button slot="reference">   
                            <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                        </button>
                    </popper>
                </label>

                <div class="col-md-6">
                    <input type = "text"
                        id="website" 
                        v-model = "website"
                        class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                        :class = "{ 'tw-border-red-light' : error['website'] != undefined}"
                        required autofocus
                    >

                    <div class = "tw-text-red" v-if = "error['website'] != undefined">
                        <span> {{this.error['website'].toString()}} </span>   
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="tagline" class="col-md-4 col-form-label text-md-right">
                    Tagline
                    <popper trigger="hover" :options = "{placement: 'bottom'}">
                        <div class="popper tw-font-hairline tw-text-grey-dark">
                            Use your tagline to briefly describe what your company does. 
                        </div>
                        <button slot="reference">   
                            <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                        </button>
                    </popper>
                </label>

                <div class="col-md-6">
                    <input type = "text"
                        id="tagline" 
                        v-model = "tagline"
                        class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                        :class = "{ 'tw-border-red-light' : error['tagline'] != undefined}"
                        required autofocus
                    >

                    <div class = "tw-text-red" v-if = "error['tagline'] != undefined">
                        <span> {{this.error['tagline'].toString()}} </span>   
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="logo" class="col-md-4 col-form-label text-md-right">
                    Logo
                </label>
            
                <div v-if = "image === ''" class = "col-md-6">
                    <input accept = "image/*" type = "file" class = "upload-image-input tw-hidden" @change = "onFileSelected" >

                    <button class = "tw-p-4 hover:tw-bg-teal-dark tw-bg-teal tw-text-white tw-font-bold tw-py-2 tw-px-4 tw-rounded" 
                        @click = "uploadImage">
                        Select A Profile
                    </button>

                </div>
                
                <div v-else class = "col-md-6">
                    <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-rounded-full tw-overflow-hidden">
                        <img :src = "image" class = "tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center" />
                    </div>

                    <button class = "tw-p-4 hover:tw-bg-teal-dark tw-bg-teal tw-text-white tw-font-bold tw-py-2 tw-px-4 tw-rounded" 
                        @click = "removeImage">
                        Choose Another Profile
                    </button>
                </div>

            </div>   
        </div>
        
        <div>
            <div class = "tw-flex tw-text-xl tw-items-center tw-flex tw-ml-8 tw-border-b tw-pb-4 tw-m-8">
                <div class="tw-flex tw-items-center tw-justify-center tw-rounded-full 
                        tw-border-2 tw-border-blue-dark tw-text-blue-dark tw-mr-2
                        tw-w-10 tw-h-10">
                    3
                </div>   
                <span class = "tw-font-bold">Account details</span>
            </div>
            <!-- Password -->
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">
                    Password<span class = "tw-text-red">*</span>
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
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">
                    Confirm Password<span class = "tw-text-red">*</span>
                </label>

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
                <label for="email" class="col-md-4 col-form-label text-md-right">
                    Email<span class = "tw-text-red">*</span>
                </label>

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
        </div>
        
        <!-- Telephone Number -->
        <div class="form-group row">
            <label for="telephone_number" class="col-md-4 col-form-label text-md-right">
                Telephone Number<span class = "tw-text-red">*</span>
                    <popper trigger="hover" :options = "{placement: 'bottom'}">
                        <div class="popper tw-font-hairline tw-text-grey-dark">
                            All appointment details will be sent to you via SMS.
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
                registration_type:'company',
                company_name: '',
                company_type:'',
                industry_type:'',

                website:'',
                tagline:'',
                selectedFile: null,

                password: '',
                password_confirmation: '',
                email: '',
                telephone_number: '',

                image: '',

                error: [],
                
                isLoading: false,
                
                company_types: [
                    "Public company",
                    "Self-employed",
                    "Government agency",
                    "Nonprofit",
                    "Sole proprietorship",
                    "Privately held",
                    "Partnership",
                ],
                industry_types: [
                    "Accounting",
                    "Airlines/Aviation",
                    "Alternative Dispute Resolution",
                    "Alternative Medicine",
                    "Animation",
                    "Apparel & Fashion",
                    "Architecture & Planning",
                    "Arts & Crafts",
                    "Automotive",
                    "Aviation & Aerospace",
                    "Banking",
                    "Biotechnology",
                    "Broadcast Media",
                    "Building Materials",
                    "Business Supplies & Equipment",
                    "Capital Markets",
                    "Chemicals",
                    "Civic & Social Organization",
                    "Civil Engineering",
                    "Commercial Real Estate",
                    "Computer & Network Security",
                    "Computer Games",
                    "Computer Hardware",
                    "Computer Networking",
                    "Computer Software",
                    "Construction",
                    "Consumer Electronics",
                    "Consumer Goods",
                    "Consumer Services",
                    "Cosmetics",
                    "Dairy",
                    "Defense & Space",
                    "Design",
                    "E-learning",
                    "Education Management",
                    "Electrical & Electronic Manufacturing",
                    "Entertainment",
                    "Environmental Services",
                    "Events Services",
                    "Executive Office",
                    "Facilities Services",
                    "Farming",
                    "Financial Services",
                    "Fine Art",
                    "Fishery",
                    "Food & Beverages",
                    "Food Production",
                    "Fundraising",
                    "Furniture",
                    "Gambling & Casinos",
                    "Glass, Ceramics & Concrete",
                    "Government Administration",
                    "Government Relations",
                    "Graphic Design",
                    "Health, Wellness & Fitness",
                    "Higher Education",
                    "Hospital & Health Care",
                    "Hospitality",
                    "Human Resources",
                    "Import & Export",
                    "Individual & Family Services",
                    "Industrial Automation",
                    "Information Services",
                    "Information Technology & Services",
                    "Insurance",
                    "International Affairs",
                    "International Trade & Development",
                    "Internet",
                    "Investment Banking",
                    "Investment Management",
                    "Judiciary",
                    "Law Enforcement",
                    "Law Practice",
                    "Legal Services",
                    "Legislative Office",
                    "Leisure, Travel & Tourism",
                    "Libraries",
                    "Logistics & Supply Chain",
                    "Luxury Goods & Jewelry",
                    "Machinery",
                    "Management Consulting",
                    "Maritime",
                    "Market Research",
                    "Marketing & Advertising",
                    "Mechanical Or Industrial Engineering",
                    "Media Production",
                    "Medical Device",
                    "Medical Practice",
                    "Mental Health Care",
                    "Military",
                    "Mining & Metals",
                    "Motion Pictures & Film",
                    "Museums & Institutions",
                    "Music",
                    "Nanotechnology",
                    "Newspapers",
                    "Non-profit Organization Management",
                    "Oil & Energy",
                    "Online Media",
                    "Outsourcing/Offshoring",
                    "Package/Freight Delivery",
                    "Packaging & Containers",
                    "Paper & Forest Products",
                    "Performing Arts",
                    "Pharmaceuticals",
                    "Philanthropy",
                    "Photography",
                    "Plastics",
                    "Political Organization",
                    "Primary/Secondary Education",
                    "Printing",
                    "Professional Training & Coaching",
                    "Program Development",
                    "Public Policy",
                    "Public Relations & Communications",
                    "Public Safety",
                    "Publishing",
                    "Railroad Manufacture",
                    "Ranching",
                    "Real Estate",
                    "Recreational Facilities & Services",
                    "Religious Institutions",
                    "Renewables & Environment",
                    "Research",
                    "Restaurants",
                    "Retail",
                    "Security & Investigations",
                    "Semiconductors",
                    "Shipbuilding",
                    "Sporting Goods",
                    "Sports",
                    "Staffing & Recruiting",
                    "Supermarkets",
                    "Telecommunications",
                    "Textiles",
                    "Think Tanks",
                    "Tobacco",
                    "Translation & Localization",
                    "Transportation/Trucking/Railroad",
                    "Utilities",
                    "Venture Capital & Private Equity",
                    "Veterinary",
                    "Warehousing",
                    "Wholesale",
                    "Wine & Spirits",
                    "Wireless",
                    "Writing & Editing"
                ],

            }
        }, 

        methods: {
            register() {              
                this.isLoading = true;

                const fd = new FormData();
                
                fd.append('registration_type', this.registration_type);
                fd.append('company_name', this.company_name);
                fd.append('company_type', this.company_type);
                fd.append('industry_type', this.industry_type);

                fd.append('website', this.website);
                fd.append('tagline', this.tagline);
                fd.append('logo', this.selectedFile);

                fd.append('password', this.password);
                fd.append('password_confirmation', this.password_confirmation);
                fd.append('email', this.email);
                fd.append('telephone_number', this.telephone_number);

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                this.error=[];

                axios.post('/api/auth/register', fd, config)
                .then(response => {
                   this.$router.replace( "/login");
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                });



            },

            uploadImage(e) {
                document.querySelector('.upload-image-input').click();
            },

            removeImage(){
                this.image = '';
                this.selectedFile = null;
            },
            
            createImage(file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },


            onFileSelected (event) {
                let files = event.target.files || event.dataTransfer.files;

                if (!files.length)
                    return;

                this.selectedFile = files[0];
                
                this.createImage(this.selectedFile);
            },
        }
    }
</script>