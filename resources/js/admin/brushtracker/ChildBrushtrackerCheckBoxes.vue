<template>
	<div class="row check-row" v-if="brush !== null">
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
</template>

<script>
	export default {
		props: {
			brush: Object
		}
	}
</script>