<style scoped>
	.card-header h4 {
		margin-top: 0px;
		margin-bottom: 0px;
	}
</style>

<template>
	<div class="main-panel">
		<header-title-component page_title="Brush Tracker"></header-title-component>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">

						<div class="card-header" style="text-align: center;" v-if="children.filter( e => e.is_active)">
							<h4>You may enter your child's brushes and flosses for these two days.</h4>
							<a href="https://brushamania.ca/wp-content/uploads/sites/2/2024/08/Parent-instructions.pdf" target="_blank"><h5>Parent Instructions PDF </h5></a>
							<button class="btn" data-toggle="modal" data-target="#days-modal">Days</button>
							<button class="btn" :class="is_today ? 'btn-primary' : ''" @click="is_today = true" data-toggle="modal" data-target="#days-modal">{{today}}</button>
							<button class="btn" :class="!is_today ? 'btn-primary' : ''" @click="is_today = false" data-toggle="modal" data-target="#days-modal">{{yesterday}}</button>
						</div>
                        <h6 class="p-4">100 brushes and/or flosses required in any 30 day period to be entered into the
                            draw on June 2. Winners will be notified after June 7</h6>
						<form @submit.prevent="submitBrushtracker" method="POST">
							<child-brushtracker-component v-for="child in children" :child="child" :key="child.id" :is_today="is_today"></child-brushtracker-component>
							<div class="card-body">
								<button class="btn btn-primary pull-right save-btn">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

        <div class="modal fade" tabindex="-1" role="dialog" id="days-modal" v-if="showDaysModal()">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Contest Information</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p style="font-size: 20px;" class="p-2">Contest open April 1 to May 31.</p>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				children: [],
				today: null,
				yesterday: null,
				is_today: true,
			}
		},
		created() {
			this.getBrushes();
			this.getDays();
		},
		methods: {
			submitBrushtracker() {
				this.children.forEach((child) => {
					if(child.is_active) {
						if(this.is_today) {
							axios.patch('/children/brushes/' + child.brush_today.id, child.brush_today).then((response) => {
								console.log(response.data);
								this.getBrushes();
							}).catch((error) => {
								console.log(error);
							});
						} else {
							axios.patch('/children/brushes/' + child.brush_yesterday.id, child.brush_yesterday).then((response) => {
								console.log(response.data);
								this.getBrushes();
							}).catch((error) => {
								console.log(error);
							});
						}
					}

				});

			},
			getBrushes() {
				axios.get('/children/brushes').then((response) => {
					this.children = response.data.children;
					// this.children.forEach((child) => {
					// 	axios.get('/year/brush-tracker/' + child.id).then((response) => {
					// 		child.is_active = response.data.is_active;
					// 	}).catch((error) => {
					// 		console.log(error);
					// 	})
					// });
				}).catch((error) => {
					console.log(error);
				});
			},
			getDays() {
				axios.get('/brushtracker/days')
					.then((response) => {
						var weekday = new Array(7);
						weekday[0] = "Sunday";
						weekday[1] = "Monday";
						weekday[2] = "Tuesday";
						weekday[3] = "Wednesday";
						weekday[4] = "Thursday";
						weekday[5] = "Friday";
						weekday[6] = "Saturday";
						this.today = weekday[response.data.days.today];
						this.yesterday = weekday[response.data.days.yesterday];
					}).catch((error) => {
						console.log(error);
					});

			},
            showDaysModal() {
                const today = new Date();
                const month = today.getMonth();
                const day = today.getDate();
                return (month < 3 || month > 4) || (month === 3 && day < 1) || (month === 5 && day > 31);
            }


        }
	}
</script>
