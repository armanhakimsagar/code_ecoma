<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle     = "All Categories";
        $categories = $this->categoryTree();
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function trashed()
    {
        $pageTitle     = "Trashed Categories";
        $categories    = Category::onlyTrashed()->with(['allSubcategories'])->paginate(getPaginate());
        return view('admin.category.trashed', compact('pageTitle', 'categories'));
    }

    public function categoryTrashedSearch(Request $request)
    {
        if ($request->search != null) {
            $search         = trim(strtolower($request->search));
            $categories       = Category::onlyTrashed()->where('name', 'like', "%$search%")
                ->with(['subcategories'])
                ->orderByDesc('id')
                ->paginate(getPaginate());
            $pageTitle     = 'Trashed Category Search - ' . $search;
            return view('admin.category.trashed', compact('pageTitle', 'categories'));
        } else {
            return redirect()->route('admin.category.trashed');
        }
    }

    public function store(Request $request, $id = 0)
    {
        $validator = $this->validation($request, $id);

        if ($validator->fails()) {
            return responseError('error', $validator->errors());
        }

        // Check if parent category exists
        if ($request->parent_id) {

            $parentCategory = Category::with('parent')->where('id', '!=', $id)->find($request->parent_id);

            if (!$parentCategory) {
                return responseError('error', 'Invalid parent category selected');
            }

            if ($this->getDepthToRoot($parentCategory) >= 5) {
                return responseError('error', 'You have reached the maximum depth from the root category');
            }
        }

        if ($this->categoryExists($request, $id)) {
            return responseError('error', 'The name has already been taken');
        }

        $position = Category::where('parent_id', $request->parent_id)->count();

        $category = $id ?  Category::findOrFail($id) : new Category();
        $this->setCategoryAttributes($category, $request);
        $category->position = $position;
        $category->save();

        $message       = $id ? 'updated' : 'added';
        $notify[] = "Category $message successfully";
        return responseSuccess('category_' . $message, $notify, [
            'categoryId' => $category->id,
            'name' => $category->name,
            'parentId' => $category->parent_id ?? '#',
            'action' => $message
        ]);
    }

    public function delete(Request $request, $id)
    {
        $category = Category::where('id', $id)->with('subcategories')->withTrashed()->first();


        if ($category->trashed()) {
            $category->restore();
            $notify[] = ['success', 'Category restored successfully'];
        } else {
            if ($category->subcategories->count()) {
                if ($request->delete_child) {
                    $this->deleteSubCategory($category);
                } else {
                    Category::where('parent_id', $category->id)->update(['parent_id' => null]);
                }
            }

            $category->delete();
            $notify[] = ['success', 'Category deleted successfully'];
        }
        return back()->withNotify($notify);
    }

    private function deleteSubCategory($category)
    {
        $subCategories = Category::where('parent_id', $category->id)->get();
        if ($subCategories->count()) {
            foreach ($subCategories as $subCategory) {
                $subCat = Category::where('parent_id', $subCategory->id)->get();
                if ($subCat->count()) {
                    $this->deleteSubCategory($subCat);
                }
                $subCategory->delete();
            }
        }
    }

    protected function setCategoryAttributes($category, $request)
    {
        if ($request->hasFile('image')) {
            $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $category->image);
        }

        $category->parent_id          = $request->parent_id;
        $category->icon               = $request->icon;
        $category->name               = $request->name;
        $category->meta_title         = $request->meta_title;
        $category->meta_description   = $request->meta_description;
        $category->meta_keywords      = $request->meta_keywords;
        $category->is_top             = $request->is_top ? 1 : 0;
        $category->is_special         = $request->is_special ? 1 : 0;
        $category->in_filter_menu     = $request->in_filter_menu ? 1 : 0;
    }

    protected function categoryExists(Request $request, $id)
    {
        return Category::where('id', '!=', $id)->where('name', $request->name)->where('parent_id', $request->parent_id)->exists();
    }

    protected function getDepthToRoot(Category $category)
    {
        $depth = 0;
        while (!blank($category->parent)) {
            $category = $category->parent;
            $depth++;
        }
        return $depth;
    }

    public function updatePosition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'   => 'required|integer:gt:0',
            'parent_id'     => 'nullable|integer:gt:0',
            'position'      => 'required|int',
            'old_position'  => 'required|int',
        ]);

        if ($validator->fails()) {
            return responseError('error', $validator->errors());
        }

        $category = Category::find($request->category_id);

        if (!$category) {
            return responseError('error', 'Category not found');
        }

        $category->parent_id = $request->parent_id;
        $category->save();

        $categories = Category::where('parent_id', $request->parent_id)->orderBy('position')->get('id')->pluck('id')->toArray();

        moveElement($categories, $request->old_position, $request->position);

        foreach ($categories as $position => $id) {
            Category::where('id', $id)->update(['position' => $position]);
        }

        return responseSuccess('success', 'Updated');
    }

    public function categoryById($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->image_path = $category->categoryImage();
            return response()->json(['category' => $category]);
        }
        return response('Category not found', '404');
    }

    protected function validation($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'parent_id'             => 'nullable|integer:gte:0',
            'name'                  => 'required|string',
            'meta_title'            => 'nullable|string',
            'meta_description'      => 'nullable|string',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'nullable|string',
            'featured_category'     => 'nullable|integer|between:0,1',
            'filter_menu'           => 'nullable|integer|between:0,1',
            'image'                 => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'icon'                  => 'nullable|string',
        ]);

        return $validator;
    }

    protected function categoryTree()
    {
        return Category::isParent()
            ->with('allSubcategories', function ($q) {
                $q->orderBy('position');
            })
            ->orderBy('position')
            ->get();
    }
}
