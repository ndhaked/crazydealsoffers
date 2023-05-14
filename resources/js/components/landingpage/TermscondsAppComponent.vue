<template>
    
    <main class="main-wrapper">
        <div class="static-content">
            <div class="container">
                <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                    <h3>
                        Terms & Conditions           
                    </h3>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                    <div class="terns-content">
                    <h4>
                        Ownership of Site Agreement to Terms of Use
                    </h4>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                    </p>
                    </div>
                    <div class="terns-content">
                    <h4>
                        Content
                    </h4>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                    </p>
                    </div>
                    <div class="terns-content">
                    <h4>
                        Your Use of the Site
                    </h4>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                    </p>
                    </div>
                    <div class="terns-content">
                    <h4>
                        Content
                    </h4>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                    </p>
                    </div>
                    <div class="terns-content">
                    <h4>
                        Your Use of the Site
                    </h4>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
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
