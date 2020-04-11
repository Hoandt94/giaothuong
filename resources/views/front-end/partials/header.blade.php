<header id="header">
	<input type="hidden" id="base_url" value={{config('admin.base_url')}}>
	<div class="top-header container-fluil none-mobile">
		<div class="container">
			@if(isset($info->title))
			<ul class="nav navbar-nav navbar-left">
				<li>
					<a href="{{route('home')}}}" title="{{$info->title}}">{{$info->title}}</a>
				</li>
			</ul>
			@endif
			<ul class="nav navbar-nav navbar-right">
				<li class="li-tg">
					<a class="nav-link" href="{{route('shop.register')}}">Đăng ký gian hàng</a>
				</li>
				@if(auth()->check())
					@php
					$user = auth()->user();
					@endphp
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
							<img src="{{get_avatar($user)}}" class="user-image" alt="">
							<span class="hidden-xs">{{$user->name}}</span>
						</a>
						<ul class="dropdown-menu">
							<li class="user-header">
								<img src="{{get_avatar($user)}}" class="img-circle" alt="">
								<p>{{$user->name}}
									<small>{{$user->email}}</small>
								</p>
							</li>
							<li class="user-body">
								<div class="row">
									<div class="col-xs-6 text-center">
										<a href="{{route('dangtin')}}">Đăng cung cầu</a>
									</div>
									<div class="col-xs-6 text-center">
										<a href="{{route('userTinDang',['id'=>auth()->user()->id])}}">Tin đăng của tôi</a>
									</div>
								</div>
							</li>
							<li class="user-footer">
								<div class="pull-left">
									<a href="{{route('profile.get',['username'=>$user->username])}}" class="btn btn-default btn-flat">Trang cá nhân</a>
								</div>
								<div class="pull-right">
									<a class="btn btn-default btn-flat" href="{{route('logout')}}">Đăng xuất</a>
								</div>
							</li>
						</ul>
					</li>
				@else
					<li class="li-dn">
						<a class="nav-link" href="{{route('register')}}">Đăng ký</a>
					</li>
					<li class="li-dk">
						<a class="nav-link" href="{{route('login.get')}}">Đăng nhập</a>
					</li>
				@endif
			</ul>
		</div>
	</div>
	<div class="banner container-fluil">
		@if(isset($header->logo))
		<div class="logo">
			<a href="{{route('home')}}"><img src="{{config('admin.base_url').$header->logo}}" alt="{{isset($info->title) ? $info->title : ''}}"></a>
		</div>
		@endif
		<div class="container">
			<div class="row">
				<div class="col-md-1 wrap-search cart hrm-card-fixed">
					<div class="card-wrapper" id="small-cart">
						@include('front-end.partials.small-cart')
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluil">
		<div class="navbar navbar-default" role="navigation">
			<div class="">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<ul class="block-mobile regis">

						<li class="li-tg"><a href="#">Trợ giúp</a></li>

						<li class="li-dn">
							<a class="nav-link" href="{{route('register')}}">Đăng ký</a>
						</li>
						<li class="li-dk">
							<a class="nav-link" href="{{route('login')}}">Đăng nhập</a>
						</li>
					</ul>
				</div>
				<div class="navbar-collapse collapse container menu-hor" id="menu-top">
					<ul class="nav navbar-nav">
						<li class="dropdown dmsp" id="menu-left">
							<a class="dropdown-toggle toggle-button-dmsp" class="navbar-toggle" data-toggle="collapse"><i class="fa fa-list"></i>DANH MỤC SẢN PHẨM</a>
							<ul class="dropdown-menu menu-vertical-dmsp">
								@foreach($types as $type)
								<li  class="dropdown dropdown-submenu" >
									<a  class="dropdown-toggle"  href="{{route('category.detail',['slug'=>$type->slug])}}">
										{{$type->name}}
									</a>
									<i class="fa fa-angle-right pull-right subDropdown"></i>
									<ul id="menu0" class="dropdown-menu">
										@foreach($type->child->where('status',1)->get() as $child)
										<li >
											<a  href="{{route('category.detail',['slug'=>$child->slug])}}">
												{{$child->name}}
											</a>
										</li>
										@endforeach
									</ul>
								@endforeach
							</ul>
						</li>
						<li >
							<a  href="{{route('ketnoi')}}">
								<i class='fa '></i><span>Kết nối cung cầu</span>
							</a>
						</li>
						<li >
							<a  href="{{route('contact.get')}}">
								<i class='fa '></i><span>Liên hệ</span>
							</a>
						</li>
					</ul>
					<div id="search" class="search-form">
						<input type="text" id="text_search" name="search" value="{{isset($t_search) ? $t_search : ''}}"  placeholder=" Tìm kiếm thông tin" class="tt-input" autocomplete="off" spellcheck="false" dir="auto">
						<button class="iconSearchHeader" ><i class="fa fa-search"></i></button>
						<div id="mapId"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>