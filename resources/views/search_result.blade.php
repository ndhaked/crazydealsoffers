@extends('layouts.app')
@section('content')

<main class="main-wrapper">
    <section class="product-list-sec">
        <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-main">
                    <h3>{{ ucfirst($search) }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
            <div class="product-list-tabs">
               
                <div class="list-promotion-box">
                <figure>
                    <img src="{{ asset('/front/images/left-deals-list-promotion.svg') }}" alt="" class="list-promotion">
                    <div class="list-promotion-actions">
                    <a href="{{ $configuration['android-app-url'] }}">
                        <img src="{{ asset('/front/images/icons/ic-google-play.svg') }} " alt="">
                    </a>
                    <a href="{{ $configuration['ios-app-url'] }}">
                        <img src="{{ asset('/front/images/icons/ic-app-store.svg') }}" alt="">
                    </a>
                    </div>
                </figure>
                </div>
            </div>
            </div>
            <div class="col-lg-9 col-md-8">

            <div class="product-list-data-sec">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="all-tab">
                        @if(count($products)>0)
                            <ul class="deal-list three-items">
                                @foreach($products as $product)
                                    <li>
                                        <a href="{{ route('details',$product->slug) }}">
                                            <div class="deal-box">
                                                <figure>
                                                    <img src="{{ $product->S3Url }} " alt="">
                                                
                                                    <div class="deal-bacth">
                                                        @if($product->deal_of_the_day)
                                                            <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                                                        @endif
                                                    </div>
                                                
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
                            </ul>
                        @else
                            <div class="col-sm-12">
                                <div class="title-main">
                                    <h3>No products found...</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                </div>

                <div class="custom-pagination">
                    <nav aria-label="Page navigation example">
                        @if ($products->lastPage() > 1)
                            <ul class="pagination">
                                <li class="page-item {{ ($products->currentPage() == 1) ? ' disabled' : '' }}">
                                    <a href="{{ $products->url(1) }}&search={{$search}}"><img src="{{ asset('/front/images/icons/ic-arrow-left.svg') }}" alt=""></a>
                                </li>
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    <li class="page-item {{ ($products->currentPage() == $i)?' active':''}}">
                                        <a href="{{ $products->url($i) }}&search={{$search}}" class="page-link">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="{{ ($products->currentPage() == $products->lastPage()) ? ' disabled' : '' }}">
                                    <a href="{{ $products->url($products->currentPage()+1) }}&search={{$search}}" ><img src="{{ asset('/front/images/icons/ic-arrow-right.svg') }}" alt=""></a>
                                </li>
                            </ul>
                        @endif
                    </nav>
                </div>
            </div>
                

            </div>
        </div>
        </div>
    </section>  
</main>
@endsection
