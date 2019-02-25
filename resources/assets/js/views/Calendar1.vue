<template>
  <div id='app'>
      <ejs-schedule height='550px' width='100%' :selectedDate='selectedDate'
      :eventSettings= 'eventSettings'>
        <e-views>
          <e-view option='Week' startHour='07:00' endHour='15:00' ></e-view>
          <e-view option='WorkWeek' startHour='10:00' endHour='18:00' ></e-view>
          <e-view option='Month' showWeekend=false></e-view>
        </e-views>
      </ejs-schedule>
  </div>
</template>
<script>

  import Vue from 'vue'; 
  import { extend } from '@syncfusion/ej2-base';
  import { SchedulePlugin,  WorkWeek, Month, Week } from '@syncfusion/ej2-vue-schedule';
  Vue.use(SchedulePlugin);
  export default {
    data () {
		var scheduleData=[
        {
            Id: 1,
            Subject: 'Conference',
            StartTime: new Date(2018, 1, 7, 10, 0),
            EndTime: new Date(2018, 1, 7, 11, 0),
            IsAllDay: false
        }, {
            Id: 2,
            Subject: 'Meeting - 1',
            StartTime: new Date(2018, 1, 15, 10, 0),
            EndTime: new Date(2018, 1, 16, 12, 30),
            IsAllDay: false
        }, {
            Id: 3,
            Subject: 'Paris',
            StartTime: new Date(2018, 1, 13, 12, 0),
            EndTime: new Date(2018, 1, 13, 12, 30),
            IsAllDay: false
        }, {
            Id: 4,
            Subject: 'Vacation',
            StartTime: new Date(2018, 1, 12, 10, 0),
            EndTime: new Date(2018, 1, 12, 10, 30),
            IsAllDay: false
        }
	];
	
      return {
        eventSettings: { dataSource: extend([], scheduleData, null, true) },
        selectedDate: new Date(2018, 1, 15)
      }
    },
    provide: {
      schedule: [ WorkWeek, Month, Week]
    }
  }
</script>
 <style>
@import '/node_modules/@syncfusion/ej2-base/styles/material.css';
@import '/node_modules/@syncfusion/ej2-buttons/styles/material.css';
@import '/node_modules/@syncfusion/ej2-calendars/styles/material.css';
@import '/node_modules/@syncfusion/ej2-dropdowns/styles/material.css';
@import '/node_modules/@syncfusion/ej2-inputs/styles/material.css';
@import '/node_modules/@syncfusion/ej2-navigations/styles/material.css';
@import '/node_modules/@syncfusion/ej2-popups/styles/material.css';
@import '/node_modules/@syncfusion/ej2-vue-schedule/styles/material.css';
</style>