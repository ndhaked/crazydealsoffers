<?php

namespace Modules\Contactus\Repositories;


interface ContactusRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function getAjaxData($request);

    public function store($request);

    public function destroy($id);

    public function getStaticPageBySlug($slug);
}