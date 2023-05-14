<?php

namespace Modules\Notifications\Repositories;


interface NotificationsRepositoryInterface
{
    public function getRecord($id);

    public function getAjaxData($request);

    public function store($request);

    public function edit($request,$slug);

    public function resendNotifications($request,$id);

    public function destroy($id);

    public function getAllUsersPluck();

    public function getSuggessionDeals($request);
    
    public function getUsersLists($request);
}