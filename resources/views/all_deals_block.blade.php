@if(count($allDeals)>0)
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
                    @foreach($allDeals as $item)
                    <li>
                        <a href="{{ route('details',$item->slug) }}">
                            <div class="deal-box">
							<div class="deal-source ribbon"><span>Amazon</span></div>
                            <figure>
                                <img src="{{ $item->S3Url }}" alt="{{ucwords($item->name)}}" title="{{ucwords($item->name)}}">
                                <?php /*
                                <div class="deal-bacth">
                                    @if($item->deal_of_the_day)
                                        <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="Deal of the day">
                                    @endif
                                </div>
                                @if($item->tag)
                                    <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$item->tag]) }}" alt="" class="deal-badge">
                                @endif
                                */ ?>
                            </figure>
                            <div class="deal-box-content">
                                <span>
                                    {{ ucfirst($item->name) }}
                                </span>
                                <strong>
                                    @if($item->price>0)
                                        ${{ number_format($item->price,2) }}
                                    @endif
                                </strong>
                            </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="text-center w-100 view-all-listing">
                    <a href="{{ route('products') }}" class="btn-primary margin-top-30" title="View All">
                    View All
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif