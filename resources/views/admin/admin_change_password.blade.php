@extends('admin.admin_dashboard')
@section('main')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Profile</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Contacts</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm order-2 order-sm-1">
                                    <div class="d-flex align-items-start mt-3 mt-sm-0">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xl me-3">
                                                <img src="{{ (!empty($profile_data->photo)) ? url('upload/admin_photo/'.$profile_data->photo) : url('upload/no_image.jpg')}} " alt="" class="img-fluid rounded-circle d-block">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>
                                                <h5 class="font-size-16 mb-1">{{$profile_data->name}}</h5>
                                                <p class="text-muted font-size-13">{{$profile_data->email}}</p>

                                                <div class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                                    <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{$profile_data->address}}</div>
                                                    <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{$profile_data->phone}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-auto order-1 order-sm-2">
                                    <div class="d-flex align-items-start justify-content-end gap-2">
                                        <div>
                                            <button type="button" class="btn btn-soft-light"><i class="me-1"></i> Message</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                    <div class="row">
                            <div class="col-12">
                                <div class="card">                                   
                                    <div class="card-body p-4">
                                      <form  action="{{route('admin.change.password.new')}}" method="post" ">
                                        @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div>
                                                        <div class="mb-3">
                                                            <label for="example-text-input" class="form-label">Current Password</label>
                                                            <input class="form-control @error('current_passowrd') is-invalid @enderror" name="current_passowrd" type="password"  id="example-text-input">
                                                            @error('current_passowrd')
                                                                <p class="text-danger">{{ $message }} </p>
                                                            @enderror
                                                        </div>
                                                    
                                                        <div class="mb-3">
                                                            <label for="example-email-input" class="form-label">New Password</label>
                                                            <input class="form-control  @error('new_password') is-invalid @enderror" name="new_password" type="password" id="example-email-input">
                                                            @error('new_password')
                                                                <p class="text-danger">{{ $message }} </p>
                                                            @enderror
                                                        </div>   
                                                        <div class="mb-3">
                                                            <label for="example-email-input" class="form-label">Comfirm New Password</label>
                                                            <input class="form-control  @error('confirm_password') is-invalid @enderror" type="password" name="confirm_password"  id="example-email-input">
                                                            @error('confirm_password')
                                                                <p class="text-danger">{{ $message }} </p>
                                                            @enderror
                                                        </div>   
                                                        <div class="mt-4">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update Password</button>
                                                        </div>                                                                                      
                                                    </div>
                                                </div>                                               
                                            </div>
                                      </form> 
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    <!-- end tab content -->
                </div>
                <!-- end col -->
                <!-- end col -->
            </div>
            <!-- end row -->
            
        </div> <!-- container-fluid -->
    </div>  
</div>


@endsection