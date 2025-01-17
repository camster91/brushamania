<template>
	<div class="card-body">
                <div class="col-3 pull-left">
                	<ul class="nav nav-pills flex-column" id="pills-tab" role="tablist" style="margin-top: 20px;">
                                <tab-component v-for="child, index in children" :link="'#' + child.url_slug" :is_active="index == 0 ? true : false" :tab_id="child.url_slug + '-tab'" :controls="child.url_slug" :title="child.firstname + ' ' + child.lastname" toggle="pill" :key="child.id"></tab-component>
                	</ul>
                </div>
                <div class="col-9 pull-right">
                        <div class="tab-content" id="pills-tabContent">
                                <tab-content-component v-for="child, index in children"  :is_active="index == 0 ? true : false" :content_id="child.url_slug" :content_label="child.url_slug + '-tab'" :key="child.id">
                                        <child-parent-update-component :child="child"></child-parent-update-component>
                                </tab-content-component>
                        </div>
                </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				children: []
			}
		},
                created() {
                        axios.get('/parents/children').then((response) => {
                                this.children = response.data.children;
                        }).catch((error) => {
                                console.log(error);
                        })
                }
		
	}
</script>