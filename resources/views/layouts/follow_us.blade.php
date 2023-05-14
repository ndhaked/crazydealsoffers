<section class="follow-us-sec">
    <div class="element"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="we-social-box">
                    <div class="we-social-title">
                        <figure>
                        <img src="{{ asset('/front/images/follow-us-img.jpg') }}" alt="">
                        </figure>
                    </div>
                    <div class="we-social-links">
                        <ul>
                            <li>
                                <a href="{{ @$socialLinkData['facebook']['value'] }}" target="_blank">
                                <img src="{{ asset('/front/images/icons/ic-black-facebook.svg') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="{{ @$socialLinkData['instagram']['value'] }}" target="_blank">
                                <img src="{{ asset('/front/images/icons/ic-black-instagram.svg') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="{{ @$socialLinkData['tiktok']['value'] }}" target="_blank">
                                <img src="{{ asset('/front/images/icons/ic-black-tiktok.svg') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="{{ @$socialLinkData['pinterest']['value'] }}" target="_blank">
                                <img src="{{ asset('/front/images/icons/ic-black-pinterest.svg') }}" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>