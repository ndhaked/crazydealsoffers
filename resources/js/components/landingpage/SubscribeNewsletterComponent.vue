<template>
    <section class="newsletter-sec">
        <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-sm-12">
            <div class="newsletter-inner-sec">
                <div class="title-main text-white">
                <h3 class="margin-bottom-15">
                    <span>
                    Subscribe to our
                    </span>
                    Newsletter
                </h3>
                <p>
                    We got the best that you can wear hat you can wear a dres a dress. 
                </p>
                </div>
                <form action="" class="newsletter-form-sec"> 
                <input type="text" name="" id="" class="form-control" placeholder="Email Address*">
                <a href="javascript:void(0);" class="btn-primary white-btn">
                    Subscribe Now
                </a>
                </form>
            </div>
            </div>
        </div>
        </div>
    </section>   
</template>
<script>
    export default {
    	  data() {
		    return {
		      baseUrl: APP_URL,
		      androidUrl: '',
		      iosUrl: '',
		    }
		  },
        mounted() {
        	axios.get(APP_URL+'/api/get-playstore-links',
    	 	{
		      headers: {
		        "Platform": "android",
		        "Version": "1.0.0"
		      }
		    }).then(response => {
		        this.androidUrl = response.data.data.androidappurl.value
		        this.iosUrl = response.data.data.iosappurl.value
		      }).catch(error => {
                
            });
        }       
    }
</script>
