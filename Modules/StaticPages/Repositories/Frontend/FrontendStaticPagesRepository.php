<?php

namespace Modules\StaticPages\Repositories\Frontend;

use DB,Mail,Session;
use Illuminate\Support\Facades\Input;
use Modules\StaticPages\Entities\StaticPages;


class FrontendStaticPagesRepository implements FrontendStaticPagesRepositoryInterface {

    public $StaticPages;
    protected $model = 'StaticPages';

    function __construct(StaticPages $StaticPages) {
        $this->StaticPages = $StaticPages;
    }

    public function getRecordIdBySlug($slug)
    {
      return ($this->StaticPages->findBySlug($slug)) ? $this->StaticPages->findBySlug($slug)->id : NULL;
    }

    public function getRecordBySlug($slug)
    {
      return $this->StaticPages->findOrFail($this->getRecordIdBySlug($slug));
    }
}
