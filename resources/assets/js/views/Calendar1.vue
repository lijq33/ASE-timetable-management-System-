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
        >
        </ejs-schedule>
      </div>
    </div>

    <div>
      <!-- Modal Component -->
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
import { DataManager, WebApiAdaptor } from "@syncfusion/ej2-data";
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
      },
      {
        Id: 6,
        Subject: "Mysteries of Bermuda Triangle",
        StartTime: new Date(2018, 1, 15, 9, 30),
        EndTime: new Date(2018, 1, 15, 11, 0),
        CategoryColor: "#f57f17"
      },
      {
        Id: 7,
        Subject: "Glaciers and Snowflakes",
        StartTime: new Date(2018, 1, 16, 11, 0),
        EndTime: new Date(2018, 1, 16, 12, 30),
        CategoryColor: "#1aaa55"
      },
      {
        Id: 8,
        Subject: "Life on Mars",
        StartTime: new Date(2018, 1, 17, 9, 0),
        EndTime: new Date(2018, 1, 17, 10, 0),
        CategoryColor: "#357cd2"
      },
      {
        Id: 9,
        Subject: "Alien Civilization",
        StartTime: new Date(2018, 1, 19, 11, 0),
        EndTime: new Date(2018, 1, 19, 13, 0),
        CategoryColor: "#7fa900"
      },
      {
        Id: 10,
        Subject: "Wildlife Galleries",
        StartTime: new Date(2018, 1, 21, 11, 0),
        EndTime: new Date(2018, 1, 21, 13, 0),
        CategoryColor: "#ea7a57"
      },
      {
        Id: 11,
        Subject: "Best Photography 2018",
        StartTime: new Date(2018, 1, 22, 9, 30),
        EndTime: new Date(2018, 1, 22, 11, 0),
        CategoryColor: "#00bdae"
      },
      {
        Id: 12,
        Subject: "Smarter Puppies",
        StartTime: new Date(2018, 1, 9, 10, 0),
        EndTime: new Date(2018, 1, 9, 11, 30),
        CategoryColor: "#f57f17"
      },
      {
        Id: 13,
        Subject: "Myths of Andromeda Galaxy",
        StartTime: new Date(2018, 1, 7, 10, 30),
        EndTime: new Date(2018, 1, 7, 12, 30),
        CategoryColor: "#1aaa55"
      },
      {
        Id: 14,
        Subject: "Aliens vs Humans",
        StartTime: new Date(2018, 1, 5, 10, 0),
        EndTime: new Date(2018, 1, 5, 11, 30),
        CategoryColor: "#357cd2"
      },
      {
        Id: 15,
        Subject: "Facts of Humming Birds",
        StartTime: new Date(2018, 1, 20, 9, 30),
        EndTime: new Date(2018, 1, 20, 11, 0),
        CategoryColor: "#7fa900"
      },
      {
        Id: 16,
        Subject: "Sky Gazers",
        StartTime: new Date(2018, 1, 23, 11, 0),
        EndTime: new Date(2018, 1, 23, 13, 0),
        CategoryColor: "#ea7a57"
      },
      {
        Id: 17,
        Subject: "The Cycle of Seasons",
        StartTime: new Date(2018, 1, 12, 5, 30),
        EndTime: new Date(2018, 1, 12, 7, 30),
        CategoryColor: "#00bdae"
      },
      {
        Id: 18,
        Subject: "Space Galaxies and Planets",
        StartTime: new Date(2018, 1, 12, 17, 0),
        EndTime: new Date(2018, 1, 12, 18, 30),
        CategoryColor: "#f57f17"
      },
      {
        Id: 19,
        Subject: "Lifecycle of Bumblebee",
        StartTime: new Date(2018, 1, 15, 6, 0),
        EndTime: new Date(2018, 1, 15, 7, 30),
        CategoryColor: "#7fa900"
      },
      {
        Id: 20,
        Subject: "Sky Gazers",
        StartTime: new Date(2018, 1, 15, 16, 0),
        EndTime: new Date(2018, 1, 15, 18, 0),
        CategoryColor: "#ea7a57"
      }
    ];

    //process custom field
    scheduleData.forEach(element => {
      //console.log(element.Subject)
      if (element.Subject === "Blue Moon Eclipse") {
        this.isAppointment = element.IsAppointment;
      }
    });

    return {
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

