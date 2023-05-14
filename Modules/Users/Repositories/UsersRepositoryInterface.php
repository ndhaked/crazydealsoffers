<?php

namespace Modules\Users\Repositories;


interface UsersRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($id);

    public function getUsersRoleList();

    public function getAll($request,$role);

    public function changeStatus($request,$slug);

    public function saveProfilePictureMedia($request);

    public function updateUserPassword($request);

}