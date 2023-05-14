<?php

namespace App\Repositories\UserProfile;


interface UserProfileRepositoryInterface
{
    public function getUserDetails($request);
    
    public function userUpdate($request);

    public function userChangePassword($request);

    public function getSignedURL($request);
    
    public function getHomepageData($request);
    
    public function getAutocompleteData($request);
    
    public function updateNotificationStatus($request);
    
    public function deleteAccountPermanently($request);
}

