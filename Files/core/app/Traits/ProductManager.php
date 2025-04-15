<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\AssignProductAttribute;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductShippingMethod;
use App\Models\ProductStock;
use App\Models\ShippingMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;

trait ProductManager
{
    protected function pageTitle($isTrashed, $searchKey)
    {
        if ($isTrashed) $title  =  "All Trashed Products";
        else $title  =  "All Products";

        if ($searchKey) $title  = "Product Search : '$searchKey'";
        return $title;
    }

    public function products($sellerId = 0, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));
        $query  = Product::query();

        if ($sellerId) $query  = $query->sellers();
        if ($isTrashed) $query = $query->onlyTrashed();

        if (request()->self_product) {
            $query->where('seller_id', 0);
        }

        $query = $query->with(['categories', 'brand', 'stocks']);

        $data['products']       = $query->searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
        $data['pageTitle']      = $this->pageTitle($isTrashed, $search);

        return $data;
    }

    public function pendingProducts($sellerId = 0, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));

        $query  = Product::query();
        if ($sellerId) {
            $query  = $query->sellers();
            $query  = $query->with(['categories', 'brand', 'stocks']);
        }
        if ($isTrashed)
            $query  = $query->onlyTrashed();
        if ($search)
            $query  = $query->where('name', 'like', "%$search%");

        $data['products']       = $query->where('status', 0)->orderByDesc('id')->paginate(getPaginate());
        $data['pageTitle']      = 'Pending Products';
        $data['emptyMessage']   = "No product found";
        return $data;
    }
    public function productByVendor($admin = true, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));

        $query  = Product::query();
        if ($isTrashed)
            $query  = $query->onlyTrashed();
        if ($search)
            $query  = $query->where('name', 'like', "%$search%");
        if ($admin) {
            $data['pageTitle']      = 'Products By Admin';
            $query = $query->where('seller_id', 0);
        } else {
            $data['pageTitle']      = 'Products By Seller';
            $query = $query->where('seller_id', '!=', 0);
        }

        $data['products']       = $query->orderByDesc('id')->paginate(getPaginate());
        return $data;
    }

    public function productCreate()
    {
        $data['categories'] = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']     = Brand::orderBy('name')->get();
        $data['pageTitle']  = "Add New Product";
        return $data;
    }

    public function editProduct($id, $sellerId = 0)
    {
        if ($sellerId) {
            $data['product']    = Product::where('seller_id', $sellerId)->where('id', $id)
                ->with('categories', 'productPreviewImages')->firstOrFail();
        } else {
            $data['product']    = Product::where('id', $id)->with('categories', 'productPreviewImages')->first();
        }

        $data['categories']     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']         = Brand::orderBy('name')->get();
        $data['images']         = [];

        foreach ($data['product']->productPreviewImages as $key => $image) {
            $img['id'] = $image->id;
            $img['src'] = getImage(getFilePath('product') . '/' . $image->image);
            $data['images'][] = $img;
        }

        $data['pageTitle']      = "Edit Product";
        return $data;
    }


    public function storeProduct($request, $id, $sellerId = 0)
    {
        $validationRules = $this->getProductValidationRule($id);
        $request->validate($validationRules, [
            'specification.*.name.required'   =>  'All specification name is required',
            'specification.*.value'           =>  'All specification value is required',
        ]);

        //Check if the sku is already taken
        if ($request->sku && $this->checkSKU($request->sku, $id)) {
            $notify[] = ['error', 'This SKU has already been taken'];
            return $notify;
        }

        $product = new Product();

        if ($id) {
            $product                = Product::findOrFail($id);
            $prev_track_inventory   = $product->track_inventory;
            $prev_has_variants      = $product->has_variants;

            if ($sellerId && $product->seller_id != $sellerId) {
                $notify[] = ['error', 'This product doesn\'t belong to this seller'];
                return $notify;
            }
        }

        if ($sellerId) {
            $product->status = gs('product_auto_approval') ? Status::ENABLE : Status::DISABLE;
        } else {
            $product->status = Status::ENABLE;
        }

        if ($request->hasFile('main_image')) {
            try {
                $product->main_image = fileUploader($request->main_image, getFilePath('product'), getFileSize('product'), @$product->main_image, getFileThumb('product'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the main image'];
                return $notify;
            }
        }

        $product->seller_id         = $sellerId;
        $product->brand_id          = $request->brand_id ?? 0;
        $product->sku               = $request->sku ?? null;
        $product->name              = $request->name;
        $product->slug              = $request->slug;
        $product->model             = $request->model;
        $product->has_variants      = $request->has_variants ?? 0;
        $product->track_inventory   = $request->track_inventory ?? 0;
        $product->show_in_frontend  = $request->show_in_frontend ?? 0;
        $product->video_link        = $request->video_link;
        $product->description       = $request->description;
        $product->summary           = $request->summary;
        $product->specification     = $request->specification ?? null;
        $product->extra_descriptions = $request->extra ?? null;
        $product->base_price        = $request->base_price;
        $product->meta_title        = $request->meta_title;
        $product->meta_description  = $request->meta_description;
        $product->meta_keywords     = $request->meta_keywords ?? null;
        $product->save();

        //Check Old Images
        $previous_images = $product->productPreviewImages->pluck('id')->toArray();
        $imageToRemove = array_values(array_diff($previous_images, $request->old ?? []));

        foreach ($imageToRemove as $item) {
            $productImage   = ProductImage::find($item);
            $location       = getFilePath('product');

            fileManager()->removeFile($location . '/' . $productImage->image);
            fileManager()->removeFile($location . '/thumb_' . $productImage->image);
            $productImage->delete();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $image) {
                try {
                    $image = fileUploader($image, getFilePath('product'), getFileSize('product'), null, getFileThumb('product'));
                    $productImage = new ProductImage();
                    $productImage->product_id   = $product->id;
                    $productImage->image        = $image;
                    $productImage->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload additional images'];
                    return $notify;
                }
            }
        }

        $message  = 'Product added successfully';

        $categories = is_array($request->categories) ? array_filter($request->categories) : [];

        if ($id) {
            $product->categories()->sync($categories);
            $message = 'Product updated successfully';

            //If the value of track_inventory or has_variants is changed then delete the prev inventory
            if (($prev_has_variants != $product->has_variants) || $prev_track_inventory != $product->track_inventory) {
                $product_stocks = $product->stocks();
                foreach ($product_stocks->get() as $st) {
                    $st->stockLogs()->delete();
                }
                $product_stocks->delete();
            }

            // Check stock table to update the sku in stock table
            if ($product->sku) {
                $stock = ProductStock::where('id', $product->id)->first();
                if ($stock) {
                    $stock->sku = $product->sku;
                    $stock->save();
                }
            }

            $assignAttributes = AssignProductAttribute::where('product_id', $product->id)->get();
            if (!$product->has_variants) {
                foreach ($assignAttributes as $assignAttribute) {
                    $assignAttribute->status = 0;
                    $assignAttribute->save();
                }
            } else {
                foreach ($assignAttributes as $assignAttribute) {
                    $assignAttribute->status = 1;
                    $assignAttribute->save();
                }
            }
        } else {
            $product->categories()->attach($categories);
        }

        $notify[] = ['success', $message];
        return $notify;
    }

    public function deleteProduct($id, $sellerId = 0)
    {
        $query    = Product::where('id', $id);
        if ($sellerId) $query = $query->where('seller_id', $sellerId);

        $product  = $query->firstOrFail();
        $product->delete();

        $notify[] = ['success', "Product deleted successfully"];
        return $notify;
    }

    public function restoreProduct($id, $sellerId = 0)
    {
        if ($sellerId) {
            $product = Product::withTrashed()->where('seller_id', $sellerId)->findOrFail($id);
        } else {
            $product = Product::withTrashed()->findOrFail($id);
        }

        $product->restore();
        $notify[] = ['success', "Product restored successfully"];
        return $notify;
    }

    protected function getProductValidationRule($id)
    {
        $rules =  [
            'name'                  => 'required|string|max:191',
            'model'                 => 'nullable|string|max:100',
            'brand_id'              => 'nullable|integer',
            'base_price'            => 'required|numeric',
            "categories"            => 'nullable|array|min:1',
            'has_variants'          => 'sometimes|required|numeric|min:1|max:1',
            'track_inventory'       => 'sometimes|required|numeric|min:1|max:1',
            'show_in_frontend'      => 'sometimes|required|numeric|min:1|max:1',
            'description'           => 'nullable|string',
            'summary'               => 'nullable|string|max:360',
            'sku'                   => 'nullable',
            'extra'                 => 'sometimes|required|array',
            'extra.*.key'           => 'required_with:extra',
            'extra.*.value'         => 'required_with:extra',
            'specification'         => 'sometimes|required|array',
            'specification.*.name'  => 'required_with:specification',
            'specification.*.value' => 'required_with:specification',
            'meta_title'            => 'nullable|string|max:191',
            'meta_description'      => 'nullable|string|max:191',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'nullable|string',
            'video_link'            => 'nullable|string',
            'photos'                => 'required_if:id,0|array|min:1',
            'photos.*'              => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ];

        if ($id == 0) {
            $rules['main_image']  = ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
        } else {
            $rules['main_image']  = ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
        }

        return $rules;
    }

    protected function checkSKU($sku, $id)
    {
        Product::where('sku', $sku)->where('id', '!=', $id)->with('stocks')->orWhereHas('stocks', function ($q) use ($sku, $id) {
            $q->where('sku', $sku)->where('product_id', '!=', $id);
        })->first();
    }


    public function productReviews($sellerId = 0)
    {
        $query = ProductReview::with(['product', 'user']);

        if ($sellerId) {
            $query = $query->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        } else {
            $query = $query->whereHas('product');
        }

        $query = $query->searchable(['review', 'rating', 'user:username', 'product:name']);

        $data['reviews'] = $query->whereHas('user')->latest()->paginate(getPaginate());

        $data['pageTitle']      = "Product Reviews";
        $data['emptyMessage']   = "No review yet";

        return $data;
    }
}
