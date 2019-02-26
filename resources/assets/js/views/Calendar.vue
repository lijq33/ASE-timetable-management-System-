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
					@change="test"
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
	import { DataManager, WebApiAdaptor } from "@syncfusion/ej2-data";
	import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";
	Vue.use(SchedulePlugin);

	export default Vue.extend({
		data: function() {
			var dataManger = new DataManager({
			url: "https://js.syncfusion.com/demos/ejservices/api/Schedule/LoadData",
			adaptor: new WebApiAdaptor(),
			crossDomain: true
			});

			var scheduleData = [
			{
				Id: 1,
				Subject: "Explosion of Betelgeuse Star",
				StartTime: new Date(2018, 1, 11, 9, 30),
				EndTime: new Date(2018, 1, 11, 11, 0),
				CategoryColor: "#1aaa55"
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
				CategoryColor: "#7fa900"
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

			return {
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
								{ text: "Public", value: "public" },
								{ text: "Invites Only", value: "invite" },
								{ text: "Private", value: "private" },
							],
							fields: { text: "text", value: "value" },
							value: "",
							floatLabelType: "Always",
							placeholder: "Open To"
						});
						dropDownList.appendTo(inputEle);
						inputEle.setAttribute("name", "EventType");
					}
				}
			},
			 onAddClick: function () {
				let Data = [{
						Id: 3,
						Subject: 'Conference',
						StartTime: new Date(2018, 1, 12, 9, 0),
						EndTime: new Date(2018, 1, 12, 10, 0),
						IsAllDay: true
					},{
						Id: 4,
						Subject: 'Meeting',
						StartTime: new Date(2018, 1, 15, 10, 0),
						EndTime: new Date(2018, 1, 15, 11, 30),
						IsAllDay: false
						}];
				this.$refs.scheduleObj.addEvent(Data);
			},
			test(){
				console.log("test");
			}
		}
	});
</script>


<style>
	.custom-field-row {
		margin-bottom: 20px;
	}
</style>