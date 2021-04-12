@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Details Of Over All Point</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Score Board</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.overAllPoint')}}">Over All Point</a></li>
            <li class="breadcrumb-item active">Details Of Over All Point</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">


            <div class="row">
                <div class="col-lg-12 m-b-20">
                    <div id="accordion">


                        @php $i = 1 @endphp
                        @foreach($data as $itemOne)

                        <div class="card">
                            <div class="card-header" id="heading{{ $i }}">
                                <h6 class="m-0">
                                    <a href="#collapse{{ $i }}" class="collapsed text-dark" data-toggle="collapse" aria-expanded="{{ ($i == 1) ? 'true' : 'false' }}" aria-controls="collapse{{ $i }}">Detail View {{ $itemOne['champLevel'] }}</a>
                                </h6>
                            </div>
                            <div id="collapse{{ $i }}" class="collapse {{ ($i == 1) ? 'show' : '' }}" aria-labelledby="heading{{ $i }}" data-parent="#accordion">
                                <div class="card-body">

                                    <table id="responsive-datatable" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <td>Task Level</td>
                                                <td>Task Quarter</td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($itemOne['levelData'] as $itemTwo)
                                            <tr>
                                                <td>{{ $itemTwo['taskLevel'] }}</td>
                                                <td>

                                                    <table id="responsive-datatable" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <td>Task Quarter</td>
                                                                <td>Total</td>
                                                                <td>Complete</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($itemTwo['quarterData'] as $tempThree)
                                                            <tr>
                                                                <td>{{ $tempThree['taskQuarter'] }}</td>
                                                                <td>
                                                                    <div>
                                                                        <span>Task:- </span>
                                                                        <level>{{ $tempThree['totalTask'] }}</level>
                                                                    </div>
                                                                    <div>
                                                                        <span>Point:- </span>
                                                                        <level>{{ $tempThree['totalPoint'] }}</level>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <span>Task:- </span>
                                                                        <level>{{ $tempThree['taskDone'] }}</level>
                                                                    </div>
                                                                    <div>
                                                                        <span>Point:- </span>
                                                                        <level>{{ $tempThree['pointAchive'] }}</level>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        
                        @php $i++ @endphp
                        @endforeach


                    </div>
                </div>
            </div>


        </div>
    </div>
</div>




@endsection
