<template>
	
    <main class="main-wrapper">
      <section class="product-detail-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-lg-6">
              <div class="product-detail-left">
                <figure>
                  <img :src="baseUrl+'/front/images/product-detail-img.jpg'" alt="">
                  <div class="deal-bacth">
                    <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                  </div>
                </figure>
              </div>
            </div>
            <div class="col-md-12 col-lg-6">
              <div class="product-detail-right">
                <div class="detail-top-social">
                  <ul>
                    <li>
                      <a :href="facebook" target="_blank">
                        <img :src="baseUrl+'/front/images/icons/ic-pink-facebook.svg'" alt="">
                      </a>
                    </li>
                    <li>
                      <a :href="instagram" target="_blank">
                        <img :src="baseUrl+'/front/images/icons/ic-pink-instagram.svg'" alt="">
                      </a>
                    </li>
                    <li>
                      <a :href="tiktok" target="_blank">
                        <img :src="baseUrl+'/front/images/icons/ic-pink-tik-tok.svg'" alt="">
                      </a>
                    </li>
                    <li>
                      <a href="">
                        <img :src="baseUrl+'/front/images/icons/ic-pink-pinterest.svg'" alt="">
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="product-detail-top-info">
                    <h3 class="title-detail">
                      ALDO Men’s Chelsey Leather Boots
                    </h3>
                    <label class="product-label">
                      Man’s Fashion
                    </label>
                    <span class="product-date">
                      1 january,2021 - 9:30 P.M
                    </span>
                </div>
                <div class="product-detail-price-share">
                  <strong>
                    $ 120.00
                  </strong>
                  <div class="align-items-center d-flex">
                    <a :href="PRODUCT_LISTING">
                      <button class="btn btn-primary">
                        View Item 
                      </button>
                    </a>
                  <div class="custom-share-social">
                    <a href="">
                      <img :src="baseUrl+'/front/images/icons/ic-pink-share.svg'" alt="">
                    </a>
                  </div>
                  </div>
                </div>
                <div class="newsletter-inner-sec product-detail-newsletter">
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
                <div class="product-desc">
                  <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                  </p>
                  <p>
                    Link : <a href="javascript:void(0);">www.amazon.com/aldomens</a>
                  </p>
                  <p>
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                  </p>
                  <div class="coupon-time-validity">
                    Prices, deals, coupons are valid at the time of posting and can expire at any time.
                  </div>
                </div>
                <div class="product-detail-right-promo">
                  <figure>
                    <img :src="baseUrl+'/front/images/detail-promo-banner.svg'" alt="">
                    <div class="list-promotion-actions">
                      <a :href="androidUrl">
                        <img :src="baseUrl+'/front/images/icons/ic-google-play.svg'" alt="">
                      </a>
                      <a :href="iosUrl">
                        <img :src="baseUrl+'/front/images/icons/ic-app-store.svg'" alt="">
                      </a>
                    </div>
                  </figure>
                </div>
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
            PRODUCT_LISTING:PRODUCT_LISTING,
            socialData: [],
            facebook: '',
            instagram: '',
            tiktok: '',
            androidUrl: '',
		        iosUrl: '',
            fields:{
              device_uid : "DEV123",
              slug : '45-off-michael-kors',
            },
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
        },
        mounted() {
          axios.post(APP_URL+'/api/product-detail',this.fields,
          {
            headers: {
                      "Platform": "android",
                      "Version": "1.0.0",
                      "IsGguest":"true",
                  }
          }).then(response => {
		        this.listing = response.data.data
		      }).catch(error => {
                
            });
        }

    }
</script>
