<template>
    
    <main class="main-wrapper">
        <div class="static-content">
            <div class="container">
                <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                    <h3>
                        Affiliate Disclosure           
                    </h3>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="image-static-data">
                    <div class="col-sm-12">
                    <img :src="baseUrl+'/front/images/affiliate-disclosure-1.jpg'" alt="">
                    </div>
                    <div class="col-sm-12">
                    <p class="margin-top-20">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                    </p>
                    </div>
                </div>
                <div class="image-static-data">
                    <div class="col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-12">
                        <img :src="baseUrl+'/front/images/affiliate-disclosure-2.jpg'" alt="">
                        </div>
                        <div class="col-lg-6 col-md-12 padding-left-30">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                        </p>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="image-static-data">
                    <div class="col-sm-12">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-12 padding-left-30 order-5">
                        <img :src="baseUrl+'/front/images/affiliate-disclosure-3.jpg'" alt="">
                        </div>
                    <div class="col-lg-6 col-md-12">
                        <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                        </p>
                    </div>
                    </div>
                </div>
                </div>
                <div class="image-static-data">
                    <div class="col-sm-12">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                    </p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
    
     
</template>
<script>
    export default {
    	  data() {
		    return {
		      baseUrl: APP_URL,
		      socialData: [],
		      facebook: '',
		      instagram: '',
		      tiktok: '',
		    }
		  },
        mounted() {
        	axios.get(APP_URL+'/api/get-social-links',
    	 	{
		      headers: {
		        "Platform": "android",
		        "Version": "1.0.0"
		      }
		    }).then(response => {
		        this.socialData = response.data.data
		        this.facebook = response.data.data.facebook.value
		        this.instagram = response.data.data.instagram.value
		        this.tiktok = response.data.data.tiktok.value
		      }).catch(error => {
                
            });
        }       
    }
</script>
