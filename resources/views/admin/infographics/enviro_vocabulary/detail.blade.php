@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            {{-- <a href="{{ route('subAdmin.show') }}" class="btn btn-info waves-effect waves-light">Back</a> --}}
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Detail Of Enviro Vocabulary</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Infographics<</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.enviroVocabulary')}}">Enviro Vocabulary List</a></li>
            <li class="breadcrumb-item active">Detail Of Enviro Vocabulary</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            

            <div class="row">
                <div class="col-lg-12 m-b-20">
                    <div id="accordion">
                        
                        
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="m-0">
                                    <a href="#collapseTwo" class="collapsed text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseTwo">Detail View Of Enviro Vocabulary</a>
                                </h6>
                            </div>
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Image: &nbsp;&nbsp;</lable>
                                                <img src="{{ $data['image'] }}" class="img-fluid" height="100px" width="100px">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Title: &nbsp;&nbsp;</lable>{{ $data['title'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Description: &nbsp;&nbsp;</lable>{{ $data['description'] }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        
                        
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>


@endsection
