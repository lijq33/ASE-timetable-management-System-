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
            <tr v-for = "(appointment, index) in appointments" :key = "index">
                <td>{{appointment.subject}}</td>
                <td>{{appointment.description}}</td>
                <td>{{appointment.location}}</td>
                <td>{{appointment.start_time}} - {{appointment.end_time}}</td>
                <td>{{appointment.start_date}}</td>
                <td> 
                    <span class = "tw-flex tw-justify-around tw-items-center" v-if = "before(appointment.start_date)">
                        <i class="fas fa-trash-alt tw-cursor-pointer"  @click = "deletes(appointment)"></i>
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
    </div>
</template>

<script>   

    import Flash from '../components/Flash.vue';
    import moment from 'moment';
    
    export default {
        components: {
            'flash': Flash,
        },
        
        mounted() {
			this.retrieveAppointment();

            setTimeout(function() { 
                $("#appointment").DataTable(); 
            }, 2000);
        },

        data() {
            return {
                appointments: [],
                error: '',
                confirmDelete:false,
                message:'',
                modalShow: false,
            }
        },

        methods: {
			retrieveAppointment(){
				var scope = this;
				axios.get('/api/appointment')
				.then((res) => {
					this.appointments = res.data.appointments;
				}).catch((error) => {
					console.log(error)
				});
            },

            before(date) {
				return moment(date).format("DD/MM/YYYY") > moment().format("DD/MM/YYYY");
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
                    axios.delete('/api/appointment/'+ appointment.id, {
                        appointment_id: appointment.id,
                    })
                    .then((res) => {
                        scope.message = "We've successfully cancel your appointment!";
                        scope.resetAppointment();
                        scope.retrieveAppointment();
                    })
                    .catch((error) => {
                        scope.error = error.response;
                    })
                });
            },



            resetAppointment() {
                this.appointment = [];
            }
      
        }
    }
</script>

        