<style scoped>
	.progress, .progress-bar {
		height: 35px;
	}
	.progress-bar {
		color: black;
		font-size: 20px;
	}
	.check-row {
		margin-top: 10px;
	}
	h4 {
		margin-top: 5px;
	}
</style>

<template>
	<div>
		<div class="card-header">
			<h4 class="card-title">{{child.child_name}}</h4>
			<span style="margin-right: 20px; padding-top: 10px;">Day #{{child.days}}</span><a href="#" class="btn btn-danger" @click="resetBrushes">RESTART</a>
		</div>
		<div class="card-body">
			<b-progress>
				<b-progress-bar :value="child.brush_count" :max="100" animated :label="child.brush_count + '%'" variant="success"></b-progress-bar>
			</b-progress>
			<child-brush-tracker-check-boxes :brush="is_today ? child.brush_today : child.brush_yesterday" v-if="child.is_active"></child-brush-tracker-check-boxes>
			<!-- <child-brush-tracker-check-boxes v-if="child.is_active" :brush="is_today ? child.brush_today : child.brush_yesterday"></child-brush-tracker-check-boxes> -->
			<!-- <h4 v-else style="text-align: center">Child is not registered for this year.</h4> -->
		</div>
	</div>
</template>

<script>
import swal from 'sweetalert';
	export default {
		data() {
			return {
				text: 'haha'
			}
		},
		props: {
			child: Object,
			is_today: Boolean
		},
		methods: {
			showResult() {
				console.log(this.text);
			},
			resetBrushes() {
				swal({
					title: "Do you wish to  restart your child's 30 day tracking period?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if(willDelete) {
						console.log('asd');
						axios.delete('/children/brushes/delete/' + this.child.id).then((response) => {
							// this.child = response.data.child;

							swal("Tracking Days Restarted Successfully", {
								icon: "success"
							}).then((result) => {
								window.location.reload(true);
							});
						}).catch((error) => {
							swal(error.response.data.msg, {
								icon: 'error'
							});
						});
					}
				});
			}
		}
	}
</script>
