<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
   
    private function getIdentifier(Request $request)
    {
        //  kiểm tra Token Sanctum ngay cả khi route không bọc middleware
        $user = Auth::guard('sanctum')->user();
        
        if ($user) {
            return ['type' => 'user', 'id' => $user->id];
        }

        // Nếu không có Token -> Lấy session_id
        $sessionId = $request->header('X-Session-ID') ?? $request->input('session_id');
        
        return ['type' => 'session', 'id' => $sessionId];
    }


    private function getCartQuery(Request $request)
    {
        $query = Cart::with('product');
        $identifier = $this->getIdentifier($request);

        if ($identifier['type'] === 'user') {
            $query->where('user_id', $identifier['id']);
        } else {
            $sessionId = $identifier['id'];
            if ($sessionId) {
                $query->where('session_id', $sessionId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    // 1. Xem danh sách giỏ hàng 
    public function index(Request $request)
    {
        $cartItems = $this->getCartQuery($request)->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->price ?? 0);
        });

        return response()->json([
            'success' => true,
            'data' => $cartItems,
            'total_price' => $totalPrice
        ], 200);
    }

    // 2. Thêm sản phẩm vào giỏ hàng 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'session_id' => 'nullable|string|max:100'
        ], [
            'product_id.required' => 'Mã sản phẩm không được để trống.',
            'product_id.exists' => 'Sản phẩm hoa này không tồn tại.',
            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng thêm vào phải lớn hơn hoặc bằng 1.'
        ]);

        $identifier = $this->getIdentifier($request);
        $productId = $validated['product_id'];
        $addQuantity = $validated['quantity'];

        $product = Product::find($productId);

        $query = Cart::where('product_id', $productId);
        if ($identifier['type'] === 'user') {
            $query->where('user_id', $identifier['id']);
        } else {
            $sessionId = $identifier['id'];
            if (!$sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu thông tin định danh giỏ hàng (session_id hoặc token).'
                ], 422);
            }
            $query->where('session_id', $sessionId);
        }

        $cartItem = $query->first();
        $currentQtyInCart = $cartItem ? $cartItem->quantity : 0;
        $totalRequestedQty = $currentQtyInCart + $addQuantity;

        // Kiểm tra tồn 
        if ($totalRequestedQty > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong giỏ vượt quá số lượng tồn kho cho phép (' . $product->stock_quantity . ').'
            ], 400);
        }

        if ($cartItem) {
            // Đã có -> Cộng dồn số lượng
            $cartItem->quantity = $totalRequestedQty;
            $cartItem->save();
        } else {
            // Chưa có -> Tạo mới 
            $cartItem = Cart::create([
                'user_id' => $identifier['type'] === 'user' ? $identifier['id'] : null,
                'session_id' => $identifier['type'] === 'session' ? $identifier['id'] : null,
                'product_id' => $productId,
                'quantity' => $addQuantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'data' => $cartItem->load('product')
        ], 201);
    }

    // Cập nhật sl sp
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ], [
            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng cập nhật tối thiểu là 1.'
        ]);

        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm trong giỏ hàng không tồn tại!'
            ], 404);
        }

        // Kiểm tra quyền sở hữu của User nào
        $identifier = $this->getIdentifier($request);
        if ($identifier['type'] === 'user') {
            if ($cartItem->user_id !== $identifier['id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền chỉnh sửa giỏ hàng này!'
                ], 403);
            }
        } else {
            $sessionId = $identifier['id'];
            if ($cartItem->session_id !== $sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền chỉnh sửa giỏ hàng này!'
                ], 403);
            }
        }

        // Kiểm tra tồn kho
        $product = Product::find($cartItem->product_id);
        if ($validated['quantity'] > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng yêu cầu vượt quá tồn kho hiện tại (' . $product->stock_quantity . ').'
            ], 400);
        }

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật giỏ hàng thành công!',
            'data' => $cartItem->load('product')
        ], 200);
    }

//xoa
    public function destroy(Request $request, $id)
    {
        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm trong giỏ hàng không tồn tại!'
            ], 404);
        }

        // Kiểm tra quyền sở hữu trước khi xóa
        $identifier = $this->getIdentifier($request);
        if ($identifier['type'] === 'user') {
            if ($cartItem->user_id !== $identifier['id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa sản phẩm này khỏi giỏ hàng!'
                ], 403);
            }
        } else {
            $sessionId = $identifier['id'];
            if ($cartItem->session_id !== $sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xóa sản phẩm này khỏi giỏ hàng!'
                ], 403);
            }
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng thành công!'
        ], 200);
    }
}