<template>
	<div class = "alert alert-success" v-if = "show">
		<span>
			{{body}}
		</span>
	</div>
</template>

<script>
	export default {
		name: "flash",

		props: ['message'],

		data() {
			return {
				body: '',
				show: false,
			}
		},

		watch:{
			message() {
				if (this.message !== ""){
					this.flash(this.message);
				}
			}
		},

		methods: {

			flash(message) {
				this.show = true;
				this.body = message;
				
				this.hide();
			},
			
			hide() {
				window.setTimeout(() => {
					this.show = false;
				}, 5000);
			}
		},
	}
</script>

<style>
	.notification {
		position: fixed;
		right: 15px;
		margin-top: 15px;
		width: 300px;
		z-index: 999;
	}
</style>