<h1>Admin DashBoard</h1>

@if($errors->any())
   @foreach($errors->all() as $error)
     <p> {{ $error}}</p>
   @endforeach
@endif

@if(Session::has('error'))
   <p>{{Session::get('error')}}</p>
@endif

@if(Session::has('success'))
    <p>{{Session::get('success')}}
@endif


<a href="{{route('admin.logout')}}">Logout</a>