<template>
  <div>
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
		<b-button variant="outline-primary" @click = "retrieveGoogleCalendar">Connect</b-button>
	</div>
	<div v-else>
		<img src = "/assets/img/loader.gif" alt = "Loading...">
	</div>

	</div>
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
                      if(status == "success"){
						scope.connectStatus = true;
						scope.processGoogleCalendarData(scope.googleCalendar);
                     }
                }
            }) 
        },

		processGoogleCalendarData(e) {
			
			var items = e.items;
			console.log(items);
			if (items.length > 0) {
				for (var i = 0; i < items.length; i++) {
					var event = items[i];
					var when = event.start.dateTime;
					var start = event.start.dateTime;
					var end = event.end.dateTime;
					if (!when) {
						when = event.start.date;
						start = event.start.date;
						end = event.end.date;
					}
					axios.post('/api/timetable', {
						is_all_day: !event.start.dateTime,
						start_time: start,
						end_time: end,

						subject: event.summary,
						description: event.description,
						location: event.location,

						limited_to: "private",
					}).then((res) => {
					}).catch((error) => {
						console.log(error)
					})
				}
				
			}  

		},
	}
}
</script>