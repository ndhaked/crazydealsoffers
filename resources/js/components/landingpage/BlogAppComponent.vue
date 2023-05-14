<template>
	<main class="main-wrapper">
		<section class="blog-sec">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
					<div class="title-main">
						<h3>
						Latest Blog              
						</h3>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-1.jpg'" alt="">
							</figure>
							<h3>
								21 Trending Products to Sell Online in 2021
							</h3>
							<span>
								1 january,2021 - 9:30 P.M
							</span>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-2.jpg'" alt="">
							</figure>
							<h3>
								21 Trending Products to Sell Online in 2021
							</h3>
							<span>
								1 january,2021 - 9:30 P.M
							</span>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-3.jpg'" alt="">
							</figure>
							<h3>
								21 Trending Products to Sell Online in 2021
							</h3>
							<span>
								1 january,2021 - 9:30 P.M
							</span>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-4.jpg'" alt="">
							</figure>
							<h3>
							21 Trending Products to Sell Online in 2021
							</h3>
							<span>
							1 january,2021 - 9:30 P.M
							</span>
							<p>
							Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-2.jpg'" alt="">
							</figure>
							<h3>
								21 Trending Products to Sell Online in 2021
							</h3>
							<span>
								1 january,2021 - 9:30 P.M
							</span>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="blog-list-box">
							<figure>
								<img :src="baseUrl+'/front/images/blog-3.jpg'" alt="">
							</figure>
							<h3>
								21 Trending Products to Sell Online in 2021
							</h3>
							<span>
								1 january,2021 - 9:30 P.M
							</span>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy. 
							</p>
							<a href="blog-detail.html" class="btn-text">
								+ READ MORE
							</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<div class="custom-pagination">
						<nav aria-label="Page navigation example">
							<ul class="pagination">
							<li class="page-item">
								<a class="page-link arrow-page" href="javascript:void(0);">
									<img :src="baseUrl+'/front/images/icons/ic-arrow-left.svg'" alt="">
								</a>
							</li>
							<li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
							<li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
							<li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
							<li class="page-item">
								<a class="page-link arrow-page" href="javascript:void(0);">
									<img :src="baseUrl+'/front/images/icons/ic-arrow-right.svg'" alt="">
								</a>
							</li>
							</ul>
						</nav>
					</div>
					</div>
				</div>
			</div>
		</section>
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
