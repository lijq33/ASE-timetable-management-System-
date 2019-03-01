<template>
    <div>
        <flash :message = "message"></flash>
        <table id="appointment" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Description</th>
                <th>Location</th>
                <th>Appointment Time</th>
                <th>Appointment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for = "(appointment, index) in Appointment" :key = "index">
                <td>{{appointment.subject}}</td>
                <td>{{appointment.description}}</td>
                <td>{{appointment.location}}</td>
                <td>{{appointment.startTime}} - {{appointment.endTime}}</td>
                <td>{{appointment.date}}</td>
                <td> 
                    <span class = "tw-flex tw-justify-around tw-items-center" v-if = "appointment.status === 'booked'">
                        <i class="fas fa-trash-alt tw-cursor-pointer"  @click = "deletes(appointment)"></i>
                        <i class="fas fa-pencil-alt tw-cursor-pointer" @click = "update(appointment)"></i>
                    </span>
                </td>
            </tr>  
        </tbody>
    </table>
    
    <b-modal title="Alert" v-model = "modalShow" hide-footer header-bg-variant="warning">
        <div class = "tw-w-full">
            This action cannot be <span class ="tw-font-bold">reverse</span>. 
        </div>
        <div class = "tw-w-full">
            Are you sure that you would like to cancel the appointment?
        </div>
        <div class = "tw-border-t tw-pt-4 tw-mt-4 tw-flex tw-justify-end tw-w-full">
            <button class = "tw-mr-2 btn btn-secondary" id='dontDeleteButton'>Cancel</button>
            <button class = "tw-ml-2 btn btn-primary" id='deleteButton'>Delete</button>
        </div>
    </b-modal>

    <b-modal ref="myModalRef" size="lg" hide-footer title = "Current Appointment Details:">
        <edit-appointment :appointment = "editAppointment"
            @hideModal = "hideModal"
            @updateSuccess = "updateSuccess"
        >
        </edit-appointment>
    
    </b-modal>

    </div>
</template>

<script>   

    import Edit from './Edit';
    import Flash from '../../components/Flash.vue';
    
    export default {
        props: ['appointment'],

        components: {
            'flash': Flash,
            'edit-appointment' : Edit
        },
        
        mounted() {
            this.getAppointment();

            setTimeout(function() { 
                $("#appointment").DataTable(); 
            }, 2000);
        },

        data() {
            return {
                appointments: [],
                error: '',

                confirmDelete:false,
                modalShow: false,

                editAppointment: '',
                message:'',
            }
        },

        methods: {
            getAppointment() {
                axios.get('/api/appointment/get')
                .then((res) => {
                    this.appointment = res.data.appointment;
                })
                .catch((error) => {
                    this.error = error.response;
                })
            },

            deletes(appointment) {
                this.modalShow = true;
                var scope = this;
                
                let promise = new Promise(function(resolve, reject) {
                    let deleteButton = document.getElementById('deleteButton');
                    deleteButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        resolve();
                    });
                    let dontDeleteButton = document.getElementById('dontDeleteButton');
                    dontDeleteButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        reject();
                    });

                });
                
                promise.then(function() { 
                    axios.post('/api/appointment/delete', {
                        appointment_id: appointment.id,
                    })
                    .then((res) => {
                        scope.message = "We've successfully cancel your appointment!";
                        scope.resetAppointment();
                        scope.getAppointment();
                    })
                    .catch((error) => {
                        scope.error = error.response;
                    })
                });
            },

            update(appointment) {
                this.$refs.myModalRef.show()
                this.editAppointment = appointment;
            },

            hideModal() {
                this.$refs.myModalRef.hide()
            },

            updateSuccess(){
                this.hideModal();
                this.message = "We've successfully update your appointment details!";
                this.resetAppointment();
                this.getAppointment();
            },
       
            resetAppointment() {
                this.appointment = [];
            }
      
        }
    }
</script>

        