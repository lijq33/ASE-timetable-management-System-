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
import Vue from "vue";
import { createElement, extend, enableRipple } from "@syncfusion/ej2-base";
import { DropDownList } from "@syncfusion/ej2-dropdowns";
import { CheckBox, Button } from "@syncfusion/ej2-vue-buttons";
import { DataManager, WebApiAdaptor, Query } from "@syncfusion/ej2-data";
import { Modal } from "bootstrap-vue/es/components";
import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";

enableRipple(true);
Vue.use(SchedulePlugin);

export default Vue.extend({
	mounted() {
		retrieveTimetable();	
	},

  	data() {
    	let scheduleData = [
			{
				Id: 1,
				Subject: "Not Available",
				StartTime: new Date(2018, 1, 11, 9, 30),
				EndTime: new Date(2018, 1, 11, 11, 0),
				CategoryColor: "#D4D2D4",
				IsBlock: true
			},
			{
				Id: 2,
				Subject: "Thule Air Crash Report",
				StartTime: new Date(2018, 1, 12, 12, 0),
				EndTime: new Date(2018, 1, 12, 14, 0),
				CategoryColor: "#357cd2"
			},
			{
				Id: 3,
				Subject: "Blue Moon Eclipse",
				StartTime: new Date(2018, 1, 13, 9, 30),
				EndTime: new Date(2018, 1, 13, 11, 0),
				CategoryColor: "#7fa900",
				IsAppointment: true,
				IsAllDay: true
			},
			{
				Id: 4,
				Subject: "Meteor Showers in 2018",
				StartTime: new Date(2018, 1, 14, 13, 0),
				EndTime: new Date(2018, 1, 14, 14, 30),
				CategoryColor: "#ea7a57"
			},
			{
				Id: 5,
				Subject: "Milky Way as Melting pot",
				StartTime: new Date(2018, 1, 15, 12, 0),
				EndTime: new Date(2018, 1, 15, 14, 0),
				CategoryColor: "#00bdae"
			}
    	];

		//process custom field
		scheduleData.forEach(element => {
			if (element.Subject === "Blue Moon Eclipse") {
				this.isAppointment = element.IsAppointment;
			}
		});

		return {
			readonly: false,
			isAppointment: [],
			eventSettings: {
				dataSource: extend([], scheduleData, null, true)
			},
			selectedDate: new Date(2018, 1, 15)
		};
	},
	
	provide: {
		schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
	},

  	methods: {

		//New event, update event
		onActionComplete: function(event) {
			if (event.requestType == "eventChanged") 
				this.updateTimetable(event);
			else if (event.requestType == "eventCreated")
				this.createTimetable(event);
			else if (event.requestType == "eventRemoved")
				this.deleteTimetable(event);
		},

		createTimetable(event){
			axios.post('/api/timetable', {

				is_all_day: this.isAllDay,
				date: this.date,
				start_time: this.startTime,
				end_time: this.endTime,

				event_type: this.eventType,
				subject: this.subject,
				is_appointment: this.isAppointment,
				description: this.description,
				location: this.location,
			})
		},

		updateTimetable(event){
			axios.post('/api/timetable/update', {
				is_all_day: this.isAllDay,
				date: this.date,
				start_time: this.startTime,
				end_time: this.endTime,

				event_type: this.eventType,
				subject: this.subject,
				is_appointment: this.isAppointment,
				description: this.description,
				location: this.location,
			}).then((res) => {
				//update timetable id with the response
			})
		},

		deleteTimetable(event){
			axios.post('/api/timetable/'+ event.data.Id)
			.then((res) => {
				console.log(res);
			})
		},
		
		retrieveTimetable(){
			axios.get('/api/timetable')
			.then((res) => {
				console.log(res);
			}).error({

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

		onPopupOpen: function(args) {
		console.log(args);
		if (args.type === "Editor") {
			// Create required custom elements in initial time
			if (!args.element.querySelector(".custom-field-row")) {
				let row = createElement("div", { className: "custom-field-row" });
				let formElement = args.element.querySelector(".e-schedule-form");
				formElement.firstChild.insertBefore(
					row,
					args.element.querySelector(".e-title-location-row")
				);
				let container = createElement("div", {
					className: "custom-field-container"
				});
				let inputEle = createElement("input", {
					className: "e-field",
					attrs: { name: "EventType" }
				});
				container.appendChild(inputEle);
				row.appendChild(container);
				var dropDownList = new DropDownList({
					dataSource: [
						{ text: "Public Event", value: "public-event" },
						{ text: "Maintenance", value: "maintenance" },
						{ text: "Commercial Event", value: "commercial-event" },
						{ text: "Family Event", value: "family-event" }
					],
					fields: { text: "text", value: "value" },
					value: "",
					floatLabelType: "Always",
					placeholder: "Event Type"
				});
				dropDownList.appendTo(inputEle);
				inputEle.setAttribute("name", "EventType");

				//checkbox 1
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
					label: "Appointment",
					checked: this.isAppointment
				});
				checkbox1.appendTo(inputEle_checkbox1);
				inputEle_checkbox1.setAttribute("name", "IsAppointment");

				//custom button
				let container_button1 = createElement("div", {
					className: "custom-field-container-button1"
				});
				let inputEle_button1 = createElement("button", {
					className: "e-field",
					attrs: { name: "IsButton1" }
				});
				container_button1.appendChild(inputEle_button1);
				row.appendChild(container_button1);
				let button1 = new Button({
					content: "More",
					disabled: false
				});
				container_button1.addEventListener(
					"click",
					this.customButtonEvent,
					false
				);
				button1.appendTo(inputEle_button1);
				inputEle_button1.setAttribute("name", "IsButton1");
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