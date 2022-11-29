     
		
@include('common.header')
<div class="page-container">
    
@include('common.sidebar')
<div class='page-content-wrapper'>
<div class='page-content'>
	<div class="content-wrapper">
       @yield('content')
   </div>
</div>
</div>
	   </div>
	   
@include('common.footer')
   @yield('footer-scripts')
         
	