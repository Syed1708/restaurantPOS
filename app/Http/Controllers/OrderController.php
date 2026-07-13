<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If the user is a Super Admin (who has no single store_id), return all orders
        
        if ($user->hasRole('super-admin') || is_null($user->store_id)) {
            return response()->json(Order::all());
        }

        // If they are a Cashier or Store Manager, strictly filter by their store_id
        return response()->json(
            Order::where('store_id', $user->store_id)->get()
        );
    }
}