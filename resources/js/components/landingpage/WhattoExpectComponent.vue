<template>
	<section class="screenshot-area section-gap-top section-gap-bottom">
		<div class="container animated6 fadeInDown">
			<div class="row d-flex justify-content-center">
				<div class="col-lg-6">
					<div class="section-title text-center">
						<h2> What to Expect </h2>
						<p></p>
					</div>
				</div>
			</div>
				<div class="row">
					<div id="screenshortslider" class="owl-carousel owl-theme owl-screenshot">
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/Deal_Listing App_Splash.png'" alt="">
						</div>
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/Deal_Listing_App_Login.png'" alt="">
						</div>
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/Deal_Listing_App_Blog.png'" alt="">
						</div>
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/Deal_Listing_App_Blog_ Details.png'" alt="">
						</div>
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/Deal_Listing_App_Favorite.png'" alt="">
						</div>
						<div class="single-screenshot item">
							<img class="img-fluid" :src="baseUrl+'/img/mobile-item.png'" alt="">
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
		    }
		  },
        mounted() {
        }      
    }
</script>
