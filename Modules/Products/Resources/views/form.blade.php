<div class="row">
    <div class="col-sm-12">
        <div class="form-group ermsg">
            <label class=" control-label">Select Category <span class="asterisk">*</span></label>
                 {!! Form::select('category_id',[''=>'Select Category']+$categories, null, ['required','class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Name<span class="asterisk">*</span></label>
            {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'SearchByName','placeholder'=>'Name of the product','title'=>'Please enter product name','autocomplete'=>'off','maxlength'=>'50','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
            {{ Form::hidden('product_id',null, ['id'=>'product_id']) }}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Price <span class="asterisk"></span></label>
                 {{ Form::text('price',fixPriceFormate(@$products->price), ['class'=>'form-control numberonly','id'=>'price','placeholder'=>'Price of the product','title'=>'Please enter price','step'=>'any','maxlength'=>'8','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
        </div>
    </div>
</div>

<div class="row">
   @include('products::upload_image')
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Coupon Code<span class="asterisk">*</span></label>
                 {{ Form::text('coupon_code',null, ['required','class'=>'form-control','id'=>'coupon_code','placeholder'=>'Coupon code','title'=>'Please enter coupon code','maxlength'=>'50','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Off On Product <span class="asterisk"></span></label>
                 {{ Form::text('off_on_product',fixPriceFormate(@$products->off_on_product), ['class'=>'form-control numberonly','id'=>'off_on_product','placeholder'=>'Off on product','title'=>'Off on product','step'=>'any','maxlength'=>'8','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Expiration Date<span class="asterisk">*</span></label>
                {{ Form::text('expiry_date', null, ['class'=>'form-control datetime', 'id'=>'expiry_date', 'required', 'data-date-format'=>'yyyy-mm-dd','placeholder'=>'Expiration Date','autocomplete'=>'off']) }}
                
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Item Purchase Link <span class="asterisk">*</span></label>
                 {{ Form::url('item_purchase_link',null, ['required','class'=>'form-control','id'=>'item_purchase_link','placeholder'=>'Item purchase link','title'=>'Please enter item purchase link','maxlength'=>'100','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
        </div>
    </div>
	 <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Facebook Page Id Connected to Instagram <span class="asterisk">*</span></label>
                 {{ Form::text('fb_page_id',545781699682971, ['required','class'=>'form-control','id'=>'fb_page_id','placeholder'=>'Facebook Page Id','title'=>'Please Facebook Page Id']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Select Tag <span class="asterisk"></span></label>
                 {!! Form::select('tag',[''=>'Select Tag']+config::get('custom.deal_tags'), null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group ermsg">
            <label class=" control-label">Description<span class="asterisk">*</span></label>
             <span class="ermsg"> 
                 {{ Form::textarea('description',null, ['required','class'=>'form-control','id'=>'description','placeholder'=>'Description','title'=>'Please enter description']) }}
             </span>
        </div>
    </div>
</div>

<!-------------social media post share------------------------------->
<div class="row">
    <div class="col-sm-12">
        <div class="form-group ermsg">
            <label class="control-label">Social media platforms</label>
        </div>
    </div>
</div>

@php $i=1; @endphp
@foreach($groupsList as $group)
{{--@if(in_array($group["id"],explode(',',env('FACEBOOK_GROUP_IDS')))) --}}
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <div class="" style="width: 6%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('facebook_pages[]',$group["id"], NULL,['class'=>'minimal social_checkbox','id'=>'facebook_'.$i]) }}
                </span>
            </div>
         
            <label class="control-label" style="float:left;">FB Group: {{$group["name"]}}<span class="asterisk"></span></label>
          
        </div>
        <div class="form-group col-sm-4" id="same_as_{{$i}}" style="display:none;">
            <label class="control-label" style="float:left;">Same description as above<span class="asterisk"></span></label>
            <div class="" style="width: 3%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('same_description['.$group["id"].']',1, true,['class'=>'minimal social_checkbox','id'=>'same_description'.$i]) }}
                </span>
            </div>
        </div>
    </div>    
    <div class="col-sm-12" style="display:none;" id="facebook_desc_{{$i}}">
        <div class="form-group">
        <label class=" control-label">{{$group["name"]}} Description</label>
            <span class="err_banner">
                {{ Form::textarea('facebook_description['.$group["id"].']',null, ['required','class'=>'form-control','id'=>'facebook_description_'.$group["id"],'placeholder'=>'Description','title'=>'Please enter description']) }}
            </span>
        </div>
    </div>    
</div>
@php $i++; @endphp
{{-- @endif --}}
@endforeach
@if(env('ENABLE_FACEBOOK_PAGES_POST')==true)
    @php  $j=1; @endphp
    @foreach($pagesInfo as $page_id=>$page_name)
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <div class="" style="width: 6%; float:left; margin-left: 2rem;">
                        <span class="err_banner">
                            {{ Form::checkbox('facebook_pages_business[]',$page_id, NULL,['class'=>'minimal social_checkbox_business','id'=>'facebook_business_'.$j]) }}
                        </span>
                    </div>
                 
                    <label class="control-label" style="float:left;">FB Page: {{$page_name}}<span class="asterisk"></span></label>
                  
                </div>
                <div class="form-group col-sm-4" id="same_as_business_{{$j}}" style="display:none;">
                    <label class="control-label" style="float:left;">Same description as above<span class="asterisk"></span></label>
                    <div class="" style="width: 3%; float:left; margin-left: 2rem;">
                        <span class="err_banner">
                            {{ Form::checkbox('same_description_business['.$page_id.']',1, true,['class'=>'minimal social_checkbox_business','id'=>'same_description_business_'.$j]) }}
                        </span>
                    </div>
                </div>
            </div>    
            <div class="col-sm-12" style="display:none;" id="facebook_desc_business_{{$j}}">
                <div class="form-group">
                <label class=" control-label">{{$page_name}} Description</label>
                    <span class="err_banner">
                        {{ Form::textarea('facebook_description_business['.$page_id.']',null, ['required','class'=>'form-control','id'=>'facebook_description_business_'.$page_id,'placeholder'=>'Description','title'=>'Please enter description']) }}
                    </span>
                </div>
            </div>    
        </div>
        @php $j++; @endphp
    @endforeach
@endIf
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <div class="" style="width: 6%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('instagram',1, NULL,['class'=>'minimal social_checkbox_instagram','id'=>'instagram']) }}
                </span>
            </div>
         
            <label class="control-label" style="float:left;">Instagram<span class="asterisk"></span></label>
          
        </div>
        <div class="form-group col-sm-4" id="same_as_instagram" style="display:none;">
            <label class="control-label" style="float:left;">Same description as above<span class="asterisk"></span></label>
            <div class="" style="width: 3%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('same_description_instagram',1, true,['class'=>'minimal social_checkbox_instagram','id'=>'same_description_instagram']) }}
                </span>
            </div>
        </div>
    </div>    
    <div class="col-sm-12" style="display:none;" id="instagram_desc">
        <div class="form-group">
        <label class=" control-label">Instagram Description</label>

            <span class="err_banner">
                {{ Form::textarea('instagram_description',null, ['required','class'=>'form-control','id'=>'instagram_description','placeholder'=>'Description','title'=>'Please enter description']) }}
            </span>
        </div>
    </div>    
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <div class="" style="width: 6%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('pinterest',1, NULL,['class'=>'minimal social_checkbox_pinterest','id'=>'pinterest']) }}
                </span>
            </div>
         
            <label class="control-label" style="float:left;">Pinterest<span class="asterisk"></span></label>
          
        </div>
        <div class="form-group col-sm-4" id="same_as_pinterest" style="display:none;">
            <label class="control-label" style="float:left;">Same description as above<span class="asterisk"></span></label>
            <div class="" style="width: 3%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('same_description_pinterest',1, true,['class'=>'minimal social_checkbox_pinterest','id'=>'same_description_pinterest']) }}
                </span>
            </div>
        </div>
    </div>    
    <div class="col-sm-12" style="display:none;" id="pinterest_desc">
        <div class="form-group">
        <label class=" control-label">Pinterest Description</label>

            <span class="err_banner">
                {{ Form::textarea('pinterest_description',null, ['required','class'=>'form-control','id'=>'pinterest_description','placeholder'=>'Description','title'=>'Please enter description']) }}
            </span>
        </div>
    </div>    
</div>


<?php /*
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label" style="float:left;">Product Remove<span class="asterisk"></span></label>
            <div class="" style="width: 3%; float:left; margin-left: 2rem;">
                <span class="err_banner">
                    {{ Form::checkbox('delete_status',1, NULL,['class'=>'minimal','id'=>'delete_status']) }}
                </span>
            </div>
        </div>
    </div>    
</div>
*/ ?>