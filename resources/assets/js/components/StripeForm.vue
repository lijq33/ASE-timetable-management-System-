<template>
    <div>
        
        <card class='stripe-card'
          :class='{ complete }'
          :stripe= "stripeKey"
          :options='stripeOptions'
          @change='change($event)'
        />
        
        <div id="card-errors" role="alert" v-text = "errorMessage"></div>

    </div>
</template>

<script>
    import { Card, createToken } from 'vue-stripe-elements-plus'

    export default {

        components: { Card },

        data () {
            return {
              complete: false,
              errorMessage: '',
              stripeKey: 'pk_test_cR5zftBePfWWwcryuRQYy52t',
              stripeOptions: {

                style: {
                  base: {
                    color: '#32325d',
                    lineHeight: '15px',
                    fontFamily: '"Lato", Lato, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '15px',
                    '::placeholder': {
                      color: '#b8c2cc'
                    }
                  },
                  invalid: {
                    color: '#e3342f',
                    iconColor: '#fa755a'
                  }
                },
                hidePostalCode: true
            }
          }
        },
        methods: {
            pay () {
              createToken().then(data => console.log(data.token))
            },

            change(event) {
                if (event.error) {
                  this.errorMessage = event.error.message;
                } else {
                  this.errorMessage = ''
                }
            }
      }
    }
</script>
<style>
.stripe-card {
  border: 1px solid #f1f5f8;
  border-radius: 8px;   
}

.StripeElement {
  background-color: #f1f5f8;
  height: 40px;
  padding: 10px 12px;
  border-radius: 4px;
  border: 1px solid #b8c2cc;
  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}
#card-errors{
  color: #e3342f;
}
</style>