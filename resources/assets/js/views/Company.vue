<template>
    <div>
        THIS COMPANY
       <calendar 
           :appointments = "appointments"
        >
        </calendar>
    </div>
</template>

<script>
import calendar from './Calendar'
    export default {
        components:{
            'calendar': calendar
        },

        mounted(){
            axios.get('/api/company/' + this.$route.params.Cid)
				.then((res) => {	
                    this.appointments = res.data.timetables;					
				}).catch((error) => {
					console.log(error)
				})
        },

        data() {
            return {
                appointments: [],
            }
        }, 
    }
</script>