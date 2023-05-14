<template>
	<section class="feature-area  animated6 fadeInDown" id="why">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="col-lg-6">
						<div class="section-title text-center">
							<h2>Why C.N. Deals & Coupons!</h2>
							<p> CN Deals works around the clock to find the best deals! We take the guesswork out and teach you how to stack savings! CN Deals works with brands and sellers worldwide to offer exclusive discount codes! </p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="single-feature">
							<a href="javascirpt:;" class="title">
								<span class="icon"><img :src="baseUrl+'/img/discount.png'" alt="image not found"></span>
								<h3>Daily Deals & Coupons!</h3>
							</a>
							<p>
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-feature">
							<a href="javascirpt:;" class="title">
								<span class="icon"><img :src="baseUrl+'/img/lessmoney.png'" alt="image not found"></span>
								<h3>Exclusive Discount Codes!</h3>
							</a>
							<p>
								
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-feature">
							<a href="javascirpt:;" class="title">
								<span class="icon"><img :src="baseUrl+'/img/like.png'" alt="image not found"></span>
								<h3>Stack Deals, Coupons and Codes for Ultimate Savings!</h3>
							</a>
							<p>
								
							</p>
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