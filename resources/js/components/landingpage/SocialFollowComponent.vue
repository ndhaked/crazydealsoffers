<template>
    <section class="follow-us-sec">
      <div class="element"></div>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="we-social-box">
              <div class="we-social-title">
                <figure>
                  <img :src="baseUrl+'/front/images/follow-us-img.jpg'" alt="">
                </figure>
              </div>
              <div class="we-social-links">
                <ul>
                  <li>
                    <a :href="facebook" target="_blank">
                      <img :src="baseUrl+'/front/images/icons/ic-black-facebook.svg'" alt="">
                    </a>
                  </li>
                  <li>
                    <a :href="instagram" target="_blank">
                      <img :src="baseUrl+'/front/images/icons/ic-black-instagram.svg'" alt="">
                    </a>
                  </li>
                  <li>
                    <a :href="tiktok" target="_blank">
                      <img :src="baseUrl+'/front/images/icons/ic-black-tiktok.svg'" alt="">
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);">
                      <img :src="baseUrl+'/front/images/icons/ic-black-pinterest.svg'" alt="">
                    </a>
                  </li>
                </ul>
              </div>
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
