<?php

namespace Modules\Slider\Repositories;


interface SliderRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function getAjaxData($request);

    public function store($request);

    public function update($request,$id);

    public function destroy($id);
    
    public function changeStatus($request,$slug);

    public function saveSliderPictureMedia($request);

    public function getSliderCount();

    public function getSliderOrder();

    public function getEditSliderOrder($num);

    public function sliderShowHide($status);
}