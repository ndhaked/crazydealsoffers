<template>
<div id="termandcondition" class="modal" role="dialog">
	<div class="modal-dialog modal-lg ">
	  <div class="modal-content ">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Terms and Condition</h4>
		</div>
		<div class="modal-body">
		  <p><strong>Last Updated: {{ info.last_update }}</strong></p>
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
        	axios.get(APP_URL+'/api/pages/terms-and-condition',
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

