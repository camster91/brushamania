<template>
    <div class="main-panel">
        <header-title-component page_title="Child Brushtracker"></header-title-component>
        <div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">{{brush.name}}</h4>
						</div>
                        <div class="card-body">
                            <form @submit.prevent="submitBrushtracker" method="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="label">Brushtracker Date (required)</label>
                                            <input type="date" v-model="brush.brush_date" required v-validate class="form-control" name="brush_date" data-vv-as="brush date">
                                            <div class="invalid-feedback">
                                                {{errors.first('brush_date')}}
                                                {{(backend_errors != null) ? (backend_errors.brush_date != null) ? backend_errors.brush_date[0] : null : null}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row check-row">
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Morning Brush
                                                <input type="checkbox" v-model="brush.morning_brush">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Morning Floss
                                                <input type="checkbox" v-model="brush.morning_floss">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Lunchtime Brush
                                                <input type="checkbox" v-model="brush.lunchtime_brush">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Lunchtime Floss
                                                <input type="checkbox" v-model="brush.lunchtime_floss">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Evening Brush
                                                <input type="checkbox" v-model="brush.night_brush">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Evening Floss
                                                <input type="checkbox" v-model="brush.night_floss">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Bonus Brush
                                                <input type="checkbox" v-model="brush.bonus_brush">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox">
                                            <label class="control control--checkbox">Bonus Floss
                                                <input type="checkbox" v-model="brush.bonus_floss">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
				                    <div class="col-md-12">
                                    <div class="success-message" v-if="successful != null && successful == true">Brush Updated Successfully</div>
            		<div class="error-message" v-else-if="successful != null && successful == false">{{error_message}}</div>
                                        <button class="btn btn-primary pull-right save-btn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
</template>

<script>
	import {eventBus} from '../../app';

	export default {
		data() {
			return {
                brush: {
                    child_id: null,
                    name: null,
                    brush_date: null,
                    morning_brush: false,
                    morning_floss: false,
                    lunchtime_brush: false,
                    lunchtime_floss: false,
                    night_brush: false,
                    night_floss: false,
                    bonus_brush: false,
                    bonus_floss: false

                },
                successful: null,
                error_message: null,
                backend_errors: null
			}
		},
		created() {
			axios.get('/child/edit/' + this.$route.params.child).then((response) => {
				this.brush.child_id = response.data.child.id;
                this.brush.name = response.data.child.firstname + ' ' + response.data.child.lastname
			}).catch((error) => {
				console.log(error);
			});
		},
		methods: {
			submitBrushtracker() {
                this.$validator.validate().then(result => {
                    if(result) {
                        axios.post('/children/brushes', this.brush).then((response) => {
							console.log(response.data);
							if(response.data.success) {
								this.successful = true;
								this.backend_errors = null;
							} else {
								this.successful = false;
								this.error_message = response.data.msg;
							}
						}).catch((error) => {
							console.log(error);
							this.successful = false;
							this.error_message = error.response.data.msg;
							if(error.response.data.msg != null) {
                                this.error_message = error.response.data.msg;
                            } else {
                                this.error_message = error.response.data.message;
                            }
                            this.backend_errors = error.response.data.errors;
						});
                    }
                });
			}
		}
	}
</script>