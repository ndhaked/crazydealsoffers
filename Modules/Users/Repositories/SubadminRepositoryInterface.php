<?php

namespace Modules\Users\Repositories;


interface SubadminRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($id);

    public function store($request);

    public function edit($slug);

    public function update($request,$id);

    public function destroy($id);

}