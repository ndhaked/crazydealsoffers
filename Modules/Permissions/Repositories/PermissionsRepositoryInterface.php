<?php

namespace Modules\Permissions\Repositories;


interface PermissionsRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($id);

    public function getAll();

    public function getAllWithGroupByGroupName();

    public function getAllDatatable($request);

    public function getRoute();
    
    public function createPermissions();

    public function assignRolePermissions($request,$slug);

    public function getPermissionGroups();

    public function getPermissionRouteLists();

    public function update($request,$id);
    
    public function destroy($id);
}