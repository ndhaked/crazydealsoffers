<footer>
    <div class="container">
        <div class="row">            
            <div class="col-lg-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="footer-block">
                            <ul class="footer-links">
                                <li>
                                    <a href="{{ route('advertiseaffiliated','affiliate') }}">
                                        Affiliate Disclosure
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('advertiseaffiliated','advertise') }}">
                                        Advertise With Us
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-block">
                            <ul class="footer-links">
                                <li>
                                    <a href="{{ route('static.pages','privacy-policy') }}">
                                        Privacy And Policy
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('static.pages','terms-and-condition') }}">
                                        Terms & Conditions
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
				<div class="footer-block">
							<ul class="footer-social">
								<li>

									<a href="{{ @$socialLinkData['facebook']['value'] }}" target="_blank">
										<img src="{{ asset('/front/images/icons/ic-white-facebook.svg') }}" alt="">
									</a>
								</li>
								<li>
									<a href="{{ @$socialLinkData['instagram']['value'] }}" target="_blank">
										<img src="{{ asset('/front/images/icons/ic-white-instagram.svg') }}" alt="">
									</a>
								</li>
								<li>
									<a href="{{ @$socialLinkData['tiktok']['value'] }}" target="_blank">
										<img src="{{ asset('/front/images/icons/ic-white-tiktok.svg') }}" alt="">
									</a>
								</li>
								<li>
									<a href="{{ @$socialLinkData['pinterest']['value']  }}" target="_blank">
										<img src=" {{ asset('/front/images/icons/ic-white-pinterest.svg') }}" alt="">
									</a>
								</li>
							</ul>
						</div>
            </div>
			
			<div class="col-lg-6 col-sm-12">
                <div class="footer-block">
                    <!-- -------------------------------------------------------- -->
                    <div class="newsletter-inner-sec product-detail-newsletter">
                        <div class="title-main text-white">
                            <h3 class="margin-bottom-15">
                                <span>
                                    Subscribe to our
                                </span>
                                Newsletter!
                            </h3>
                            <p>
                                All the hottest deals delivered straight to your inbox!
                            </p>
                        </div>
                        <form action="javascript:void(0);" id="subscribe_footer" name="subscribe_footer"
                            class="newsletter-form-sec" method="post">
                            @csrf
                            <input type="text" name="email_footer" id="email_footer" class="form-control"
                                autocomplete="off" placeholder="Email Address*" required>
                            <button type="submit" class="btn-primary white-btn" id="myButton" name="myButton"
                                value="Submit">Subscribe Now</button>
                        </form>
                    </div>
                    <!-- -------------------------------------------------------- -->

                </div>
            </div>
        </div>
    </div>
</footer>