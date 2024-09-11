@extends('admin.layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/date-picker/spectrum.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">Thêm mới bài viết</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.exam.index')}}">Bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
        </ol>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <form action="{{route('admin.exam.update',$item->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="card-header ">
                        <div class="text-right col-md-12">
                            <button class="btn btn-primary" type="submit">Lưu</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" name="title" value="{{$item->title}}"
                                               placeholder="Nhập tiêu đề">
                                    </div>
                                    @if(!empty($files))
                                        @foreach($files as $k=>$file)
                                            <div class="form-group">
                                                <label class="form-label">File {{$k+1}}
                                                    @if($k==0)
                                                        (Tiếng anh:File trắc nghiệm | Luật: file nào cũng được)
                                                    @elseif($k==1)
                                                        (Tiếng anh:File đoạn văn | Luật: file nào cũng được)
                                                    @elseif($k==2)
                                                        (Tiếng anh:File điền từ còn thiếu vào đoạn văn | Luật: file nào
                                                        cũng được)
                                                    @elseif($k==3)
                                                        (Chỉ dành cho file trắc nghiệm vè luật)
                                                    @endif
                                                </label>
                                                <input type="file" name="content{{$k+1}}"
                                                       data-default-file="{{$file?asset($file):""}}"
                                                       class="dropify"/>
                                                @error("content" . ($k+1))
                                                <div class="alert alert-warning" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×
                                                    </button>{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @else

                                        <div class="form-group">
                                            <label class="form-label">File 1 (Tiếng anh:File trắc nghiệm | Luật: file
                                                nào cũng được)</label>
                                            <input type="file" name="content1"
                                                   class="dropify"/>
                                            @error('content1')
                                            <div class="alert alert-warning" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×
                                                </button>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">File 2 (Tiếng anh:File đoạn văn | Luật: file nào
                                                cũng được)</label>
                                            <input type="file" name="content2"
                                                   class="dropify"/>
                                            @error('content2')
                                            <div class="alert alert-warning" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×
                                                </button>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">File 3 (Tiếng anh:File điền từ còn thiếu vào đoạn
                                                văn | Luật: file nào cũng được)</label>
                                            <input type="file" name="content3"
                                                   class="dropify"/>
                                            @error('content3')
                                            <div class="alert alert-warning" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×
                                                </button>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">File 4 (Chỉ dành cho file trắc nghiệm vè
                                                luật)</label>
                                            <input type="file" name="content4"
                                                   class="dropify"/>
                                            @error('content4')
                                            <div class="alert alert-warning" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×
                                                </button>{{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Loại</label>
                                        <select name="type" class="form-control">
                                            <option value="3" {{$item->type==3?"selected":''}}>Kho bạc nhà nước</option>
                                            <option value="2" {{$item->type==2?"selected":''}}>Tiếng anh</option>
                                            <option value="1" {{$item->type==1?"selected":''}}>Công chức thuế</option>
                                        </select>
                                        @error('type')
                                        <div class="alert alert-warning" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                                ×
                                            </button>{{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="form-control select2" name="status">
                                            <option value=1 {{$item->status==1?'selected':''}}>Publish</option>
                                            <option value=0 {{$item->status==0?'selected':''}}>Disable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- TABLE WRAPPER -->
            </div>
            <!-- SECTION WRAPPER -->
        </div>
    </div>
    <!-- ROW-1 CLOSED -->

    </div>
    </div>
    <!-- CONTAINER CLOSED -->
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/date-picker/spectrum.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/time-picker/toggles.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
@endsection
