<template>
    <div class="main-panel">
        <header-title-component page_title="Parents">
        	<template v-slot:year>
	        	<select name="year" class="form-control col-md-3" v-model="selected_year" @change="updateParentList()">
	        		<option v-for="year in getYears" :value="year" :key="year">{{year}}</option>
	        	</select>
	        </template>
	        <template v-slot:export>
        		<a :href="'/parents/export/' + selected_year" class="btn btn-success">Export</a>
        	</template>
        	<template v-slot:addNew>
        		<router-link class="btn btn-primary" to="/parents/new">Add New Parent and Children</router-link>
        	</template>
        </header-title-component>
		<content-component title="Parents List" search_placeholder="Search Parent" :fields="fields" :items="getParents" :total_rows="getParentsLength">
			<template slot="children" slot-scope="data" v-if="title == 'Parents List'">
				<div v-for="child, index in data.value" class="children-container" :key="index">
					<a href="#">
						{{child.firstname}}
					</a>
					<b-progress>
						<b-progress-bar :value="child.brush_count" :max="100" animated :label="`${child.brush_count}%`" variant="success"></b-progress-bar>
					</b-progress>
				</div>
			</template>
		</content-component>
    </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import {eventBus} from '../../app';
	
	export default {
		data() {
			return {
				search: '',
				selected_year: null,
				fields: [
					{
						key: 'parent_name',
						sortable: true,
						label: 'Parent'
					},
										{
						key: 'email',
						sortable: true,
						label: 'Email'
					},
					{
						key: 'phone',
						sortable: true,
						label: 'Phone'
					},
					{
						key: 'children',
						sortable: true,
						label: 'Children'
					}
				],
			}
		},
		computed: {
			...mapGetters([
				'getParents',
				'getParentsLength',
				'getYears',
				'getActiveYear'
			])
		},
		created() {
			eventBus.$on('deleteParent', (id) => {
				this.deleteParent(id);
			});
			this.setYear();
		},
		beforeRouteLeave(to, from, next) {
			if(this.getActiveYear != this.selected_year) {
				this.$store.dispatch('getParents');
			}
			next();
		},
		methods: {
			deleteParent(id) {
				swal({
					title: "Are you sure?",
					text: "Once deleted, you will never be able to recover this parent!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						axios.delete('/parent/' + id).then((response) => {
							swal(response.data.msg, {
								icon: 'success'
							});
							this.$store.dispatch('getParents', this.selected_year);
							this.$store.dispatch('getChildren');
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			},
			updateParentList() {
				this.$store.dispatch('getParents', this.selected_year);
			},
			setYear() {
				axios.get('/year/active').then((response) => {
					this.selected_year = response.data.year;
				}).catch((error) => {
					console.log(error);
				})
			}
		}
	}
</script>