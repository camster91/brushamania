<style scoped>
	.card-title, .form-control, .label  {
		display: inline;
	}
	.search-box {
		width: 30%;
	}

	.send-btn {
		margin-right: 10px;
	}

	.card-header {
		display: inline-flex;
	}

	.send-btn {
		position: relative;
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
    			<form method="POST">
	    			<div class="card">
	    				<div class="card-header">
	    					<div class="col-md-3">
	    						<label class="label">Template Name: </label>
	    						<input type="text" class="form-control" name="template_name" required v-validate v-model="mail.name" data-vv-as="mail template name">
								<div class="invalid-feedback">
		                    		{{errors.first('template_name')}}
		                    		{{(backend_errors != null) ? (backend_errors.template_name != null) ? backend_errors.template_name[0] : null : null}}
		                		</div>
	    					</div>
							<div class="col-md-2">
	    						<label class="label">Subject: </label>
	    						<input type="text" class="form-control" name="subject" required v-validate v-model="mail.subject" data-vv-as="mail subject">
								<div class="invalid-feedback">
		                    		{{errors.first('subject')}}
		                    		{{(backend_errors != null) ? (backend_errors.subject != null) ? backend_errors.subject[0] : null : null}}
		                		</div>
	    					</div>
	    					<div class="col-md-2">
	    						<label class="label">Receiver: </label>
		    					<select name="receiver" class="form-control" v-model="mail.receiver">
		    						<option value="school">Schools</option>
		    						<option value="dentist">Dentists</option>
		    						<option value="rotarian">Rotarians</option>
		    						<option value="parent">Parents</option>
		    						<option value="politician">Politicians</option>
		    					</select>
	    					</div>
	    					<div class="col-md-2" v-if="mail.receiver == 'politician'">
	    						<label class="label">City/Province</label>
		    					<input type="text" class="form-control" name="address" v-model="mail.address">
	    					</div>
							<div class="col-md-2" v-else>
								<label class="label">Year: </label>
								<select name="year" class="form-control col-md-12" v-model="mail.year">
									<option value="All">All</option>
	        						<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
	        					</select>
							</div>
							<div class="col-md-3 pull-right">
								<label class="label">Send Test Mail</label>
								<form action="POST" id="test-email-form" @submit.prevent="testMail">
									<div class="col-md-7 pull-left" style="padding-left: 0px;">
										<input type="email" class="form-control" name="test_mail" required v-validate v-model="mail.test_mail" data-vv-as="email" placeholder="Email">
									</div>
									<button class="btn btn-primary pull-right save-btn col-md-4">Test Mail</button>
								</form>
							</div>
	    				</div>
	    				<div class="card-body">
	    					<div class="col-md-12">
	    						<vue-editor v-model="mail.content" useCustomImageHandler @image-added="handleImageAdded"></vue-editor>
		    					<div class="row">
									<div class="col-md-12 message-row">
										<div class="success-message" v-if="successful != null && successful == true">{{message}}</div>
			            				<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
										<button class="btn btn-primary pull-right save-btn" @click.prevent="saveMail" >Save Mail</button>
										<button type="button" class="btn btn-success pull-right send-btn" @click="selectEmails">Send Mail</button>
									</div>
								</div>
	    					</div>
	    				</div>
	    			</div>
	    		</form>
    		</div>
    	</div>
	</div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';

	export default {
		data() {
			return {
				mail: {
					id: null,
					name: null,
					subject: null,
					receiver: "school",
					year: "All",
					email_addresses: [],
					content: null,
					address: null,
					recipients: []
				},
				successful: null,
				message: null,
				error_message: null,
				backend_errors: null,
				autosave: 1
			}
		},
		computed: {
			...mapGetters([
				'getYears'
			])
		},
		created() {
			if (this.$route.params.mailblast != null) {
				this.getMailBlast();
			}
			eventBus.$on('sendMail', (selected_emails) => {
				this.mail.email_addresses = selected_emails;
				this.sendMail();
			});
			eventBus.$on('saveRecipients', (selected_emails) => {
				this.mail.email_addresses = selected_emails;
				this.saveRecipients();
			})
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
                if (this.$route.params.mailblast == null) {
                    this.createMail();
                } else {
                    this.updateMail();
                }
                this.$store.dispatch('getMailBlasts');
                    
			},
			createMail() {
				axios.post('/mailblast', this.mail).then((response) => {
					console.log(response.data);
					this.successful = true;
					this.message = response.data.msg;
					this.mail.id = response.data.id;
					this.$router.push({path:'/mailblast/update/' + response.data.url_slug});
				}).catch((error) => {
					console.log(error);
					this.successful = false;
				});
			},
			updateMail() {
				axios.patch('/mailblast/' + this.mail.id, this.mail).then((response) => {
					console.log(response.data);
					this.successful = true;
					this.message = response.data.msg;
					this.mail.id = response.data.id;
					this.$router.push({path:'/mailblast/update/' + response.data.url_slug});
				}).catch((error) => {
					console.log(error);
					this.successful = false;
				});
			},
			selectEmails() {
				eventBus.$emit('showModal', {
					receiver: this.mail.receiver, 
					address: this.mail.address,
					year: this.mail.year,
					recipients: this.mail.recipients
				});
			},
			saveRecipients() {
				axios.post('/mailblast/save-recipients', this.mail).then((response) => {
					console.log(response.data);
					this.successful = true;
					this.message = response.data.msg;
				}).catch((error) => {
					console.log(error);
					this.successful = false;
				});
				$('#select-emails-modal').modal('hide');
			},
			sendMail() {
				axios.post('/mailblast/send', this.mail).then((response) => {
					console.log(response.data);
					this.successful = true;
					this.message = response.data.msg;
				}).catch((error) => {
					console.log(error);
					this.successful = false;
				});
				$('#select-emails-modal').modal('hide');
			},
			getMailBlast() {
				axios.get('/mailblast/' + this.$route.params.mailblast).then((response) => {
					this.mail = {...response.data.mail, year: response.data.mail.year || 'All'};
				}).catch((error) => {
					console.log(error);
				});
			},
			testMail() {
				axios.post('/mail/test', this.mail).then((response) => {
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
		},
		watch: {
			autosave: {
				handler(newVal, oldVal) {
					// console.log('handler', newVal, oldVal)
					setTimeout(() => {
						console.log('save');
						this.saveMail();
						this.autosave++;
					}, 600000)
				},
				immediate: true
			}
		}
	}
</script>