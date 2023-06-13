@extends('layouts.app')
@section('title',$product['MetaTitle'])
@section('description',$product['MetaDescription'])
@section('content')
<main class="main-wrapper comment-form-page">
    <section class="product-detail-sec comment-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="product-detail-left">
						<div class="deal-source ribbon"><span>Amazon</span></div>
                        <figure>
                            <img src="{{ ($product->image!='')?$product->S3Url:'' }}" alt="">
<!--
                            <div class="deal-bacth">
                                @if($product->deal_of_the_day)
                                    <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                                @endif
                            </div>
-->
                            <!-- badge -->
<!--
                            @if($product->tag)
                                <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$product->tag]) }}" alt="Tag" class="deal-badge">
                            @endif
-->
                        </figure>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="product-detail-right">                        
                        <div class="product-detail-top-info">
                            <h3 class="title-detail">
                                {{ $product->name }}
                            </h3>
                            <label class="product-label">
                                {{ $product->category->name }}
                            </label>
                        </div>
                        <div class="media publisher-info my-4 p-2">
                            <img class="rounded-circle mr-3" src="{{$product->PublisherImage}}" width="30">
                            <div class="media-body">
                                Publisher: <strong>{{$product->PublisherName}}</strong><span></span>{{ date_format($product->created_at,"d F,Y - h:i A") }} (EDT)
                            </div>
                        </div>
                        <div class="product-detail-price-share">
                            <strong>
                                @if($product->price>0)
                                    ${{ number_format($product->price,2) }}
                                @endif
                            </strong>
                            <div class="align-items-center d-flex mobile-flex">
                            <a href="{{ $product->item_purchase_link }}" target="_blank">
                                <button class="btn btn-primary">
                                    View Item 
                                </button>
                            </a>
                            <div class="d-flex">
                                <div class="custom-share-social">
                                    <a href="###">
                                        <!-- AddToAny BEGIN -->
                                        <div class="">
                                            <a class="a2a_dd" href="https://www.addtoany.com/share">
                                                <img src="{{ asset('/front/images/icons/ic-pink-share.svg') }}" alt="">
                                            </a>
                                        </div>
                                        <script async src="https://static.addtoany.com/menu/page.js"></script>
                                        <!-- AddToAny END -->
                                    </a>
                                </div>&nbsp;&nbsp;
                                <div class="custom-share-social">
                                    <a href="###">
                                        <!-- AddToAny BEGIN -->
                                        <div class="">
                                                                    
                                            <a class="" href="javascript:void(0);">
                                                <img onclick="copyToClipboard('#p1')" src="{{ asset('/front/images/icons/clipboard.svg') }}" alt="" title="Click to copy">
                                                <p id="p1" style="display:none;">{{ url()->current() }}</p>
                                            </a>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>
						
						<div class="detail-top-social">
                            <ul>
                                <li>
                                    <a href="{{ @$socialLinkData['facebook']['value'] }}" target="_blank">
                                    <img src="{{ asset('/front/images/icons/ic-pink-facebook.svg') }}" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ @$socialLinkData['instagram']['value'] }}" target="_blank">
                                    <img src="{{ asset('/front/images/icons/ic-pink-instagram.svg') }}" alt="">
                                    </a>
                                </li>   
                                <li>
                                    <a href="{{ @$socialLinkData['tiktok']['value'] }}" target="_blank">
                                    <img src="{{ asset('/front/images/icons/ic-pink-tik-tok.svg') }}" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ @$socialLinkData['pinterest']['value'] }}" target="_blank">
                                        <img src="{{ asset('/front/images/icons/ic-pink-pinterest.svg') }}" alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>
						
						<div class="newsletter-inner-sec product-detail-newsletter">
                            <div class="title-main">
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
                            <form action="javascript:void(0);" id="subscribe" name="subscribe" class="newsletter-form-sec" method="post" > 
                                @csrf
                                <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email Address*" required>
                                <button type="submit" class="btn-primary white-btn" id="myButton" name="myButton" value="Submit">Subscribe Now</button>
                            </form>
                        </div>	
                       
                    </div>                   
                </div>
				
            </div>
        </div>
		
		<div class="product-details-area">
			<div class="row">
				<div class="container">
					<div class="col-lg-12">
                       <div class="product-desc">
                              <p>
                                  {!! $product->description !!}
                              </p>
                              <!-- <p>
                                  Link : <a href="{{ $product->item_purchase_link }}" target="_blank">{{ substr($product->item_purchase_link,0,50) }}</a>
                              </p> -->

                              <div class="coupon-time-validity">
                                  {{ trans('flash.info.price_can_expire_any_time') }}
                                  <a href="{{ route('advertiseaffiliated','affiliate') }}" target="_blank">{{ trans('flash.info.affiliate_disclosure_here') }} </a>
                              </div>
                          </div>

                       <div class="product-detail-right-promo">
                          <figure>
                              <img src="{{ asset('/front/images/detail-promo-banner.svg') }}" alt="">
                              <div class="list-promotion-actions">
                                  <a href="{{ @$socialLinkData['android-app-url']['value'] }}">
                                  <img src="{{ asset('/front/images/icons/ic-google-play.svg') }}" alt="">
                                  </a>
                                  <a href="{{ @$socialLinkData['ios-app-url']['value'] }}">
                                  <img src="{{ asset('/front/images/icons/ic-app-store.svg') }}" alt="">
                                  </a>
                              </div>
                          </figure>   
                      </div>

                      @if(count($product->commentsLatestTwo)>0)
                      <div class="comment-section mt-5">
                          <div class="container">
                              <div class="d-flex justify-content-center align-items-center row">
                                  <div class="col-8 col-sm-8">
                                      <h3 class="title-comment">Comments</h3>
                                  </div>
                                  <div class="col-4 col-sm-4 text-right">
                                      <a href="javascript:;" class="text-link viewAllComments" data-toggle="modal" data-target="#CommentsModal" data-href="{{route('front.getProductsCommnetsForFront',$product->slug)}}">View All</a>
                                  </div>
                              </div>
                          </div>
                          <div class="container mt-4">
                              <div class="d-flex justify-content-center row">
                                  <div class="col-md-12">
                                      @foreach($product->commentsLatestTwo as $comment)
                                          <div class="d-flex flex-column comment-section-list">
                                              <div class="p-0">
                                                  <div class="d-flex flex-row user-info">
                                                      <img class="rounded-circle" src="{{$comment->PublisherImage}}" width="50" alt="User Image" onerror="this.src='{{onerrorReturnImage()}}'">
                                                      <div class="d-flex flex-row justify-content-between w-100">
                                                          <div class="d-flex flex-column justify-content-center ml-2">
                                                              <span class="d-block name">{{$comment->PublisherName}}</span>
                                                              <span class="author">
                                                                  @if($comment->IsAdminWithRole)
                                                                      {{$comment->IsAdminWithRole}} <img src="{{ asset('/images/verified.svg') }}" alt="" class="verified">
                                                                  @endif
                                                                  &nbsp;
                                                              </span>
                                                          </div>
                                                          <div class="d-flex flex-column justify-content-center ml-2">
                                                              <span class="date">{{$comment->created_at->diffForHumans()}}</span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="comments-view">
                                                      <div class="mt-2">
                                                          <p class="comment-text">{{ $comment->comment }}</p>
                                                      </div>
                                                      <div class="text-right">
                                                          <span class="likes-text"><img src="{{ asset('/images/like.svg') }}" alt="" class="likes">{{$comment->likes()}}</span>
                                                      </div>
                                                      @if($comment->replies()->count() > 0)
                                                      <div class="d-flex mb-3">
                                                          <a href="javascript:;" class="more-comments replypoptoggle"  data-toggle="modal" data-target="#CommentsModal" data-href="{{route('front.getProductsCommnetsForFront',$product->slug)}}" data-cmntid="replyblk{{$comment->id}}">View {{$comment->replies()->count()}} Replies</a>
                                                      </div>
                                                      @endif
                                                  </div>
                                              </div>
                                          </div>
                                      @endforeach
                                      <div class="d-flex flex-column comment-section-list no-more-comments">
                                          <div class="no-more-comment-bottom">
                                              <p class="m-0 p-0">These comments were made via app only</p>
                                              <input type="hidden" name="page" id="page" value="1">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      @endif
                  </div>			
				</div>
			</div>
		</div>
    </section>
</main>
<style>
    header {
    position: inherit !important;
    height: 80px;
}
</style>
<div class="modal fade" id="CommentsModal" tabindex="-1" role="dialog" aria-labelledby="CommentsModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h2>Comments</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body comment_model">
        <div class="container mt-4">
            <div class="d-flex justify-content-center row">
                <div class="col-md-12" id="data-wrapper">
                </div>
                <div class="col-md-12 auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
                <div class="col-md-12 auto-load-no-data text-center">
                </div>
            </div>
        </div>
        </div>
        <div class="no-more-comment-bottom">
            <p class="m-0 p-0">These comments were made via app only</p>
        </div>
    </div>
    </div>
</div>
@endsection
@section('script')
 <script type="text/javascript">
    var ENDPOINT_URL = "{{route('front.getProductsCommnetsForFront',$product->slug)}}";
    var page = $("#page").val();
    $(document).on('click', '.viewAllComments', function(e) { 
        $("#page").val(1);
        var page = $("#page").val();
        $('.auto-load-no-data').html('');
        $("#data-wrapper").html('');
        infinteLoadMore(page);
    });
    $('.comment_model').on('scroll', function () { 
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                page = $("#page").val();
                $.ajax({
                    url: ENDPOINT_URL + "?page=" + page,
                    datatype: "html",
                    type: "get",
                    beforeSend: function () {
                        $('.auto-load').show();
                    }
                })
                .done(function (response) {
                    if (response.length == 0) {
                        $('.auto-load-no-data').html("We don't have more data to display :(");
                        $('.auto-load').hide();
                        return;
                    }
                    $('.auto-load').hide();
                    $("#data-wrapper").append(response);
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
                page++;
                $("#page").val(page);
        }
    });
    infinteLoadMore(page);
    function infinteLoadMore(page) {
        $.ajax({
                url: ENDPOINT_URL + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-load').show();
                }
            })
            .done(function (response) {
                if (response.length == 0) {
                    $('.auto-load').html("We don't have more data to display :(");
                    return;
                }
                page++;
                $("#page").val(page);
                $('.auto-load').hide();
                $("#data-wrapper").append(response);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
    $("body").delegate(".replypoptoggle", "click", function () {
        var _replyBlockId = $(this).data("cmntid");
        $("#"+_replyBlockId).show();
        $("."+_replyBlockId).removeClass('d-flex').hide();
    });
 </script>
@endsection
