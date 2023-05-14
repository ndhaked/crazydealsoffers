<?php

namespace Modules\Advertisements\Repositories;

interface AdvertisementsRepositoryInterface
{
	public function getAll($request);

    public function getAjaxData($request);

    public function store($request);

    public function saveProductPictureMedia($request);

    public function changeStatus($request,$id);

    public function getRecordBySlug($id);

    public function getRecord($id);

    public function update($request,$id);

    public function destroy($id);

}