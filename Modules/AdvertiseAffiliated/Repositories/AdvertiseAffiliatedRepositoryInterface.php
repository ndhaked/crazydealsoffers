<?php

namespace Modules\AdvertiseAffiliated\Repositories;

interface AdvertiseAffiliatedRepositoryInterface
{
	public function getAll($request);

    public function getAjaxData($request);

    public function saveProductPictureMedia($request);

    public function changeStatus($request,$id);

    public function getRecordBySlug($slug);

    public function getRecord($id);

    public function update($request,$id);

}