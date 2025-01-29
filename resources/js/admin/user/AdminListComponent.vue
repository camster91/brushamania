<template>
    <div class="main-panel">
        <header-title-component page_title="Users">
        	<template v-slot:addNew>
        		<button class="btn btn-primary" @click="showModal()">Add New User</button>
        	</template>
        </header-title-component>
		<content-component title="Users List" search_placeholder="Search User" :fields="fields" :items="getUsers" :total_rows="getUsersLength"></content-component>
		<modal-component modal_id="add-user-modal" title="Add User">
			<form @submit.prevent="submitUser" method="POST">
				<div class="modal-body">
					<div class="row row-space">
						<div class="col-md-6">
							<div class="form-group">
								<label class="label">First Name</label>
								<input type="text" class="form-control" name="firstname" required v-validate data-vv-as="first name" v-model="user.contact_firstname">
								<div class="invalid-feedback">
		                    		{{errors.first('firstname')}}
		                    		{{(backend_errors != null) ? (backend_errors.contact_firstname != null) ? backend_errors.contact_firstname[0] : null : null}}
		                		</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="label">Last Name</label>
								<input type="text" class="form-control" name="lastname" required v-validate data-vv-as="last name" v-model="user.contact_lastname">
								<div class="invalid-feedback">
		                    		{{errors.first('lastname')}}
		                    		{{(backend_errors != null) ? (backend_errors.contact_lastname != null) ? backend_errors.contact_lastname[0] : null : null}}
		                		</div>
							</div>
						</div>
					</div>
					<div class="row row-space">
						<div class="col-md-6">
							<div class="form-group">
								<label class="label">Email</label>
								<input type="email" class="form-control" name="email" required v-validate v-model="user.email">
								<div class="invalid-feedback">
		                    		{{errors.first('email')}}
		                    		{{(backend_errors != null) ? (backend_errors.email != null) ? backend_errors.email[0] : null : null}}
		                		</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="label">Role</label>
								<select name="role" class="form-control" v-model="user.role" required>
		    						<option value="admin">Admin</option>
		    						<option value="student">Student</option>
		    					</select>
								<div class="invalid-feedback">
		                    		{{errors.first('role')}}
		                    		{{(backend_errors != null) ? (backend_errors.role != null) ? backend_errors.role[0] : null : null}}
		                		</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="submit" value="Save" class="btn btn-primary">
				</div>
			</form>
		</modal-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				user: {
					contact_firstname: null,
					contact_lastname: null,
					email: null,
					role: 'admin'
				},
				search: '',
				fields: [
					{
						key: 'name',
						sortable: true,
						label: 'Name'
					},
					{
						key: 'email',
						sortable: true,
						label: 'Email'
					},
					{
						key: 'role',
						sortable: true,
						label: 'Role'
					}
				],
				backend_errors: null
			}
		},
		computed: {
			...mapGetters([
				'getUsers',
				'getUsersLength'
			])
		},
		created() {
			eventBus.$on('deleteUser', (id) => {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this user!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/user/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getUsers');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			});
		},
		methods: {
			showModal() {
				$('#add-user-modal').modal('show');
			},
			submitUser() {
				this.$validator.validate().then(result => {
					if (result) {
						axios.post('/user', this.user).then((response) => {
							// console.log(response);
							swal("User Added Successfully", {
								icon: "success"
							});
							this.$store.dispatch('getUsers');
							$('#add-user-modal').modal('hide');
							this.resetData();
						}).catch((error) => {
							// console.log(error.response.data);
							// swal(error.response.data.msg, {
							// 	icon: "error"
							// });
							this.backend_errors = error.response.data.errors;
						});
					}
				});
			},
			resetData() {
				this.backend_errors = null;
				this.user = {
					contact_firstname: null,
					contact_lastname: null,
					email: null
				};
				this.$validator.pause();
                this.$nextTick(() => {
                    this.$validator.errors.clear();
                    this.$validator.fields.items.forEach(field => field.reset());
                    this.$validator.fields.items.forEach(field => this.errors.remove(field));
                    this.$validator.resume();
                });
			}
		}
	}
</script>