<template>
  <div>
		<flash :message = "message"></flash>
		 
		<!-- calendar -->
		<div class = "tw-flex">
			<div class = "tw-3/4">
				<div class="col-md-12 control-section">
					<div class="content-wrapper">
						<ejs-schedule
						id='Schedule'
						ref="ScheduleObj"
						height="650px"
						:selectedDate='selectedDate'
						:eventSettings='eventSettings'
						:eventRendered="oneventRendered"
						:popupOpen="onPopupOpen"
						:actionComplete="onActionComplete"
						>
						</ejs-schedule>
					</div>
				</div>
			</div>

			<!-- legend -->
			<div class = "tw-w-1/4">
				<h6 class = "tw-underline tw-flex tw-justify-center">Legend</h6> 
				<div class="col-lg-3 property-section">
					<div id="property" class="property-panel-content">
						<div class="tw-flex">
							<div class="">
								<i class="fas fa-stop tw-text-black"></i>
							</div>
							<div class="tw-text-xs tw-ml-2">
								External Appointment
							</div>
						</div>

						<div class="tw-flex">
							<div class="">
								<i class="fas fa-stop tw-text-green"></i>
							</div>
							<div class="tw-text-xs tw-ml-2">
								Personal Timetable
							</div>
						</div>

						<div class="tw-flex">
							<div class="">
								<i class="fas fa-stop tw-text-orange"></i>
							</div>
							<div class="tw-text-xs tw-ml-2">
								Personal Appointment
							</div>
						</div>
						<div class="tw-flex">
							<div class="">
								<i class="fas fa-stop tw-text-indigo-light"></i>
							</div>
							<div class="tw-text-xs tw-ml-2">
								Companies Appointment
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div>
			<modal
				:appointment = "appointment"
				:show-modal = "showModal"
				@hideModal = "hideModal"
				@message = "updateCalendar"
			></modal>
		</div>
	</div>
</template>

<script>
	import "@syncfusion/ej2-base/styles/material.css";
	import "@syncfusion/ej2-buttons/styles/material.css";
	import "@syncfusion/ej2-calendars/styles/material.css";
	import "@syncfusion/ej2-dropdowns/styles/material.css";
	import "@syncfusion/ej2-inputs/styles/material.css";
	import "@syncfusion/ej2-navigations/styles/material.css";
	import "@syncfusion/ej2-popups/styles/material.css";
	import "@syncfusion/ej2-vue-schedule/styles/material.css";
	import "@syncfusion/ej2-vue-inputs/styles/material.css";
	import Vue from "vue";
	import { NumericTextBox } from '@syncfusion/ej2-inputs';
	import { createElement, extend, enableRipple } from "@syncfusion/ej2-base";
	import { DropDownList } from "@syncfusion/ej2-dropdowns";
	import { CheckBox, Button, CheckBoxPlugin } from "@syncfusion/ej2-vue-buttons";
	import { DataManager, WebApiAdaptor, Query } from "@syncfusion/ej2-data";
	import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";
	import GoogleCalendar from './GoogleCalendar.vue';
	import Flash from '../components/Flash.vue';
	import Modal from '../components/Modal.vue';
    import moment from 'moment'

	enableRipple(true);
	Vue.use(SchedulePlugin);
	Vue.use(CheckBoxPlugin);

	//Category:
	//An Appointment With other company:3, 
	//Your Personal Timetable:2
	//An external appointment before you book:1, 
	// Google:4

	export default Vue.extend({
		mounted() {
			this.retrieveAppointment();
			this.retrieveTimetable();	
		},

		components: {
            'flash': Flash,
			'modal': Modal,
			'googleCalendar':GoogleCalendar
		},

		props: ['appointments'],

		data() {
			return {
				message:'',
				googleCalendarId:'',
				showModal: null,
				appointment: null,
				 cssClass: 'schedule-add-remove-resources',
				scheduleData : [],
				isAppointment:true,
				readonly: false,
				eventSettings: {
					dataSource: extend([], this.scheduleData, null, true)
				},
				selectedDate: new Date(),
			};
		}, 	 
		provide: {
			schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
		},

		watch: {
			appointments(){
				
				for (var key in this.appointments) {
					if (this.appointments.hasOwnProperty(key)) {
						var index = this.scheduleData.find( calendar => calendar.Id === this.appointments[key].id );

						if(index == null)
							this.scheduleData.push({
								Id: this.appointments[key].id,
								Subject: this.appointments[key].subject,
								StartTime: this.appointments[key].StartTime,
								EndTime: this.appointments[key].EndTime,
								RecurrenceRule: this.appointments[key].recurrence_rule,
								IsAllDay: this.appointments[key].is_all_day,
								Description: this.appointments[key].description,

								IsAppointment: this.appointments[key].is_appointment,
								LimitedTo: this.appointments[key].limited_to,
								NoOfSlots: this.appointments[key].no_of_slots,
								Location: this.appointments[key].location,
								Price: this.appointments[key].price,
								CategoryColor: "#000000",
								
								IsReadonly: true,
								Category : 1,
							});
					}
				}	
				this.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = this.scheduleData;
				this.$refs.ScheduleObj.refreshEvents();
			},
		},

		methods: {
			onChange(args){
				 
				let scheduleObj = this.$refs.ScheduleObj;
				let value = parseInt((args.event.target).getAttribute('value'), 10);
				console.log(value)
				let resourceData = this.scheduleData.filter(function (data) { return data.Category == value; });
				  //console.log(this.scheduleData);
                if (args.checked) {
                    // scheduleObj.addResource(resourceData, 'Category', value );
					// scheduleObj.dataBind();
					//this.scheduleData = [];
					//this.retrieveGoogleCalendar();
                } else {
					 var temp = this.scheduleData.filter(function (data) { return data.Category != value;});
					 

					 if(temp.length != 0){
						 
							  this.scheduleData = [];
							  this.scheduleData = temp;
							this.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource =  temp;
							
						this.$refs.ScheduleObj.refreshEvents();
						console.log(this.scheduleData);
					 }
				
                }
            	
			}, 
			// retrieve the user's timetable from database
			retrieveTimetable(){
				var scope = this;
				axios.get('/api/timetable')
				.then((res) => {	
					res.data.timetables.forEach(element => { 

						//process date object
						scope.scheduleData.push({
							Id: element.id,
							Subject: element.subject,
							StartTime: element.StartTime,
							EndTime: element.EndTime,
							RecurrenceRule: element.recurrence_rule,
							IsAllDay: element.is_all_day,
							Description: element.description,

							IsAppointment: element.is_appointment,
							LimitedTo: element.limited_to,
							NoOfSlots: element.no_of_slots,
							Location: element.location,
							Price: element.price,
							CategoryColor: (element.is_appointment == true) ? "#7886d7" : "#38c172",
							Category : 2,
						}); 	
					
					}); 
					
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					scope.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = scope.scheduleData;
					scope.$refs.ScheduleObj.refreshEvents();
				});
				
			},

			retrieveAppointment(){
				var scope = this;
				axios.get('/api/appointment')
				.then((res) => {
					res.data.appointments.forEach(element => {  
						//process date object
					var index = this.scheduleData.find( calendar => calendar.Id === element.Id );
						if(index != null){
							var newItem = index;
							newItem.Category = 3;
							newItem.CategoryColor = "#f6993f";
						}else
							scope.scheduleData.push({
								Id: element.id,
								Subject: element.subject,
								StartTime: element.StartTime,
								EndTime: element.EndTime,
								RecurrenceRule: element.recurrence_rule,
								IsAllDay: element.is_all_day,
								Description: element.description,

								IsAppointment: element.is_appointment,
								LimitedTo: element.limited_to,
								NoOfSlots: element.no_of_slots,
								Location: element.location,
								Price: element.price,
								CategoryColor: "#f6993f",
								Category : 3,
							}); 
					}); 

				}).catch((error) => {
					console.log(error)
				}).then(() => {
					scope.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = scope.scheduleData;
					scope.$refs.ScheduleObj.refreshEvents();
				});
			},

			isCompany(){
				return this.$store.getters.isCompany
			},
			
			onActionComplete: function(event) {
				console.log(event)
				if (event.requestType == "eventCreated")
					this.createTimetable(event);
				else if (event.requestType == "eventChanged") 
					this.updateTimetable(event);
				else if (event.requestType == "eventRemoved")
					this.deleteTimetable(event);
					
			},

			createTimetable(event){ 
				var scope = this;
				axios.post('/api/timetable', {
					is_all_day: event.data.IsAllDay,
					start_time: event.data.StartTime,
					end_time: event.data.EndTime,

					description: event.data.Description,
					subject: event.data.Subject,
					location: event.data.Location,
					recurrence_rule: event.data.RecurrenceRule,

					is_appointment: event.data.IsAppointment,
					no_of_slots: event.data.NoOfSlots,
					limited_to: event.data.LimitedTo,
					price: event.data.Price
				}).then((res) => {

					var index = scope.scheduleData.find( calendar => calendar.Id === event.data.Id );
					var newItem = index;
					newItem.Category = 2;
					newItem.CategoryColor = (newItem.IsAppointment == true) ? "#7886d7" : "#38c172";

					if (res.data.id != event.data.Id){
						newItem.Id = res.data.id;
					}
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					scope.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = scope.scheduleData;
					scope.$refs.ScheduleObj.refreshEvents();
				});
			},

			//TBD UPDATE CATEGORY COLOR
			updateTimetable(event){
				axios.patch('/api/timetable/'+ event.data.Id, {
					is_all_day: event.data.IsAllDay,
					start_time: event.data.StartTime,
					end_time: event.data.EndTime,

					description: event.data.Description,
					subject: event.data.Subject,
					location: event.data.Location,
					recurrence_rule: event.data.RecurrenceRule,

					is_appointment: event.data.IsAppointment,
					no_of_slots: event.data.NoOfSlots,
					limited_to: event.data.LimitedTo,
					price: event.data.Price
				}).then((res) => {
					console.log(res);
				})
			},

			deleteTimetable(event){
				axios.delete('/api/timetable/'+ event.data[0].Id)
				.then((res) => {
					this.message = res.data.message
				})
			},

			deleteAppointment(){
				var scope = this;
				axios.delete('/api/appointment/'+ this.appointment.Id)
				.then((res) => {
					this.message = res.data.message

					var index = scope.scheduleData.find( calendar => calendar.Id === scope.appointment.Id );

					console.log(index);
					var newItem = index;
					newItem.Category = 1;
					newItem.CategoryColor = "#000000";

					// scope.scheduleData.splice(index, 1);

					this.$refs.ScheduleObj.refreshEvents();
				})
			},

			
			displayModal() {
				this.showModal = true;
			},

			hideModal() {
				this.showModal = false;
			},

			updateCalendar(payload) {
				this.message = payload;

				var sch_index = this.scheduleData.find( calendar => calendar.Id === this.appointment.Id );
				var app_index = this.appointments.find( calendar => calendar.id === this.appointment.Id );

				console.log(sch_index);
				console.log(app_index);

				if(sch_index != null && app_index != null){
					var newItem = sch_index;
					newItem.Category = 3;
					newItem.CategoryColor = "#f6993f";
				}

				this.$refs.ScheduleObj.refreshEvents();
			},

			oneventRendered: function(args) {
				let scheduleObj = this.$refs.ScheduleObj;
				let categoryColor = args.data.CategoryColor;
				if (!args.element || !categoryColor) {
					return;
				}
				if (scheduleObj.ej2Instances.currentView === "Agenda") {
					args.element.firstChild.style.borderLeftColor = categoryColor;
				} else {
					args.element.style.backgroundColor = categoryColor;
				}
			},

			before(date){
				return moment(date).format("DD/MM/YYYY") > moment().format("DD/MM/YYYY");
			},

			// Create required custom elements in initial time
			onPopupOpen: function(args) {
				
				var schedule = this.scheduleData.find( calendar => calendar.Id === args.data.Id);
				if (args.type === "QuickInfo"){
					if (schedule != undefined){
						if(schedule.Category == 1 && this.before(schedule.StartTime)){
							
							var elements = document.getElementsByClassName('e-delete');
								while(elements.length > 0){
									elements[0].parentNode.removeChild(elements[0]);
								}	 
							
							var elements2 = document.getElementsByClassName('e-edit');
							while(elements2.length > 0){
								elements2[0].parentNode.removeChild(elements2[0]);
							}
								
							this.appointment = schedule;
							
							let row = createElement("div", { className: "custom-button-row" });
							let formElement = args.element.querySelector(".e-popup-content");
							
							//custom button
							let container_button1 = createElement("div", {
								className: "custom-field-container-button1"
							});
							let inputEle_button1 = createElement("button", {
								className: "e-field",
								attrs: { name: "IsButton1" }
							});
							container_button1.appendChild(inputEle_button1);
							formElement.appendChild(container_button1);
							let button1 = new Button({
								content: "Book",
								disabled: false
							});
							container_button1.addEventListener(
								"click",
								this.displayModal,
								false
							);
							button1.appendTo(inputEle_button1);
							inputEle_button1.setAttribute("name", "IsButton1");  
						}
						//APPOINTMENT MADE BY OTHERS
						else if(schedule.Category == 3){
							var elements = document.getElementsByClassName('e-delete');
								while(elements.length > 0){
									elements[0].parentNode.removeChild(elements[0]);
								}	 
							
							var elements2 = document.getElementsByClassName('e-edit');
							while(elements2.length > 0){
								elements2[0].parentNode.removeChild(elements2[0]);
							}
							
							this.appointment = schedule;

							let row = createElement("div", { className: "custom-button-row" });
							let formElement = args.element.querySelector(".e-popup-content");
							
							//custom button
							let container_button2 = createElement("div", {
								className: "custom-field-container-button2"
							});
							let inputEle_button2 = createElement("button", {
								className: "e-field",
								attrs: { name: "IsButton2" }
							});
							container_button2.appendChild(inputEle_button2);
							formElement.appendChild(container_button2);
							let button2 = new Button({
								content: "Cancel Appointment",
								disabled: false
							});
							container_button2.addEventListener(
								"click",
								this.deleteAppointment,
								false
							);
							button2.appendTo(inputEle_button2);
							inputEle_button2.setAttribute("name", "IsButton2");  
						}
					}
				}
					 
				let isAppointment = false;
				let eventType = "";
				let NoOfSlot = 0;
				let Price = 0;
					
				if (args.type === "Editor" && this.isCompany()) {

					//add data to custom fields
					const result = this.scheduleData.find( calendar => calendar.Id === args.data.Id );

					if (result !== undefined){
						isAppointment = result.IsAppointment;
						eventType= result.LimitedTo;
						NoOfSlot = result.NoOfSlots;
						Price = result.Price;
					}

					console.log(args);
					if (!args.element.querySelector(".custom-field-row")) {

						let row = createElement("div", { className: "custom-field-row" });
						let formElement = args.element.querySelector(".e-schedule-form");
						formElement.firstChild.insertBefore(
							row,
							args.element.querySelector(".e-title-location-row")
						);


						//is appointment
						let container_checkbox1 = createElement("div", {
							className: "custom-field-container-checkbox1"
						});
						let inputEle_checkbox1 = createElement("input", {
							className: "e-field",
							attrs: { name: "IsAppointment" }
						});
						container_checkbox1.appendChild(inputEle_checkbox1);
						row.appendChild(container_checkbox1);
						var checkbox1 = new CheckBox({
							label: "Requires Appointment",
							checked: isAppointment
						});
						checkbox1.appendTo(inputEle_checkbox1);
						inputEle_checkbox1.setAttribute("name", "IsAppointment");

						let container = createElement("div", {
							className: "custom-field-container"
						});
						let inputEle = createElement("input", {
							className: "e-field",
							attrs: { name: "LimitedTo" }
						});
						container.appendChild(inputEle);
						row.appendChild(container);
						var dropDownList = new DropDownList({
							dataSource: [
								{ text: "Public", value: "public" },
								{ text: "Invites Only", value: "invites only" },
								{ text: "Private", value: "private" },
							],
							fields: { text: "text", value: "value" },
							value: eventType,
							floatLabelType: "Always",
							placeholder: "This event is open to:"
						});
						dropDownList.appendTo(inputEle);
						inputEle.setAttribute("name", "LimitedTo");
					

						//number of slots							
						let container2 = createElement("div", {
							className: "custom-field-container"
						});
						let inputEle2 = createElement("input", {
							className: "e-field",
							attrs: { name: "NoOfSlots" }
						});
						container2.appendChild(inputEle2);
						row.appendChild(container2);
						var numeric = new NumericTextBox({ min: 1, max: 500, format:'0', 
							floatLabelType: "Always",
							placeholder: "Number of slots:",
							value: NoOfSlot

						});
						numeric.appendTo(inputEle2);
						inputEle2.setAttribute("name", "NoOfSlots");

						//price
						let container3 = createElement("div", {
							className: "custom-field-container"
						});
						let inputEle3 = createElement("input", {
							className: "e-field",
							attrs: { name: "Price" }
						});
						container3.appendChild(inputEle3);
						row.appendChild(container3);
						var price = new NumericTextBox({
							format: 'c2',
							value: Price,
							placeholder: 'Price',
							floatLabelType: 'Auto'
						});
						price.appendTo(inputEle3);
						inputEle3.setAttribute("name", "Price");

					}
				}
			},
		}
	});
</script>
