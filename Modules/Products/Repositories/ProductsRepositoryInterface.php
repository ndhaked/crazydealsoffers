<?php

namespace Modules\Products\Repositories;

interface ProductsRepositoryInterface
{
	public function getAll($request);

	public function getCategories();

    public function store($request);

    public function saveProductPictureMedia($request);
    
    public function changeStatus($request,$id);
    
    public function getRecordBySlug($id);
    
    public function getRecord($id);
    
    public function update($request,$id);
    
    public function destroy($id);
    
    public function exportCSV();
    
    public function dealFTheDayStatus($request,$id,$status);
    
    public function getSuggessionDeals($request);

    public function getRemoveInactive();
}