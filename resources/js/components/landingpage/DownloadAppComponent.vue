<template>

	<section class="home-banner">
    <div class="container-fluid">
      <div class="row">
        <div class="home-banner-slider owl-theme owl-carousel">
        
          <div class="item">
            <figure>
              <img :src="baseUrl+'/front/images/home-banner-1.svg'" alt="">
              <div class="list-promotion-actions">
                <a :href="iosUrl">
                  <img :src="baseUrl+'/front/images/icons/ic-google-play.svg'" alt="">IOS
                </a>
                <a :href="androidUrl">
                  <img :src="baseUrl+'/front/images/icons/ic-app-store.svg'" alt="">Android
                </a>
              </div>
            </figure>
          </div>
    
          <div class="item">
            <figure>
              <img :src="baseUrl+'/front/images/home-banner-1.svg'" alt="">
            </figure>
          </div>
          <div class="item">
            <figure>
              <img :src="baseUrl+'/front/images/home-banner-1.svg'" alt="">
            </figure>
          </div>
          <div class="item">
            <figure>
              <img :src="baseUrl+'/front/images/home-banner-1.svg'" alt="">
            </figure>
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
