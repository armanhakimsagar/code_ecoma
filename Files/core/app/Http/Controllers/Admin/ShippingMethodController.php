<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Shipping Methods';
        $shippingMethods  = ShippingMethod::orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.shipping_method.index', compact('pageTitle', 'shippingMethods'));
    }

    public function create()
    {
        $pageTitle = 'Create New Shipping Method';
        return view('admin.shipping_method.create', compact('pageTitle'));
    }

    public function edit($id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);
        $pageTitle = 'Edit Shipping Method';

        return view('admin.shipping_method.create', compact('pageTitle', 'shippingMethod'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'   => 'required|string',
            'charge' => 'required|numeric|gte:0',
            'shipping_time'    => 'required|integer|gt:0',
            'description'   => 'nullable|string',
        ]);

        if ($id == 0) {
            $method = new ShippingMethod();
            $message = 'Shipping method added successfully';
        } else {
            $method = ShippingMethod::findOrFail($id);
            $message = 'Shipping method updated successfully';
        }

        $method->name = $request->name;
        $method->charge = $request->charge;
        $method->shipping_time= $request->shipping_time;
        $method->description  = $request->description;
        $method->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return ShippingMethod::changeStatus($id);
    }
}
