<style type="text/css">
  .col-md-2 {
    width: 14.667%;
}
</style>
{!! Form::open(['route' => 'statistics.index','method' => 'GET']) !!}
<div class="row">

 <div class='col-md-3'>
      <div class="form-group">
          <div class=''>
              {{ Form::select('search', [''=>'All Requests']+Config::get('custom.filter_project_status'), @$_GET['search'] , ['class' => 'form-control', 'id'=>'searchFilter']) }}
          </div>
      </div>
 </div>
    
</div>
{!! Form::close() !!}