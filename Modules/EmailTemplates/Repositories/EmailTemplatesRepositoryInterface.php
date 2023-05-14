<?php

namespace Modules\EmailTemplates\Repositories;


interface EmailTemplatesRepositoryInterface
{
    public function getRecord($id);

    public function getAjaxData($request);

    public function store($request);

    public function edit($request,$slug);

    public function update($request,$id);

    public function destroy($id);
}