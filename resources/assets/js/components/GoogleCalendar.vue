<template>
  <div>
    <b-button @click="showModal" id="showBtn">Google Calendar</b-button>
    
    <b-modal ref="myModalRef" hide-footer title="Google Calendar">
      <!-- status -->
      <label class="col-form-label text-md-right">Status: </label>

     <!-- success --> 
     <b-alert v-if ="connectStatus" variant="success" show>Connected</b-alert>

     <!-- failed --> 
     <b-alert v-if ="!connectStatus" variant="danger" show>Not Connected</b-alert>

      <div v-if ="!connectStatus">
        <b-form-group label-cols="4" label-cols-lg="2" label="Google Calendar ID" label-for="input_default">
            <b-form-input v-model="googleIdInput" type="text" />
         </b-form-group>

         <b-form-group label-cols="4" label-cols-lg="2" label="Api Key" label-for="input_default">
            <b-form-input v-model="googleApiKeyInput" type="text" />
         </b-form-group>
        
         <div v-if = "!isLoading">
            <b-button variant="outline-primary" @click="retrieveGoogleCalendar">Connect</b-button>
        </div>
        <div v-else>
            <img src = "/assets/img/loader.gif" alt = "Loading...">
        </div>

      </div>
     
      <b-button class="mt-3" variant="outline-danger" block @click="hideModal">Close</b-button>
      
    </b-modal>
  </div>
</template>

<script>
  export default {
     data() {
      return {
        isLoading: false,
        googleId:'',
        connectStatus:false,
        googleIdInput: '',
        googleApiKeyInput:'',
        googleCalendar: [],
      }
    },
    methods: {
        retrieveGoogleCalendar(){
            var scope = this;
             this.isLoading = true;
            //google calender api
            //var calendarId = "lie6f2e16ef2cvttkamf3trdu4@group.calendar.google.com";
            //var publicKey = "AIzaSyAIAkiam90N9R-_Nh72fL6MpGJpKUBDWgQ";
            
            $.ajax({
                url: "https://www.googleapis.com/calendar/v3/calendars/" +
                    this.googleIdInput + "/events?key=" +this.googleApiKeyInput,
                type: "GET",
                success: function (data, status, jqXHR) { 
                    scope.googleCalendar = data;
                },
                error: function (jqXHR, status, err) {
                    scope.isLoading = false;
                    scope.connectStatus = false;
                    console.log("Local error callback.");
                },
                complete: function (jqXHR, status) {
                      scope.isLoading = false;
                     //pass data to calendar.vue
                     if(status == "success"){
                        scope.connectStatus = true;
                        scope.$emit('googleCalendarData', scope.googleCalendar);
                     }
                }
            }) 
        },

      showModal() {
        this.$refs.myModalRef.show()
      },
      hideModal() {
          console.log(this.googleIdInput)
        this.$refs.myModalRef.hide()
      }
    }
  }
</script>