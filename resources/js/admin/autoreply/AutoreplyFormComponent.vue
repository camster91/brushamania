<style scoped>
	.card-title, .form-control, .label,  {
		display: inline;
	}
	.search-box {
		width: 30%;
	}

	div.col-md-5 {
		display: inline-block;

	}
	
	.label {
		font-size: 15px;
	}

	.label #template-name {
		color: black;
	}

	.send-btn {
		margin-right: 10px;
	}

	#test-email-form > * {
		display: inline-block !important;
	}

	#test-email-form button {
		margin-top: 0px;
	}

	@media(max-width: 768px) {
		.search-box {
			width: 50%;
		}
	}
</style>

<template>
	<div class="content">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="card">
    				<div class="card-header">
    					<div class="col-md-5">
    						<label class="label">Template Name: <span id="template-name">{{autoreply.name}}</span></label>
    					</div>
    					<div class="col-md-5 pull-right">
    						<form action="POST" id="test-email-form" @submit.prevent="testMail">
    							<div class="col-md-7">
    								<input type="email" class="form-control" name="test_mail" required v-validate v-model="autoreply.test_mail" data-vv-as="email" placeholder="Email">
    							</div>
		                		<button class="btn btn-primary pull-right save-btn col-md-4">Test Mail</button>
    						</form>
    					</div>
    				</div>
    				<div class="card-body">
    					<form method="POST" @submit.prevent="saveMail">
	    					<div class="col-md-12">
	    						<vue-editor v-model="autoreply.content" useCustomImageHandler @image-added="handleImageAdded"></vue-editor>
		    					<div class="row">
									<div class="col-md-12">
										<div class="success-message" v-if="successful != null && successful == true">{{message}}</div>
			            				<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
										<button class="btn btn-primary pull-right save-btn">Save Mail</button>
									</div>
								</div>
	    					</div>
	    				</form>
    				</div>
    			</div>
    		</div>
    	</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				autoreply: {
					test_mail: null,
					name: null,
					content: null
				},
				successful: null,
				message: null,
				error_message: null,
				backend_errors: null
			}
		},
		created() {
			this.getAutoreply();
			
		},
		methods: {
			handleImageAdded(file, Editor, cursorLocation, resetUploader) {
				var formData = new FormData();
				formData.append("image", file);
				axios.post('/image', formData).then((response) => {
					console.log(response);
					Editor.insertEmbed(cursorLocation, "image", response.data.res);
					resetUploader();
				}).catch((error) => {
					console.log(error);
				})
			},
			saveMail() {
            	console.log(this.$route.params.autoreply);
            	axios.patch('/autoreply-emails/' + this.$route.params.autoreply, this.autoreply).then((response) => {
					console.log(response.data);
					this.successful = true;
					this.message = response.data.msg;
				}).catch((error) => {
					console.log(error);
					this.successful = false;
				});
			},
			getAutoreply() {
				axios.get('/autoreply-emails/' + this.$route.params.autoreply).then((response) => {
					this.autoreply = response.data.autoreply;
				}).catch((error) => {
					console.log(error);
				});
			},
			testMail() {
				axios.post('/mail/test', this.autoreply).then((response) => {
					console.log(response.data);
					swal("Test Mail Sent Successfully", {
						icon: "success"
					});
				}).catch((error) => {
					console.log(error);
					swal("Test Mail Not Sent", {
						icon: "error"
					});
				})
			}
		}
	}
</script>