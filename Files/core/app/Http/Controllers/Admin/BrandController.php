<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private $pageTitle;

    public function index()
    {
        $this->pageTitle = "All Brands";
        return $this->getBrands();
    }

    public function trashed()
    {
        $this->pageTitle = "Trashed Brands";
        return $this->getBrands(true);
    }

    protected function getBrands($trashed = false)
    {
        $pageTitle = $this->pageTitle;
        $search    = request()->search;
        $brands    = Brand::searchable(['name'])->withCount('products');

        if ($trashed) {
            $brands = $brands->onlyTrashed();
        }

        $brands = $brands->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.brand.index', compact('pageTitle', 'brands', 'trashed'));
    }

    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        if ($id == 0) {
            $brand        = new Brand();
            $notification = 'Brand created successfully';
        } else {
            $brand        = Brand::findOrFail($id);
            $notification = 'Brand updated successfully';
        }

        if ($request->hasFile('image_input')) {
            $oldImage = $brand->image;
            $brand->logo = fileUploader($request->image_input, getFilePath('brand'), getFileSize('brand'), $oldImage);
        }

        $brand->name             = $request->name;
        $brand->top              = $request->top ? 1 : 0;
        $brand->meta_title       = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_keywords    = $request->meta_keywords;
        $brand->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function changeStatus($id)
    {
        $brand = Brand::find($id);
        $brand->top = !$brand->top;
        $brand->save();

        $notify[] = ['success', $brand->top ? 'Set as top brand' : 'Removed from top brands'];
        return responseSuccess('status_updated', $notify);
    }

    protected function validation($request, $id)
    {

        $imgValidation  = $id ? 'nullable' : 'required';
        $validationRule = [
            'name'                  => 'required|string|max:255|unique:brands,name,' . $id,
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string|max:255',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'required_with:meta_keywords|string',
            'image_input'           => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ];
        $request->validate($validationRule, [
            'meta_keywords.array.*' => 'All keywords',
            'image_input.required'  => 'Logo field is required',
        ]);
    }

    public function delete($id)
    {
        $category = Brand::where('id', $id)->withTrashed()->first();

        if ($category->trashed()) {
            $category->restore();
            $notification = 'Brand restored successfully';
        } else {
            $category->delete();
            $notification = 'Brand deleted successfully';
        }
        $notify = ['success', $notification];
        return back()->withNotify($notify);
    }
}
