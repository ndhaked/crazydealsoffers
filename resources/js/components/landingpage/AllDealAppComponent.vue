<template>
    <section class="deals-listing-sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                        <h3>
                            All Deals
                        </h3>
                    </div>
                    <ul class="deal-list owl-carousel owl-theme deal-list-slider">

                        <li v-for='item in listing':key='item._id'>
                            <a :href="baseUrl+'/product/details/'+item.slug">
                                <div class="deal-box">
                                <figure>
                                    <img :src="item.product_image_url" alt="" style="height:100px; weight:200px;">
                                    <div class="deal-bacth">
                                        <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                                    </div>
                                </figure>
                                <div class="deal-box-content">
                                    <span>
                                        {{ item.name }}
                                    </span>
                                    <strong>
                                        $ {{ item.price }}
                                    </strong>
                                </div>
                                </div>
                            </a>
                        </li>
                        
                    </ul>
                    <div class="text-center w-100 view-all-listing">
                        <a :href="PRODUCT_LISTING" class="btn-primary margin-top-30">
                        View All
                        </a>
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
                PRODUCT_LISTING:PRODUCT_LISTING,
                listing: [],
                fields:{
                    device_uid : "DEV123",
                },
		    }
		  },
        mounted() {
        	axios.post(APP_URL+'/api/product-list',this.fields,
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
