<div class="product-list-data-sec">
    <div class="tab-content" id="myTabContent">
        <input type="hidden" id="productCategory" value="{{$category}}">
        <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="all-tab">
            <ul class="deal-list three-items">
                @if(count($products)>0)
                    @foreach($products as $product)
                        <li>
                            <a href="{{ route('details',$product->slug) }}" title="View detail">
                                <div class="deal-box">
                                    @if($product->platform)
									<div class="deal-source ribbon"><span>{{ucwords($product->platform->name)}}</span></div>
                                    @endif
                                    <figure>
                                        <img src="{{ $product->S3Url }}" alt="Product Image" title="{{$product->name}}">
                                        <div class="deal-bacth">
                                            <?php /*
                                            @if($product->deal_of_the_day)
                                                <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                                            @endif
                                            */ ?>
                                        </div>
                                        <?php /*
                                        @if($product->tag)
                                            <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$product->tag]) }}" alt="" class="deal-badge">
                                        @endif
                                        */ ?>
                                    </figure>
                                    <div class="deal-box-content">
                                    <span>
                                        {{ $product->name }}
                                    </span>
                                    <strong>
                                        @if($product->price>0)
                                        ${{ number_format($product->price,2) }}
                                        @endif
                                    </strong>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @else
                No Record found
                @endif
            </ul>
        </div>
    </div>
    {!! $products->appends(array('search' => @$search))->links('front_dash_pagination') !!}
</div>
