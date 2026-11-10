<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Admin xem danh sách toàn bộ đơn hàng.
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items')->orderBy('created_at', 'desc');

        // Lọc theo trạng thái đơn hàng nếu có (pending, processing, completed, cancelled)
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }

    /**
     * Xem chi tiết một đơn hàng cụ thể.
     */
    public function show($id)
    {
        $order = Order::with('items.product', 'user', 'payments')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ], 200);
    }

    /**
     * Cập nhật trạng thái đơn hàng hoặc trạng thái thanh toán.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        $request->validate([
            'order_status'   => 'nullable|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid,failed',
        ]);

        if ($request->has('order_status')) {
            $order->order_status = $request->order_status;
        }

        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái đơn hàng thành công!',
            'data' => $order
        ], 200);
    }
}