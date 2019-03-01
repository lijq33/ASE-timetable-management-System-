<template>
<div >
    <div >
        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Type :
            </label>
            <input type="text" :value = "editAppointment.health_service_type" 
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                disabled
            >
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Location :
            </label>
            <textarea :value = "editAppointment.location"
                class = "col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light tw-py-2"
                disabled
            >
            </textarea>
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                {{this.editAppointment.health_service_type}} : 
            </label>
            <input type="text" :value = "clinic" 
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                disabled
            >
        </div>

        <!-- EDITABLE DATA -->
        <div v-if = "!isChasclinic" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                {{this.Role}} : 
            </label>
            <input type="text" v-model = "prefer_practitioner"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div v-if = "isDental" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Services :
            </label>
            <div class="tw-w-1/2">
                <select v-model = "service " class="form-control" required>
                    <option v-for="(service, index) in allDentalServices" :key = "index">{{service}}</option>
                </select>
            </div>
        </div>

        <div v-else class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Condition :
            </label>
            <input type="text" v-model = "condition"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div v-if = "isHospital || isPolyclinic" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Department :
            </label>
            <div class="tw-w-1/2">
                <select v-model = "department" class="form-control" required>
                    <option v-for="(department, index) in allDepartments" :key = "index">{{department}}</option>
                </select>
            </div>
        </div>

        <div v-if = "isHospital" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Referral :
            </label>
            <input type="text" v-model = "referal"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Date :
            </label>
            <div class="tw-w-1/2">
                <date-picker v-model = "appointment_date" required = "required"> </date-picker>
            </div>
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Time :
            </label>
            <div class="tw-w-1/2">
                <time-picker v-model = "appointment_time" required = "required" :useContainer = "true"> </time-picker>
            </div>
        </div>

    </div>
        <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
            <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
            <button class = "tw-ml-2 btn btn-primary" @click = "updateAppointment()">Update</button>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['appointment'],

        data() {
            return {
                editAppointment: '',        
                referal: '',
                condition: '',
                department: '',
                service: '',
                prefer_practitioner: '',
                appointment_date: '',
                appointment_time: '',
            }
        },

        watch: {
            appointment() {
                this.editAppointment = this.appointment;

                this.appointment_date = this.editAppointment.appointment_date;
                this.appointment_time = this.editAppointment.appointment_time;
                this.referal = this.appointment.referal; 
                this.department = this.appointment.department; 
                this.condition = this.appointment.condition; 
                this.service = this.appointment.service; 
                this.prefer_practitioner = this.Practitioner;
            },
        },

        computed: {
            clinic() {
                if (this.editAppointment.health_service_type === "Chas Clinic") {
                    return this.editAppointment.chas_clinic
                }
                if (this.editAppointment.health_service_type === "Dental") {
                    return this.editAppointment.dental
                }
                if (this.editAppointment.health_service_type === "Polyclinic") {
                    return this.editAppointment.polyclinic
                }
                if (this.editAppointment.health_service_type === "Hospital") {
                    return this.editAppointment.hospital
                }
            },

            Role() {
                 if (this.editAppointment.health_service_type === "Dental") {
                    return "Preferred Dentist"
                }
                return "Preferred Doctor"
            },

            Practitioner() {
                 if (this.editAppointment.health_service_type === "Dental") {
                    return this.editAppointment.prefer_dentist;
                }
                return this.editAppointment.prefer_doctor;
            },

            isDental() {
                return this.editAppointment.health_service_type === 'Dental';
            },

            isChasclinic() {
                return this.editAppointment.health_service_type === 'Chas Clinic';
            },

            isHospital() {
                return this.editAppointment.health_service_type === 'Hospital';
            },

            isPolyclinic() {
                return this.editAppointment.health_service_type === 'Polyclinic';
            },
      
        },

        methods: {
            updateAppointment(){
                axios.post('/api/appointment/update', {
                    id: this.appointment.id,
                    
                    health_service_type: this.editAppointment.health_service_type.split(" ").join(""),
                    department: this.department,
                    service: this.service,
                    
                    condition: this.condition,
                    prefer_doctor: this.prefer_practitioner,
                    prefer_dentist: this.prefer_practitioner,

                    referal: this.referal,
                    appointment_date: this.appointment_date,
                    appointment_time: this.appointment_time,
                })
                .then((res) => {
                    this.$emit('updateSuccess');
                })
                .catch((error) => {
                    this.error = error.response;
                })
            },

            hideModal() {
                this.$emit('hideModal');
            },
        }

    }
</script>

