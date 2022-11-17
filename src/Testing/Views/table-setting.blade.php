<html>
    <head>
        <title>table setting</title>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-grid.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-reboot.min.css')}}" />
    </head>
    <body>

        <div class="container">
            {!! session()->get('success') == 'true' ? '<div class="alert mt-3 alert-success">save successfully</div>' : '' !!}

            <a href="{{route('tableSoft.home')}}">
                <button class="btn mt-2 btn-dark">Home</button>
            </a>

            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <h1 class="mb-3">table setting</h1>

                    <form action="{{route('tableSoft.tableSetting.update')}}" method="post" enctype="application/x-www-form-urlencoded">

                        <div id="item-holder" class="form-group">
                            @if($siteData->tableSettings->count() && $siteData->tableSettings->fields)
                                @foreach($siteData->tableSettings->fields as $field)
                                    @include('tableSoft::table-setting-row', [
                                        'title' => $field->title ?? '',
                                        'name' => $field->name ?? '',
                                        'type' => $field->type ?? '',
                                        'sorting' => $field->sorting ?? '',
                                        'width' => $field->width ?? '',
                                        'widthMeasure' => $field->widthMeasure ?? '',
                                        'searchable' => $field->searchable ?? '',
                                    ])
                                @endforeach
                            @else
                                @include('tableSoft::table-setting-row')
                            @endif
                        </div>

                        <div class="row">
                            <button id="add-row" class="btn btn-primary" type="button">Add new column</button>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="paginate">table id:</label>
                            <input value="{{!empty($siteData->tableSettings->name) ? $siteData->tableSettings->name : ''}}" name="table_id" id="paginate" type="text" placeholder="table-id" />
                            @error('table_id')
                                <div class="alert-danger">{{$message}}</div>
                            @enderror
                            <span class="small">if 0, show all</span>
                        </div>
                        <div class="form-group">
                            <label for="paginate">paginate:</label>
                            <input value="{{!empty($siteData->tableSettings->paginate)? $siteData->tableSettings->paginate : ''}}" name="paginate" id="paginate" type="text" />
                            @error('paginate')
                                <div class="alert-danger">{{$message}}</div>
                            @enderror
                            <span class="small">if 0, show all</span>
                        </div>
                        <div class="form-group">
                            <label for="caching">caching:</label>
                            <input name="caching" id="caching" {{!empty($siteData->tableSettings->caching) && $siteData->tableSettings->caching? 'checked':''}} value="true" type="checkbox" />
                        </div>
                        <button class="btn btn-success" type="submit">Save</button>
                        @csrf
                    </form>

                </div>
            </div>
        </div>


        <script type="text/javascript" src="{{asset('assets/js/jquery-3.6.1.min.js')}}"></script>
        <script type="text/javascript">
            let CSRF_TOKEN = '{{csrf_token()}}';
            let ROUTE_AJAX = '{{route('tableSoft.tableSetting.ajax')}}';
        </script>
        <script type="text/javascript" src="{{asset('assets/js/script.js')}}"></script>
    </body>
</html>
