<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $pageTitle     = "All Attribute Types";
        $attributes    = ProductAttribute::orderBy('id', 'desc')->searchable(['name', 'name_for_user'])->paginate(getPaginate());
        return view('admin.products.attributes.index', compact('pageTitle', 'attributes'));
    }

    public function store(Request $request, $id = 0)
    {
        $validation_rule = [
            'name'          => 'required|max:100',
            'name_for_user' => 'required|max:100',
            'type'          => 'required|integer|in:1,2,3'
        ];

        $request->validate($validation_rule);

        if($id ==0){
            $productAttribute = new ProductAttribute();
            $notify[] = ['success', 'Attribute Type Created Successfully'];
        }else{
            $productAttribute = ProductAttribute::findOrFail($id);
            $notify[] = ['success', 'Attribute Type Updated Successfully'];
        }
        $productAttribute->name            = $request->name;
        $productAttribute->name_for_user   = $request->name_for_user;
        $productAttribute->type            = $request->type;
        $productAttribute->save();

        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $productAttribute = ProductAttribute::where('id', $id)->withTrashed()->first();

        $productAttribute->delete();
        $notify[] = ['success', 'Product attribute deleted successfully'];
        return back()->withNotify($notify);

    }
}
