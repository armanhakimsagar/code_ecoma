<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\AssignProductAttribute;
use App\Models\Product;
use App\Models\ProductReview;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ProductManager;
use App\Traits\ProductVariantManager;

class ProductController extends Controller
{
    use ProductManager, ProductVariantManager;

    public function index()
    {
        return view('admin.products.index', $this->products());
    }
    public function pending()
    {
        return view('admin.products.index', $this->pendingProducts());
    }

    public function adminProducts()
    {
        return view('admin.products.index', $this->productByVendor());
    }
    public function sellerProducts()
    {
        return view('admin.products.index', $this->productByVendor(false));
    }

    public function trashed()
    {
        return view('admin.products.index', $this->products(0, true));
    }

    public function create()
    {
        return view('admin.products.create', $this->productCreate());
    }

    public function edit($id)
    {
        return view('admin.products.create', $this->editProduct($id));
    }

    public function store(Request $request, $id)
    {
        return back()->withNotify(
            $this->storeProduct($request, $id)
        );
    }

    public function delete($id)
    {
        return back()->withNotify(
            $this->deleteProduct($id)
        );
    }

    public function restore($id)
    {
        return back()->withNotify(
            $this->restoreProduct($id)
        );
    }

    public function statusAction($id)
    {
        $product = Product::findOrFail($id);

        if ($product->status == Status::ENABLE) {
            $product->status = Status::DISABLE;
            $message = 'Product has been disabled';
        } else {
            $product->status = Status::ENABLE;
            $message = 'Product has been approved';
        }
        $product->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function approveAll()
    {
        Product::pending()->update(['status' => 1]);
        $notify[] = ['success', 'All pending product has been approved'];
        return back()->withNotify($notify);
    }

    public function addVariant($product_id)
    {

        return view('admin.products.variant.create', $this->addProductVariant($product_id));
    }

    public function storeVariant(Request $request, $id)
    {
        return back()->withNotify(
            $this->storeProductVariant($request, $id)
        );
    }

    public function updateVariant(Request $request, $id)
    {
        $productAttribute = AssignProductAttribute::findOrFail($id);
        if ($productAttribute->productAttribute->type == 1 || $productAttribute->productAttribute->type == 2) {
            $request->validate([
                'name'  => 'required',
                'value' => 'required',
                'price' => 'required',
            ]);
        } elseif ($productAttribute->productAttribute->type == 3) {

            $request->validate([
                'name'    => 'required',
                'image'   => ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
                'price'   => 'required'
            ]);

            $oldImage = (isset($productAttribute->value)) ? $productAttribute->value : '';

            if ($request->hasFile('image')) {
                try {
                    $request->merge(['value' => fileUploader($request->image, getFilePath('attribute'), getFileSize('attribute'), $oldImage)]);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload the image.'];
                    return back()->withNotify($notify);
                }
            }
        }
        $productAttribute->name   = $request->name;
        $productAttribute->value  = $request->value ?? '';
        $productAttribute->extra_price  = $request->price;
        $productAttribute->save();
        $notify[] = ['success', 'Product variant updated successfully'];
        return back()->withNotify($notify);
    }


    public function featured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        $message = $product->is_featured ? "Product marked as featured" : "Product removed from featured";

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function deleteVariant($id)
    {
        return back()->withNotify(
            $this->deleteProductVariant($id)
        );
    }

    public function reviews()
    {
        return view('admin.products.reviews', $this->productReviews());
    }

    public function trashedReviews()
    {
        $pageTitle      = "All Product Reviews";
        $reviews = ProductReview::onlyTrashed()->with(['product', 'user'])
            ->whereHas('product')->whereHas('user')
            ->latest()
            ->paginate(getPaginate());
        return view('admin.products.reviews', compact('pageTitle', 'reviews'));
    }

    public function changeReviewStatus($id)
    {
        $review = ProductReview::where('id', $id)->withTrashed()->first();
        if ($review->trashed()) {
            $review->restore();
            $notify[] = ['success', 'Review Restored Successfully'];
            return back()->withNotify($notify);
        } else {
            $review->delete();
            $notify[] = ['success', 'Review Deleted Successfully'];
            return back()->withNotify($notify);
        }
    }

    public function addVariantImages($id)
    {
        return view('admin.products.variant.image', $this->addProductVariantImages($id));
    }

    public function saveVariantImages(Request $request, $id)
    {
        $storeImages = $this->saveProductVariantImages($request, $id);

        return back()->withNotify($storeImages);
    }

    public function checkSlug(Request $request)
    {
        $slugExists = Product::where('slug', $request->slug)->where('id', '!=', $request->id)->exists();

        return response()->json([
            'status' => !$slugExists,
            'message' => $slugExists ? 'Slug already exists' : 'Slug is available'
        ]);
    }
}
