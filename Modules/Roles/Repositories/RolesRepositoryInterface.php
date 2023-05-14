<?php

namespace Modules\Roles\Repositories;


interface RolesRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($id);

    public function getAll($request);

    public function store($request);

    public function edit($request,$slug);

    public function update($request,$id);

    public function destroy($id);
}