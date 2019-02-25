<template>
    <button type="submit" class="btn btn-primary" @click ="test()" >
        Run test
    </button>
</template>

<script>
    export default {
        data() {
            return {
                Timetable_id: '1', //If not how to update??
                Description: '1',
                EndTime: '1',
                IsAllDay: '1',
                Location: '1',
                RecurrenceRule: '1',
                StartTime: '1',
                StartTimezone: '1',
                Subject: '1',
            }
        }, 

        methods: {
            test() {
                axios.post("/api/timetable", {
                    timetable_id: this.timetable_id, //If not how to update??
                    description: this.Description,
                    end_time: this.EndTime,
                    is_all_day: this.IsAllDay,
                    location: this.Location,
                    recurrence_rule: this.RecurrenceRule,
                    start_time: this.StartTime,
                    start_timezone: this.StartTimezone,
                    subject: this.Subject,
                    isAppointment: true,
                })
            },
        }
    }
</script>