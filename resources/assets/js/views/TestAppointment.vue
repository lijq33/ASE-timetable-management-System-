<template>
    <button type="submit" class="btn btn-primary" @click ="test()" >
        Run test
    </button>
</template>

<script>
    export default {
        data() {
            return {
                userID: '1',
                time: '10:00:00', 
                purpose: 'test',
                description: 'a long long description',
                date:'12/12/2012'
            }
        }, 

        methods: {
            test() {
                axios.post("/api/appointment", {
                    user_id: this.userID,
                    time: this.time,
                    purpose: this.purpose,
                    description: this.description,
                    date: this.date
                })
            },
        }
    }
</script>