<template>
  <div id="privacypolicy" class="modal" role="dialog">
	<div class="modal-dialog modal-lg ">
	  <div class="modal-content ">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">{{ info.title }}</h4>
		</div>
		<div class="modal-body">
		  <h2>{{ info.title }}</h2>
		  <p v-html="info.description">{{ info.description }}</p>
		</div>
	  </div>
	</div>
  </div>
 </template>
<script>
    export default {
    	  data() {
		    return {
		      info : '',
		    }
		  },
        mounted() {
        	axios.get(APP_URL+'/api/pages/privacy-policy',
    	 	{
		      headers: {
		        "Platform": "android",
		        "Version": "1.0.0"
		      }
		    }).then(response => {
		        this.info = response.data.data
		      }).catch(error => {
                
            });
        }      
    }
</script>
