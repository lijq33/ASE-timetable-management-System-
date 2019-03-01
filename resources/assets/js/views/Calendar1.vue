<template>

  <div>

    <div>
      <!-- Google Calendar Modal Component -->
      <b-button
        @click="showGoogleCalendar"
        ref="btnGoogleCalendarShow"
      >Google Calendar</b-button>

      <b-modal
        ref="btnGoogleCalendarShow"
        hide-footer
        title="Google Calendar Sychronization"
      >
        <div class="d-block text-center">
          <h3>Status:</h3>
        </div>
        <b-button
          class="mt-3"
          variant="outline-danger"
          block
          @click="hideGoogleCalendar"
        >Close Me</b-button>
      </b-modal>
    </div>

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
import {
  SchedulePlugin,
  Day,
  Week,
  WorkWeek,
  Month,
  Agenda,
  View,
  Resize,
  DragAndDrop
} from "@syncfusion/ej2-vue-schedule";
enableRipple(true);
Vue.use(SchedulePlugin);

let scheduleData = [
  {
    Id: 1,
    Subject: "Not Available",
    StartTime: new Date(2018, 10, 11, 9, 30),
    EndTime: new Date(2018, 10, 11, 11, 0),
    CategoryColor: "#D4D2D4",
    IsBlock: true
  }
];

export default Vue.extend({
  mounted() {
    var scope = this;
    //google calender api
    var calendarId = "5105trob9dasha31vuqek6qgp0@group.calendar.google.com";
    var publicKey = "AIzaSyD76zjMDsL_jkenM5AAnNsORypS1Icuqxg";

    axios
      .get(
        "https://www.googleapis.com/calendar/v3/calendars/" +
          calendarId +
          "/events?key=" +
          publicKey
      )
      .then(function(response) {
        // handle success
        //process data
        scope.processGoogleCalendarData(response.data);
      })
      .catch(function(error) {
        // handle error
        console.log(error);
      });
  },
  data() {
    //process custom field
    scheduleData.forEach(element => {
      //console.log(element.Subject)
      if (element.Subject === "Blue Moon Eclipse") {
        this.isAppointment = element.IsAppointment;
      }
    });

    return {
      readonly: false,
      googleCalendarData: [],
      isAppointment: [],
      eventSettings: {
        dataSource: extend([], scheduleData, null, true)
      },
      selectedDate: new Date(2018, 10, 15)
    };
  },
  provide: {
    schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
  },
  methods: {
    processGoogleCalendarData(e) {
      var items = e.items;
      var scheduleData1 = [];
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
          scheduleData1.push({
            Id: event.id,
            Subject: event.summary,
            StartTime: new Date(start),
            EndTime: new Date(end),
            IsAllDay: !event.start.dateTime
          });
        }
      }
      this.googleCalendarData = scheduleData1;
    },

    showGoogleCalendar() {
      //this.$refs.btnGoogleCalendarShow.show();

      this.googleCalendarData.forEach(element => {
        //console.log(element.Subject)
        scheduleData.push(element);
      });

      //refresh calendar data
      this.$refs.ScheduleObj.ej2Instances.eventSettings.dataSource = scheduleData;
    },
    hideGoogleCalendar() {
      this.$refs.btnGoogleCalendarShow.hide();
    },
    //New event, update event
    onActionComplete: function(event) {
      console.log(event);
      if (event.requestType == "eventChanged") console.log("update data");
      else if (event.requestType == "eventCreated")
        console.log("saved settings");
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

