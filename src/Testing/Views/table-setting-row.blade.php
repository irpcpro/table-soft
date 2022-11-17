<div class="row table-setting-items">
    <div class="col-md-3">
        <div class="form-group">
            <label for="title_[]">Title <span class="text-danger">*</span></label><br/>
            <input id="title_[]" name="title_[]" type="text" value="{{$title??''}}" />
            @error('title_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="fieldName_[]">Field name <span class="text-danger">*</span></label><br/>
            <input id="fieldName_[]" name="fieldName_[]" type="text" value="{{$name??''}}" /><br/>
            @error('fieldName_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
            <span class="small">case sensitive. without special characters.</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="fieldType_[]">Field type <span class="text-danger">*</span></label><br/>
            <input id="fieldType_[]" name="fieldType_[]" type="text" value="{{$type??''}}" /><br/>
            @error('fieldType_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
            <span class="small">int,string,float,date,bool</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="sorting_[]">Sorting</label><br/>
            <input id="sorting_[]" name="sorting_[]" type="text" value="{{$sorting??''}}" /><br/>
            @error('sorting_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
            <span class="small">fill to active sorting. asc,desc</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="width_[]">width</label><br/>
            <input id="width_[]" name="width_[]" type="text" value="{{$width??''}}" /><br/>
            @error('width_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="widthMeasure_[]">width measure</label><br/>
            <input id="widthMeasure_[]" name="widthMeasure_[]" type="text" value="{{$widthMeasure??''}}" style="width:70px"/><br/>
            @error('widthMeasure_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
            <span class="small">px,%</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="searchable_[]">Searchable</label><br/>
            <select class="form-control" name="searchable_[]" id="searchable_[]">
                <option {{$searchable? 'selected' : ''}} value="yes">yes</option>
                <option {{!$searchable? 'selected' : ''}} value="no">no</option>
            </select><br/>
            @error('searchable_.*')
                <div class="alert-danger">{{$message}}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <button type="button" class="removeRowItem btn btn-danger">remove</button>
        </div>
    </div>

</div>

<hr>
