<style scoped>
	.error-message {
		margin-top: 0px;
	}

	.btn {
		position: relative;
	}
</style>

<template>
    <div class="main-panel">
        <header-title-component page_title="Mail Blast">
        </header-title-component>
		<mail-form-component ></mail-form-component>
		<modal-component modal_id="select-emails-modal" title="Select Emails">
			<div class="modal-body">
				<div class="checkbox">
					<label class="control control--checkbox">
						Select All
						<input type="checkbox" id="select-all" @change="selectAll">
						<div class="control__indicator"></div>
					</label>
				</div>
				<div v-for="email in email_list" class="checkbox" :key="email.email">
					<label class="control control--checkbox">
						{{email.name}} <{{email.email}}>
						<input type="checkbox" class="select-email" :value="email" v-model="selected_emails" @change="selectMail">
						<div class="control__indicator"></div>
					</label>
				</div>
				<div class="modal-footer">
					<div class="error-message" v-if="successful != null && successful == false">Please Select an Email Address</div>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="button" value="Save" class="btn btn-success" @click="saveRecipients">
					<input type="button" value="Send" class="btn btn-primary" @click="sendMail">
				</div>
			</div>
        </modal-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				successful: null,
				email_list: [],
				selected_emails: [],
				recipients: [],
			}
		},
		created() {
			eventBus.$on('showModal', receiver => {
				console.log('receiver',receiver)
				this.successful = null;
				$('#select-emails-modal').modal('show');
				// $('#select-all').prop("checked", true);
				this.loadEmails(receiver);
			});
		},
		methods: {
			loadEmails(receiver) {
				if (receiver.receiver == 'politician') {
					console.log('address', receiver.address === null, receiver.address);
					let address;
					if(receiver.address === null || typeof receiver.address === 'undefined') {
						address = '';
					} else {
						address = receiver.address;
					}
					axios.get('/emails/list/' + receiver.receiver + '/' + address).then((response) => {
						this.email_list = response.data.emails;
						this.selected_emails = response.data.emails.filter(email => receiver.recipients.includes(email.email));
					}).catch((error) => {
						console.log(error);
					});
				} else {
					axios.get('/emails/list/' + receiver.receiver + '/' + receiver.year).then((response) => {
						this.email_list = response.data.emails;
						this.selected_emails = response.data.emails.filter(email => receiver.recipients.includes(email.email));
					}).catch((error) => {
						console.log(error);
					});
				}
				
			},
			saveRecipients() {
				eventBus.$emit('saveRecipients', this.selected_emails);
			},
			sendMail() {
				if (this.selected_emails.length == 0) {
					this.successful = false;
				} else {
					eventBus.$emit('sendMail', this.selected_emails);
				}
				
			},
			selectAll() {
				// console.log('asd');
				if($('#select-all').prop("checked")) {
					$('.select-email').prop("checked", true);
					this.selected_emails = this.email_list;
				} else {
					$('.select-email').prop("checked", false);
					this.selected_emails = [];
				}
			},
			selectMail(e) {
				// console.log('asd');
				if(!e.target.checked) {
					$('#select-all').prop("checked", false);
				}
			}
		}
	}
</script>