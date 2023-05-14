<?php

namespace Modules\Blogs\Repositories;

interface BlogsRepositoryInterface
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

    public function removeImage($id);

}