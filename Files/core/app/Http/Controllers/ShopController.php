<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\AssignProductAttribute;
use App\Models\ProductStock;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    private $minPrice;
    private $maxPrice;
    private $brands;

    public function categories()
    {
        $pageTitle = 'Categories';
        $categories = Category::latest()->paginate(20);

        return view('Template::categories', compact('pageTitle', 'categories'));
    }

    public function brands()
    {
        $data['brands']         = Brand::latest()->paginate(30);
        $data['pageTitle']     = 'Brands';
        $data['emptyMessage']  = 'No Brand Found';

        return view('Template::brands', $data);
    }

    public function products(Request $request)
    {
        $pageTitle    = 'Products';
        $brands       = Brand::latest()->get();
        $categories   = Category::where('parent_id', null)->latest()->get();


        $brand        = $request->brand ? $request->brand : ['0'];
        $category_id  = $request->category_id ?? 0;
        $min          = $request->min;
        $max          = $request->max;

        $products =  $this->getProducts($request);

        $perpage = $request->perpage ?? 15;
        $min_price = $this->minPrice;
        $max_price = $this->maxPrice;

        if ($request->ajax()) {
            $view = 'partials.products_filter';
        } else {
            $view = 'products';
        }

        $emptyMessage = "Sorry! No Product Found.";
        return view('Template::' . $view, compact('products', 'perpage', 'brand', 'min_price', 'max_price', 'pageTitle', 'brands', 'min', 'max', 'category_id', 'emptyMessage'));
    }

    public function productsFilter(Request $request)
    {
        $products = $this->getProducts($request);
        return view('Template::partials.products_filter', compact('products'));
    }

    public function productSearch(Request $request)
    {
        $pageTitle     = 'Product Search';
        $emptyMessage  = 'No product found';
        $searchKey     = $request->search_key;
        $category_id   = $request->category_id;
        $perpage       = 15;

        $products = $this->getProducts($request);

        if ($request->ajax()) {
            $view = 'partials.products_search_filter';
        } else {
            $view = 'products_search';
        }

        return view('Template::' . $view, compact('pageTitle', 'products', 'emptyMessage', 'searchKey', 'category_id', 'perpage'));
    }


    public function productsByCategory(Request $request, $id)
    {
        $category   = Category::findOrFail($id);
        $pageTitle  = 'Products by Category - ' . $category->name;
        $categories = Category::where('parent_id', null)->latest()->get();
        $brand      = $request->brand ? $request->brand : ['0'];
        $min        = $request->min;
        $max        = $request->max;
        $perpage    = $request->perpage ?? 15;

        $request->merge(['category_id' => $category->id]);

        $products = $this->getProducts($request);
        $min_price = $this->minPrice;
        $max_price = $this->maxPrice;
        $brands = $this->brands;

        $emptyMessage       = "Sorry! No Product Found";
        $imageData['path']  = getFilePath('category');
        $imageData['size']  = getFileSize('category');
        $seoContents        = getSeoContents($category, $imageData, 'image');
        $view               = 'products_by_category';

        if ($request->ajax()) {
            $view = 'partials.products_filter';
        }

        return view('Template::' . $view, compact('products', 'perpage', 'brand', 'min_price', 'max_price', 'pageTitle', 'emptyMessage', 'min', 'max', 'category', 'brands', 'seoContents'));
    }


    public function productsByBrand(Request $request, $id)
    {
        $brand                  = Brand::whereId($id)->firstOrFail();
        $pageTitle              = 'Products by Brand - ' . $brand->name;
        $categories             = Category::where('parent_id', null)->latest()->get();
        $category_id            = $request->category_id ?? 0;
        $min                    = $request->min;
        $max                    = $request->max;
        $perpage                = $request->perpage ?? 15;

        $request->merge(['brand_id' => $brand->id]);
        $products  =  $this->getProducts($request);
        $min_price = $this->minPrice;
        $max_price = $this->maxPrice;

        $view = 'products_by_brand';

        if ($request->ajax()) {
            $view = 'partials.products_filter';
        }

        $emptyMessage   = "Sorry! No Product Found.";

        $imageData['path'] = getFilePath('brand');
        $imageData['size'] = getFileSize('brand');
        $seoContents = getSeoContents($brand, $imageData, 'logo');

        return view('Template::' . $view, compact('products', 'categories', 'perpage', 'brand', 'min_price', 'max_price', 'pageTitle', 'emptyMessage', 'min', 'max', 'category_id', 'seoContents'));
    }



    private function getProducts($request)
    {
        $products = Product::publishable()->with(['categories', 'offer', 'offer.activeOffer', 'reviews', 'brand']);

        if ($request->category_id) {
            $products->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }

        if ($request->brand_id) {
            $products->where('brand_id', $request->brand_id);
        }

        if ($request->brand && is_array($request->brand)) {
            $brands = array_filter($request->brand);
            if (count($brands) > 0) {
                $products->whereIn('brand_id', $brands);
            }
        }

        if ($request->min) {
            $products->where('base_price', '>=', $request->min);
        }

        if ($request->max) {
            $products->where('base_price', '<=', $request->max);
        }

        if ($request->search_key) {
            $products->where(function ($query) use($request) {
                $query->where('name', 'like', "%{$request->search_key}%")
                    ->orWhere('summary', 'like', "%{$request->search_key}%")
                    ->orWhere('description', 'like', "%{$request->search_key}%")
                    ->orWhereHas('brand', function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->search_key}%");
                    })
                    ->orWhereHas('categories', function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->search_key}%");
                    });
            });
        }

        $this->minPrice = $products->min('base_price') ?? 0;
        $this->maxPrice = $products->max('base_price') ?? 0;

        $this->getBrands((clone $products)->pluck('brand_id')->toArray());

        $perPage = $request->perpage ?? 15;
        return $products->paginate($perPage);
    }

    private function getBrands($ids)
    {
        $this->brands = Brand::whereIn('id', $ids)->orderBy('name')->get();
        return $this->brands;
    }

    public function quickView(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $id  = $request->id;

        $product = Product::publishable()->where('id', $id)
            ->with('categories', 'offer', 'offer.activeOffer', 'reviews', 'productImages', 'brand')
            ->firstOrFail();

        if (optional($product->offer)->activeOffer) {
            $discount = calculateDiscount($product->offer->activeOffer->amount, $product->offer->activeOffer->discount_type, $product->base_price);
        } else $discount = 0;


        $rProducts = $product->categories()
            ->with('products', 'products.offer')
            ->get()
            ->map(function ($item) use ($id) {
                return $item->products->where('id', '!=', $id)->take(5);
            });

        $attributes     = AssignProductAttribute::where('status', 1)->where('product_id', $id)->distinct('product_attribute_id')->with('productAttribute', 'product')->get(['product_attribute_id']);

        $pageTitle = 'Product Details';
        return view('Template::partials.quick_view', compact('product', 'pageTitle', 'discount', 'attributes'));
    }

    public function productDetails($slug)
    {
        $product = Product::publishable()
            ->where('slug', $slug)
            ->with('categories', 'assignAttributes', 'offer', 'offer.activeOffer', 'reviews', 'productPreviewImages', 'stocks', 'productVariantImages')
            ->firstOrFail();


        $images = $product->productPreviewImages;

        if ($images->count() == 0) {
            $images = $product->productVariantImages;
        }

        if (optional($product->offer)->activeOffer) {
            $discount = calculateDiscount($product->offer->activeOffer->amount, $product->offer->activeOffer->discount_type, $product->base_price);
        } else $discount = 0;

        $rProducts = $product->categories()->with(
            [
                'products' => function ($q) {
                    return $q->whereHas('categories')->whereHas('brand');
                },
                'products.reviews',
                'products.offer',
                'products.offer.activeOffer'
            ]
        )
            ->get()->map(function ($item) use ($product) {
                return $item->products->where('id', '!=', $product->id)->take(5);
            });

        $relatedProducts = [];

        foreach ($rProducts as $childArray) {
            foreach ($childArray as $value) {
                $relatedProducts[] = $value;
            }
        }

        $attributes     = AssignProductAttribute::where('status', 1)->with('productAttribute')->where('product_id', $product->id)->distinct('product_attribute_id')->get(['product_attribute_id']);

        $imageData['path']      = getFilePath('product');
        $imageData['size']      = getFileSize('product');
        $seoContents    = getSeoContents($product, $imageData, 'main_image');

        $pageTitle = 'Product Details';
        return view('Template::product_details', compact('product', 'pageTitle', 'relatedProducts', 'discount', 'attributes', 'images', 'seoContents'));
    }

    public function addToCompare(Request $request)
    {
        $id         = $request->product_id;
        $product    = Product::where('id', $id)->with('categories')->first();
        $compare    = session()->get('compare');

        if ($compare) {
            $resetCompare  = reset($compare);
            $previousProducts   = Product::where('id', $resetCompare['id'])->with('categories')->first();

            $notSame       = empty(array_intersect($product->categories->pluck('id')->toArray(), $previousProducts->categories->pluck('id')->toArray()));

            if ($notSame) {
                return response()->json(['error' => 'A different type of product is already on your comparison list']);
            }
            if (count($compare) > 2) {
                return response()->json(['error' => 'You can\'t add more than 3 product in comparison list']);
            }
        }

        if (!$compare) {

            $compare = [
                $id => [
                    "id" => $product->id
                ]
            ];
            session()->put('compare', $compare);
            return response()->json(['success' => 'Added to comparison list']);
        }

        // if compare list is not empty then check if this product exist
        if (isset($compare[$id])) {
            return response()->json(['error' => 'Already in the comparison list']);
        }
        $compare[$id] = [
            "id" => $product->id
        ];

        session()->put('compare', $compare);
        return response()->json(['success' => 'Added to comparison list']);
    }

    public function compare()
    {
        $data       = session()->get('compare');

        $products   = [];

        if ($data) {
            foreach ($data as $key => $val) {
                array_push($products, $key);
            }
        }

        $compare_data   = Product::with('categories', 'offer', 'offer.activeOffer', 'reviews')->whereIn('id', $products)->get();

        $compare_items = $compare_data->take(4);

        $pageTitle = 'Product Comparison';
        $emptyMessage = 'Comparison list is empty';
        return view('Template::compare', compact('pageTitle', 'compare_items', 'emptyMessage'));
    }

    public function getCompare()
    {
        $data       = session()->get('compare');
        if (!$data) {
            return response(['total' => 0]);
        }

        $products   = [];

        foreach ($data as $key => $val) {
            $products[] = $key;
        }

        $compare_data   = Product::with('categories', 'offer', 'offer.activeOffer', 'reviews')
            ->where('status', 1)
            ->whereIn('id', $products)->get();
        return response(['total' => count($compare_data)]);
    }

    public function getVariantStock(Request $request)
    {
        $pid    = $request->product_id;
        $attr   = json_decode($request->attr_id);
        sort($attr);
        $attr = json_encode($attr);

        $stock  = ProductStock::showAvailableStock($pid, $attr);

        return response()->json(['sku' => $stock->sku ?? 'Not Available', 'quantity' => $stock->quantity ?? 0]);
    }

    public function getVariantImage(Request $request)
    {
        $variant = AssignProductAttribute::whereId($request->id)->with('productImages')->first();

        if (!$variant) {
            return response()->json(['error' => true]);
        }

        $images  = $variant->productImages;
        if ($images->count() > 0) {
            return view('Template::partials.variant_images', compact('images'));
        } else {
            return response()->json(['error' => true]);
        }
    }

    public function removeFromCompare($id)
    {
        $compare = session()->get('compare');

        if (isset($compare[$id])) {
            unset($compare[$id]);
            session()->put('compare', $compare);
            $notify[] = ['success', 'Deleted from compare list'];
            return response()->json(['message' => 'Removed from compare list']);
        }

        return response()->json(['error' => 'Something went wrong']);
    }

    public function loadMoreReviews(Request $request)
    {
        $reviews = ProductReview::where('product_id', $request->pid)->orderBy('id', 'desc')->paginate(5);
        return view('Template::partials.product_review', compact('reviews'));
    }

    public function allSellers()
    {
        $pageTitle  = "Our sellers";
        $sellers    = Seller::active()->whereHas('shop')->with('shop')->paginate(getPaginate());
        return view('Template::all_sellers', compact('pageTitle', 'sellers'));
    }

    public function sellerDetails($id, $slug)
    {
        $pageTitle      = "Seller Details";
        $seller         = Seller::active()->where('id', $id)->whereHas('shop')->with('shop')->firstOrFail();
        $imageData['path']      = getFilePath('sellerShopCover');
        $imageData['size']      = getFileSize('sellerShopCover');
        $seoContents    = getSeoContents($seller->shop, $imageData, 'cover');
        $products       = Product::active()->where('seller_id', $seller->id)->latest()->paginate(getPaginate(20));
        return view('Template::seller_details', compact('pageTitle', 'seller', 'products', 'seoContents'));
    }
}
