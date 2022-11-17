<?php

namespace Irpcpro\TableSoft\Testing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Irpcpro\TableSoft\Testing\Models\Product;
use Irpcpro\TableSoft\Testing\Models\TableSetting;
use TableSoft;

class DashboardController
{

    public function home()
    {
        return view('tableSoft::home');
    }

    public function tableSetting()
    {
        $tableSettings = TableSetting::first();

        $siteData = (object)[
            'tableSettings' => $tableSettings ?? collect([])
        ];


        return view('tableSoft::table-setting', compact('siteData'));
    }

    public function tableSettingGetRow(Request $request)
    {
        $row = view('tableSoft::table-setting-row')->render();
        return response()->json($row);
    }

    public function tableSettingUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required',
            'paginate' => 'required|numeric|min:0',
            'caching' => '',

            'title_.*' => 'required',
            'fieldName_.*' => 'required',
            'fieldType_.*' => 'required',
            'sorting_.*' => 'nullable|in:asc,desc',
            'width_.*' => 'nullable|numeric',
            'widthMeasure_.*' => 'nullable|in:%,px',
            'searchable_.*' => 'nullable',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $collectData = [];
        $i = 0;
        foreach ($request->input('title_') as $item){
            if(!isset($item))
                break;
            $collectData[] = [
                'title' => $request->input('title_')[$i],
                'name' => $request->input('fieldName_')[$i],
                'type' => $request->input('fieldType_')[$i],
                'sorting' => $request->input('sorting_')[$i],
                'width' => $request->input('width_')[$i],
                'widthMeasure' => $request->input('widthMeasure_')[$i],
                'searchable' => $request->input('searchable_')[$i] == 'yes',
            ];
            $i++;
        }

        // import to db
        TableSetting::updateOrCreate([
            'name' => $request->input('table_id'),
        ],[
            'fields' => $collectData,
            'caching' => $request->caching == 'true',
            'paginate' => $request->input('paginate', 0),
        ]);

        return redirect()->back()->with(['success' => 'true']);
    }

    public function managementTable()
    {
        $data = collect([]);
        // get table settings
        $tableSetting = TableSetting::query();
        if($tableSetting->exists()){
            // get data
            $data = Product::query();

            // get table setting
            $tableSetting = $tableSetting->first();

            // mage table soft
            $table = TableSoft::data($data);

            // make fields
            foreach ($tableSetting->fields as $field) {
                $table = $table->column($field->title, $field->name.':'.$field->type, $field->sorting != null ? 'sort:'.$field->sorting : null);

                if($field->searchable)
                    $table = $table->searchable();

                if($field->width)
                    $table = $table->setWidth($field->width, $field->widthMeasure);
            }

            if($tableSetting->caching)
                $table = $table->setCaching($tableSetting->name);

            if($tableSetting->paginate != 0)
                $table = $table->paginate($tableSetting->paginate);

            $data = $table->get();
        }

        // return view
        return view('tableSoft::management-table', compact('data'));
    }

}
