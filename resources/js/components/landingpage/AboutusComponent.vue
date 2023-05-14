<template>
 <div class="about-area-custom" id="about">
		<div class="container">
			<section class="about-area aboutsecondFirst">
				<div class="">
					<div class="row align-items-center justify-content-between">
						<div class="col-lg-5 home-about-left animated7 fadeInLeft">
							<img class="img-fluid" :src="baseUrl+'/img/fea1.png'" alt="">
						</div>
						<div class="col-lg-6 home-about-right animated8 fadeInRight">
							<h1>
								Score the best deals all in one easy to use mobile app!
							</h1>
							<ul class="dash-list">
								<li> Popular items at a fraction of the price</li>
								<li> Never pay full price again at your favorite retailers Target, Macys, Amazon, Wal-Mart and more! </li>
								<li> Hundreds of new deals added daily</li>
								<li> Easy to follow steps to redeem coupons and codes</li>
								<li> Deal alerts sent straight to your phone</li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<section class="about-area aboutsecondsec pT-0">
				<div class="">
					<div class="row align-items-center justify-content-between">
						<div class="col-lg-6 home-about-right home-about-right2 animated7 fadeInLeft">
							<h1>
								CN Deals provides you the best deals, coupons, and discount codes!
							</h1>
							<ul class="dash-list">
								<li> Our team is just as passionate about saving money as you!</li>
								<li> We work around the clock to make sure you never miss a deal </li>
								<li> CN Deals works with brands and sellers around the globe to provide you exclusive discount codes! </li>
							</ul>
							<div class="download-button d-flex flex-row justify-content-start">
								<ul class="storeBtn">
									<li><a :href="androidUrl"><img :src="baseUrl+'/img/google-play.svg'" alt="image"></a></li>
									<li><a :href="iosUrl"><img :src="baseUrl+'/img/app-store.svg'" alt="image"></a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-5 home-about-left animated8 fadeInRight">
							<img class="img-fluid" :src="baseUrl+'/img/fea2.png'" alt="">
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
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
