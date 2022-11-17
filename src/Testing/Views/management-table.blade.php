<html>
    <head>
        <title>table soft</title>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-grid.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-reboot.min.css')}}" />
    </head>
    <body>

        <div style="width:1100px;margin:0 auto;padding: 20px;">
            <form method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="q" value="{{request('q')}}" placeholder="search" />
                    <div class="input-group-prepend">
                        <button class="form-control btn btn-success ml-2">search</button>
                    </div>
                </div>

                <div class="row">
                    @if(!empty($data['sort_fields']) && $data['sort_fields'])
                        @foreach($data['sort_fields'] as $item)
                            <div class="col-12">
                                <span class="font-weight-bold text-dark">{{$item->title}}:</span>

                                <label for="{{$item->title}}_asc">ASC</label>
                                <input id="{{$item->title}}_asc" type="radio" name="sort-{{$item->name}}" {{ request('sort-'.$item->name) == 'asc'? 'checked' : '' }} value="asc" />
                                <span>-</span>
                                <label for="{{$item->title}}_desc">DESC</label>
                                <input id="{{$item->title}}_desc" type="radio" name="sort-{{$item->name}}" {{ request('sort-'.$item->name) == 'desc'? 'checked' : '' }} value="desc" />
                                <span>-</span>
                                <label for="{{$item->title}}_none">none</label>
                                <input id="{{$item->title}}_none" type="radio" name="sort-{{$item->name}}" {{ request('sort-'.$item->name) == 'none'? 'checked' : ''}} value="none" />
                            </div>
                        @endforeach
                        <div class="col-12">
                            <button class="btn btn-success">submit sort</button>
                        </div>
                    @endif
                </div>

            </form>

            @if($data['exists'])
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        @foreach($data['head'] as $head)
                            <th width="{{$head->width ? $head->width.$head->widthMeasure : ''}}">{{$head}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['body'] as $body)
                        <tr>
                            @foreach($body as $item)
                                <td width="{{$item->width ? $item->width.$item->widthMeasure : ''}}">{!! $item !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center">no data</div>
            @endif

            @if(count($data['body']) && $data['body'] && method_exists($data['body'],'total'))
                {!! $data['body']->appends(request()->except('page'))->render('pagination::bootstrap-4') !!}
            @endif
        </div>

    </body>
</html>
