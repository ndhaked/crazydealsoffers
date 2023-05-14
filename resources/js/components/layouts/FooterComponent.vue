<template>
	
	<footer>
		<div class="container">
			<div class="row">
			<div class="col-sm-3">
				<div class="footer-block">
				<figure>
					<img :src="baseUrl+'/front/images/footer-logo.svg'" alt="">
				</figure>
				<span>
					Get timely updates from your favorite products
				</span>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="footer-block">
				<ul class="footer-links">
					<li>
						<a :href="AFFILIATED">
							Affiliate Disclosure
						</a>
					</li>
					<li>
						<a :href="ADVERTISE">
							Advertise With Us
						</a>
					</li>
				</ul>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="footer-block">
				<ul class="footer-links">
					<li>
						<a href="javascript:;">
							Privacy And Policy
						</a>
					</li>
					<li>
						<a href="javascript:;">
							Terms & Conditions
						</a>
					</li>
				</ul>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="footer-block">
				<ul class="footer-social">
					<li>
					<a :href="facebook" target="_blank">
						<img :src="baseUrl+'/front/images/icons/ic-white-facebook.svg'" alt="">
					</a>
					</li>
					<li>
					<a :href="instagram" target="_blank">
						<img :src="baseUrl+'/front/images/icons/ic-white-instagram.svg'" alt="">
					</a>
					</li>
					<li>
					<a :href="tiktok" target="_blank">
						<img :src="baseUrl+'/front/images/icons/ic-white-tiktok.svg'" alt="">
					</a>
					</li>
					<li>
					<a href="javascript:void(0);">
						<img :src="baseUrl+'/front/images/icons/ic-white-pinterest.svg'" alt="">
					</a>
					</li>
				</ul>
				</div>
			</div>
			</div>
		</div>
	</footer>

</template>
<script>
    export default {
    	  data() {
		    return {
				baseUrl: APP_URL,
				currentYear: new Date().getFullYear(),
				socialData: [],
				facebook: '',
				instagram: '',
				tiktok: '',
				AFFILIATED : AFFILIATED,
				ADVERTISE : ADVERTISE,
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