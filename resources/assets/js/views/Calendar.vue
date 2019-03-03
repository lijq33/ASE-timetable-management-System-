<template>
  <div>
		<!-- calendar -->
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
				:readonly="readonly"
				>
				</ejs-schedule>
			</div>
		</div>

		<div>
			<!-- Custom Edit Modal Component -->
			<b-modal
				ref="myModalRef"
				hide-footer
				title="Using Component Methods"
			>
				<div class="d-block text-center">
					<h3>Hello From My Modal!</h3>
				</div>
				<b-button
					class="mt-3"
					variant="outline-danger"
					block
					@click="hideModal"
				>Close Me</b-button>
		</b-modal>
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
	import { CheckBox, Button } from "@syncfusion/ej2-vue-buttons";
	import { DataManager, WebApiAdaptor, Query } from "@syncfusion/ej2-data";
	import { Modal } from "bootstrap-vue/es/components";
	import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";

	enableRipple(true);
	Vue.use(SchedulePlugin);

	let scheduleData = [];
	
	export default Vue.extend({
		created() {
			this.retrieveTimetable();	
			this.retrieveAppointment();
		},

		data() {
			return {
				readonly: false,
				eventSettings: {
					dataSource: extend([], scheduleData, null, true)
				},
				selectedDate: new Date(2018, 10, 15),
			};
		},
		
		provide: {
			schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
		},

		methods: {

			// retrieve the user's timetable from database
			retrieveTimetable(){
				var scope = this;
				axios.get('/api/timetable')
				.then((res) => {	
					res.data.timetables.forEach(element => { 
						 
						//process date object
						scheduleData.push({
							Id: element.id,
							Subject: element.subject,
							StartTime: element.StartTime,
							EndTime: element.EndTime,
							// IsAllDay: element.is_all_day,
							// Description: element.description,
							// RecurrenceRule: element.recurrence_rule,
							
							// IsAppointment: element.is_appointment,
							// LimitedTo: element.limited_to,
							// NoOfSlots: element.no_of_slots,
							// Location: element.location,
							// price: element.price,
							// CategoryColor: "#A4D2D4",
						}); 	

						scope.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = scheduleData;
					
					}); 
					
				}).catch((error) => {
					console.log(error)
				})
				
			},

			retrieveAppointment(){
				axios.get('/api/appointment')
				.then((res) => {
					// this.eventSettings.dataSource = res.data.timetables;
				}).catch((error) => {

				})
			},

			//New event, update event
			onActionComplete: function(event) {
				if (event.requestType == "eventCreated")
					this.createTimetable(event);
				else if (event.requestType == "eventChanged") 
					this.updateTimetable(event);
				else if (event.requestType == "eventRemoved")
					this.deleteTimetable(event);
			},

			//done
			createTimetable(event){
				console.log(event)
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
					limited_to: event.data.LimitedTo
				}).then((res) => {
					console.log(res);
				})
			},

			updateTimetable(event){
				axios.post('/api/timetable/update', {
					is_all_day: this.isAllDay,
					date: this.date,
					start_time: this.startTime,
					end_time: this.endTime,

					event_type: this.LimitedTo,
					subject: this.subject,
					is_appointment: this.isAppointment,
					description: this.description,
					location: this.location,
				}).then((res) => {
					//update timetable id with the response
					console.log(res);
				})
			},

			deleteTimetable(event){
				axios.delete('/api/timetable/'+ event.data[0].Id)
				.then((res) => {
					console.log(res);
				})
			},

		
			makeAppointment(id){
				this.timetableId = id;
				this.showModal= true;	 
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
			
			customButtonEvent: function(event) {
				this.$refs.myModalRef.show();
			},
			
			hideModal() {
				this.$refs.myModalRef.hide();
			},


			// Create required custom elements in initial time
			onPopupOpen: function(args) {
				console.log(args.type);
			if (args.type === "Editor") {
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
						checked: false
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
						value: "",
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
						placeholder: "Number of slots:"
					 });
					numeric.appendTo(inputEle2);
					inputEle2.setAttribute("name", "NoOfSlots");

					//price
					let container3 = createElement("div", {
						className: "custom-field-container"
					});
					let inputEle3 = createElement("input", {
						className: "e-field",
						attrs: { name: "price" }
					});
					container3.appendChild(inputEle3);
					row.appendChild(container3);
					var price = new NumericTextBox({
						format: 'c2',
						value: '00',
						placeholder: 'price',
						floatLabelType: 'Auto'
					});
					price.appendTo(inputEle3);
					inputEle3.setAttribute("name", "price");

				}
			}
			}
		}
		});
</script>

<style>
	.e-time-zone-container{
		visibility:hidden;
	}
</style>