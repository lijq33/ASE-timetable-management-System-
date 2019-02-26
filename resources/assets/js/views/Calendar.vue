<template>
	<div>
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
					:cssClass='cssClass'
				>
				</ejs-schedule>
			</div>
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
	import { createElement, extend } from "@syncfusion/ej2-base";
	import { DropDownList } from "@syncfusion/ej2-dropdowns";
	import { CheckBox } from "@syncfusion/ej2-vue-buttons";
	import { DataManager, WebApiAdaptor } from "@syncfusion/ej2-data";
	import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";
	Vue.use(SchedulePlugin);

	export default Vue.extend({
		data() {
			var dataManger = new DataManager({
				url: "https://js.syncfusion.com/demos/ejservices/api/Schedule/LoadData",
				adaptor: new WebApiAdaptor(),
				crossDomain: true
			});

			let scheduleData = [
				{
					Id: 1,
					Subject: "Explosion of Betelgeuse Star",
					StartTime: new Date(2018, 1, 11, 9, 30),
					EndTime: new Date(2018, 1, 11, 11, 0),
					CategoryColor: "#1aaa55",
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
					IsSunny: true,
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
				//console.log(element.Subject)
				if (element.Subject === "Blue Moon Eclipse") {
					this.isSunny = element.IsSunny;
				}
			});

			return {
				eventSettings: {
					dataSource: extend([], scheduleData, null, true)
				},
				isSunny: [],
				selectedDate: new Date(2018, 1, 15),
				cssClass: "block-events"
			};
		},

		provide: {
			schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
		},

		methods: {

			//New event, update event
			onActionComplete: function(event) {
				if (event.requestType == "eventChanged") 
					this.createTimetable();
				else if (event.requestType == "eventCreated")
					this.updateTimetable();
			},

			createTimetable(){
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

			updateTimetable(){
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
				})
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


			onPopupOpen: function(args) {
				console.log(args);
				if (args.type === "Editor") {
					// Create required custom elements in initial time
					if (!args.element.querySelector(".custom-field-row")) {

						let row = createElement("div", { className: "custom-field-row" });
						let formElement = args.element.querySelector(".e-schedule-form");
						formElement.firstChild.insertBefore( row, args.element.querySelector(".e-title-location-row"));

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
							placeholder: "Customized Event Type"
						});
						
						dropDownList.appendTo(inputEle);
						inputEle.setAttribute("name", "EventType");

						//checkbox 1
						let container_checkbox1 = createElement("div", {
							className: "custom-field-container-checkbox1"
						});
						
						let inputEle_checkbox1 = createElement("input", {
							className: "e-field",
							attrs: { name: "IsSunny" }
						});

						container_checkbox1.appendChild(inputEle_checkbox1);
						row.appendChild(container_checkbox1);
						
						var checkbox1 = new CheckBox({
							label: "Sunny",
							checked: this.isSunny
						});

						checkbox1.appendTo(inputEle_checkbox1);
						inputEle_checkbox1.setAttribute("name", "IsSunny");
					}
				}
			}
		}
	});
</script>

<style>
	.custom-field-row {
		margin-bottom: 20px;
	}
	.block-events.e-schedule .template-wrap {
		width: 100%;
	}

	.block-events.e-schedule .e-vertical-view .e-resource-cells {
		height: 58px;
	}

	.block-events.e-schedule .e-timeline-view .e-resource-left-td,
	.block-events.e-schedule .e-timeline-month-view .e-resource-left-td {
		width: 170px;
	}

	.block-events.e-schedule .e-resource-cells.e-child-node .employee-category,
	.block-events.e-schedule .e-resource-cells.e-child-node .employee-name {
		padding: 5px;
	}

	.block-events.e-schedule .employee-image {
		width: 45px;
		height: 40px;
		float: left;
		border-radius: 50%;
		margin-right: 10px;
	}

	.block-events.e-schedule .employee-name {
		font-size: 13px;
	}

	.block-events.e-schedule .employee-designation {
		font-size: 10px;
	}
</style>