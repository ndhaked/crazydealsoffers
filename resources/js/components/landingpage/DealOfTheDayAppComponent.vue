<template>
  <section class="deals-listing-sec">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="title-main">
            <h3>
              Deals of the day
            </h3>
          </div>
          <ul class="deal-list five-items">

            <li v-for='item in listing':key='item._id'>
              <a :href="baseUrl+'/product/details/'+item.slug">
                <div class="deal-box">
                  <figure>
                    <img :src="item.product_image_url" alt="">
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
          PRODUCT_DETAIL:PRODUCT_DETAIL,
		      listing: [],
		    }
		  },
        mounted() {
        axios.post(APP_URL+'/api/deal-off-the-day', 
        this.name,
        { 
          headers: {
		        "Platform": "android",
		        "Version": "1.0.0"
		      }
        }).then(response => {
		        this.listing = response.data.data
		      }).catch(error => {
                
            });
        }       
    }
</script>
