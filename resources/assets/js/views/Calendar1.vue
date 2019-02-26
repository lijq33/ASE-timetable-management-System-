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
import { DataManager, WebApiAdaptor } from "@syncfusion/ej2-data";
import { SchedulePlugin,	Day, Week, WorkWeek, Month, Agenda, View, Resize, DragAndDrop } from "@syncfusion/ej2-vue-schedule";
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
            selectedDate: new Date(2018, 1, 15),
            cssClass: 'block-events',
        };
    },
    provide: {
        schedule: [Day, Week, WorkWeek, Month, Agenda, Resize, DragAndDrop]
    },
    methods: {
        //New event, update event
        onActionComplete: function(event) {
            console.log(event);

            if(event.requestType == 'eventChanged')
                console.log("update data");
            else if (event.requestType == 'eventCreated')
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


        //POPUP = selecting a slot
        onPopupOpen: function(args) {
            //Editor == more detail
            if (args.type === "Editor") {
                if (!args.element.querySelector(".custom-field-row")) {
                    
                    let row = createElement("div", { 
                        className: "custom-field-row" 
                    });

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
                }

                if (!args.element.querySelector(".custom-field-row2")) {
                    let row = createElement("div", { className: "custom-field-row2" });
                    let formElement = args.element.querySelector(".e-schedule-form");
                    formElement.firstChild.insertBefore(
                        row,
                        args.element.querySelector(".e-title-location-row2")
                    );
                    let container2 = createElement("div", {
                        className: "custom-field-container2"
                    });
                    let inputEle = createElement("input", {
                        className: "e-field2",
                        attrs: { name: "EventType" }
                    });
                    container2.appendChild(inputEle);
                    row.appendChild(container2);
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
                padding: 5px
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