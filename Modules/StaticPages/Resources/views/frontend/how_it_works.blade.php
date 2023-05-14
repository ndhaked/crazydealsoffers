@extends('layouts.app')
@section('title',ucfirst($pageInfo->name)." ".trans('menu.pipe')." " .app_name())
@section('content')
		<section class="aboutblock sec_pd2 howitworks">
		<div class="rightabstract"></div>
		<div class="leftabstract"></div>
		<div class="container"> 
			<div class="section_title text-center mB30">
				<h2>How It Works</h2>
				<p class="subtext">Fully customizable and native to Axure. You can't help but smile.</p>
			</div> 
			<div class="brow_block">
				<ul class="nav nav-tabs justify-content-center mB60" id="myTab" role= "tablist">
					<li class="nav-item">
					    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
						    <img src="{{asset('img/svg/loan-fill.svg')}}" alt="image not found"/><span>Borrower</span></a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
						<img src="{{asset('img/svg/investor.svg')}}" alt="image not found"/><span>investor</span></a>
					</li>
				  </ul>
				  <div class="tab-content" id="myTabContent">
						<div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
							<div class="works_content">
								<div class="row">
									<div class="col-sm-12 col-md-7 order2">
											<div class="img_wrap">
												<img src="{{asset('img/h1.png')}}" class="w-100" alt="image not found">
											</div>
									</div>
									<div class="col-sm-12 col-md-5 order1">
										<div class="content">
											<div class="section_title">
												<p>1</p>
												<h2>One borrower loan</h2>
											</div>
											<p class="subtext">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
										</div>
									</div>
								</div>
								<div class="row sec_pd2 lineblck">
									<div class="col-sm-12 col-md-5 order1">
									<div class="content">
										<div class="section_title">
											<p>2</p>
											<h2>Split into many notes</h2>
										</div>
										<p class="subtext">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
									</div>
									</div>
									<div class="col-sm-12 col-md-7 order2">
										<div class="img_wrap">
											<img src="{{asset('img/h2.png')}}" class="w-100" alt="image not found">
										</div>
								</div>
							</div>
								<div class="row">
									<div class="col-sm-12 col-md-7 order2">
										<div class="img_wrap">
											<img src="{{asset('img/h1.png')}}" class="w-100" alt="image not found">
										</div>
									</div>
									<div class="col-sm-12 col-md-5 order1">
									<div class="content">
										<div class="section_title">
											<p>3</p>
											<h2>Your Notes Portfolio</h2>
										</div>
										<p class="subtext">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
									</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="works_content">
								<div class="row">
									<div class="col-sm-12 col-md-7 order2">
											<div class="img_wrap">
												<img src="{{asset('img/h1.png')}}" class="w-100" alt="image not found">
											</div>
									</div>
									<div class="col-sm-12 col-md-5 order1">
										<div class="content">
											<div class="section_title">
												<p>1</p>
												<h2>One borrower loan</h2>
											</div>
											<p class="subtext">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
				  </div>
			</div>
		</div>
	</section>
@endsection