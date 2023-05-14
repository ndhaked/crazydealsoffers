<template>
	<section class="get-in-touch" id="contact">
		<div class="container">
			
			<div class="row justify-content-center">
				<div class="col-lg-8 col-md-12">
					<div class="section-title text-center">
						<h2 class="animated6 fadeInDown">Get In Touch</h2>
					</div>
					<div class="contact-form-success alert alert-success mt-4" v-if="success">
	                    <strong>Success!</strong> Your request is submitted.
	                </div>
					<form @submit.prevent="submit">
							<div class="row">
								<div class="col-lg-6 animated8 fadeInLeft">
									<div class="form-group">
										<input type="text" class="form-control" name="name" id="name" v-model="fields.name" placeholder="Name"/>
		    							<div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
									</div>
								</div>
								<div class="col-lg-6 animated8 fadeInRight">
									<div class="form-group">
										<input type="email" class="form-control" name="email" id="email" v-model="fields.email" placeholder="Email" />
	    								<div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
									</div>
								</div>
								<div class="col-lg-12 animated6 fadeInDown">
									<div class="form-group mb0">
										<textarea class="form-control" id="message" name="message" rows="5" v-model="fields.message" placeholder="Message"></textarea>
	            						<div v-if="errors && errors.message" class="text-danger">{{ errors.message[0] }}</div>
									</div>
								</div>
								<div class="col-lg-12 bottombtn">
									<button type="button" @click="submit" class="primary-btn"  value="Submit">{{loading ? "Sending Request..." : "Submit"}}</button> 
								</div>
							</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
export default {
  data() {
    return {
      fields: {},
      errors: {},
      loaded : true,
      success: false,
      loading : false,
    }
  },
  methods: {
    submit() {
        if(this.loaded){
        	  this.loading = true;
        	  this.loaded = false;
              this.errors = {};
              axios.post(APP_URL+'/contactus', this.fields).then(response => {
				this.fields = {};
				this.success = true;
				this.loading = false;
				this.loaded  = true;
              }).catch(error => {
                if (error.response.status === 422) {
                  this.errors = error.response.data.errors || {};
                  this.loading = false;
                  this.success = false;
                  this.loaded  = true;
                }
            });
        }
    },
  },
}
</script>