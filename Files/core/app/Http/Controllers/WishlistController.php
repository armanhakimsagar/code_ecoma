<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function addToWishList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $userid = auth()->id() ?? null;

        $sessionId = session()->get('session_id');

        if ($sessionId == null) {
            $sessionId = uniqid();
            session()->put('session_id', $sessionId);
        }

        $wishlist = Wishlist::where(function ($query) use ($userid, $sessionId) {
            $query->where('user_id', $userid)
                ->orWhere('session_id', $sessionId);
        })->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Already in the wish list'
            ]);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id    = $userid;
        $wishlist->session_id = $sessionId;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();

        $sessionWishlist = session()->get('wishlist');
        $sessionWishlist[$request->product_id] = ["id" => $request->product_id];
        session()->put('wishlist', $sessionWishlist);

        return response()->json([
            'status' => true,
            'message' => 'Added to Wishlist'
        ]);
    }

    public function getWishList()
    {
        $userid    = auth()->id() ?? null;
        $sessionId = session()->get('session_id');

        $wishlist = Wishlist::where(function ($query) use ($userid, $sessionId) {
            $query->where('user_id', $userid)->orWhere('session_id', $sessionId);
        })->whereHas('product', function ($q) {
            return $q->whereHas('categories')->whereHas('brand');
        })->with(['product', 'product.stocks', 'product.categories', 'product.offer'])->orderBy('id', 'desc')->get();

        if ($wishlist->count() > 3) {
            $latest = $wishlist->sortByDesc('id')->take(5);
        } else {
            $latest = $wishlist;
        }

        $more = $wishlist->count() - count($latest);

        return view('Template::partials.wishlist_items', ['data' => $latest, 'more' => $more]);
    }

    public function getWishListTotal()
    {
        $userid    = auth()->id() ?? null;

        $totalWishlist = Wishlist::where(function ($query) use ($userid) {
            $query->where('user_id', $userid)
                ->orWhere('session_id', session()->get('session_id'));
        })->whereHas('product', function ($q) {
            return $q->whereHas('categories')->whereHas('brand');
        })->count();


        return response($totalWishlist);
    }

    public function wishList()
    {

        $userid    = auth()->id() ?? null;
        $notify[] = [];

        if ($userid != null) {
            $wishlist_data = Wishlist::where('user_id', $userid)
                ->with(['product', 'product.stocks', 'product.categories', 'product.offer'])
                ->whereHas('product', function ($q) {
                    return $q->whereHas('categories')->whereHas('brand');
                })
                ->get();
        } else {
            $sessionId       = session()->get('session_id');
            if (!$sessionId) {
                return redirect(route('home'))->withNotify($notify);
            }
            $wishlist_data = Wishlist::where('session_id', $sessionId)
                ->with(['product', 'product.stocks', 'product.categories', 'product.offer'])
                ->whereHas('product', function ($q) {
                    return $q->whereHas('categories')->whereHas('brand');
                })
                ->get();
        }

        $pageTitle     = 'Wishlist';
        $emptyMessage  = 'No product in your wishlist';
        return view(activeTemplate() . 'wishlist', compact('pageTitle', 'wishlist_data', 'emptyMessage'));
    }

    public function removeFromWishList($id = 0)
    {
        if ($id) {
            $wishlist = Wishlist::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('session_id', session()->get('session_id'));
            })->find($id);

            if (!$wishlist) {
                return response()->json([
                    'status' => false,
                    'message' => 'This product isn\'t available in your wishlist'
                ]);
            }

            $sessionWishlist = session()->get('wishlist');
            unset($sessionWishlist[$wishlist->product_id]);
            session()->put('wishlist', $sessionWishlist);
            $wishlist->delete();

            $message = 'The product has been removed successfully';
        } else {
            Wishlist::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('session_id', session()->get('session_id'));
            })->delete();

            session()->forget('wishlist');
            $message = 'Wishlist cleared successfully';
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
