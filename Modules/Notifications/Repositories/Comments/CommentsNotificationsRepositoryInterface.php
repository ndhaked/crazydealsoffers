<?php

namespace Modules\Notifications\Repositories\Comments;


interface CommentsNotificationsRepositoryInterface
{
    public function getProductComments($request,$slug);

    public function getCommentById($id);
    
    public function destroy($id);
}