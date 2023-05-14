<template>
	
	<main class="main-wrapper">
    <section class="product-list-sec">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="title-main">
              <h3>
                Latest Deals                
              </h3>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="product-list-tabs">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All Products</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="product-1-tab" data-toggle="tab" href="#product-1" role="tab" aria-controls="product-1" aria-selected="false">Electronics &amp; Accessories</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="product-2-tab" data-toggle="tab" href="#product-2" role="tab" aria-controls="product-2" aria-selected="false">TVs, Appliances</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="product-3-tab" data-toggle="tab" href="#product-3" role="tab" aria-controls="product-3" aria-selected="true">Computer &amp; Accessories</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="product-4-tab" data-toggle="tab" href="#product-4" role="tab" aria-controls="product-4" aria-selected="false">Mobile &amp; Accessories</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="product-5-tab" data-toggle="tab" href="#product-5" role="tab" aria-controls="product-5" aria-selected="false">Women’s Fashion</a>
                </li>
              </ul>
              <div class="list-promotion-box">
                <figure>
                  <img :src="baseUrl+'/front/images/left-deals-list-promotion.svg'" alt="" class="list-promotion">
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
          <div class="col-lg-9 col-md-8">
            <div class="product-list-data-sec">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-4.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Apple iWatch Series 3 44mm Black
                            </span>
                            <strong>
                              $ 300.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-5.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Sony Experia M655
                            </span>
                            <strong>
                              $ 110.50
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-6.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Gucci Handbag
                            </span>
                            <strong>
                              $ 199.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-7.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Canon DSLR Camera with 18mm Lens...
                            </span>
                            <strong>
                              $ 100.30
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-8.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Hotwheels Remote Control Car... 
                            </span>
                            <strong>
                              $ 60.50
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-9.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Tommy Hilfiger Men’s Belt...
                            </span>
                            <strong>
                              $ 40.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-10.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Fair Design Men’s T-Shirt Orange
                            </span>
                            <strong>
                              $ 30.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="tab-pane fade" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-4.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Apple iWatch Series 3 44mm Black
                            </span>
                            <strong>
                              $ 300.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-5.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Sony Experia M655
                            </span>
                            <strong>
                              $ 110.50
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-6.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Gucci Handbag
                            </span>
                            <strong>
                              $ 199.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="tab-pane fade" id="product-2" role="tabpanel" aria-labelledby="product-2-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    
                  </ul>
                </div>
                <div class="tab-pane fade" id="product-3" role="tabpanel" aria-labelledby="product-3-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    
                  </ul>
                </div>
                <div class="tab-pane fade" id="product-4" role="tabpanel" aria-labelledby="product-4-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="tab-pane fade" id="product-5" role="tabpanel" aria-labelledby="product-5-tab">
                  <ul class="deal-list three-items">
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-1.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Selvatelle Leather Sandals
                            </span>
                            <strong>
                              $ 140.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-2.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              ALDO Men’s Chelsey Leather Boots
                            </span>
                            <strong>
                              $ 120.00
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="product-detail.html">
                        <div class="deal-box">
                          <figure>
                            <img :src="baseUrl+'/front/images/product-lists-3.jpg'" alt="">
                            <div class="deal-bacth">
                              <img :src="baseUrl+'/front/images/deal-batch-1.svg'" alt="">
                            </div>
                          </figure>
                          <div class="deal-box-content">
                            <span>
                              Boat Navy Blue 5V Headphones
                            </span>
                            <strong>
                              $ 99.99
                            </strong>
                          </div>
                        </div>
                      </a>
                    </li>
                    
                  </ul>
                </div>
              </div>
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
      </div>
    </section>  
  </main>

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
