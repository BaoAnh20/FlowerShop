<?php namespace App\Http\Controllers\Api\User; 

use App\Http\Controllers\Controller; 
use App\Models\Cart; 
use App\Models\Product; 
use App\Models\Order; 
use App\Models\OrderItem; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str; 

class CartController extends Controller 
{ 
    /** 
     * Lấy định danh giỏ hàng hiện tại và TỰ ĐỘNG GỘP giỏ hàng Guest sang User khi đăng nhập.
     */ 
    private function getCartQuery() 
    { 
        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = session()->get('cart_session_id');

            // NẾU KHÁCH CÓ THÊM SẢN PHẨM KHI CHƯA ĐĂNG NHẬP, SAU ĐÓ ĐĂNG NHẬP:
            // Tự động gộp các item từ session_id sang user_id của tài khoản mới đăng nhập
            if ($sessionId) {
                $guestCarts = Cart::where('session_id', $sessionId)->get();
                foreach ($guestCarts as $guestCart) {
                    $userCart = Cart::where('user_id', $userId)
                                    ->where('product_id', $guestCart->product_id)
                                    ->first();

                    if ($userCart) {
                        // Nếu sản phẩm đã có trong giỏ của user -> cộng dồn số lượng
                        $userCart->update(['quantity' => $userCart->quantity + $guestCart->quantity]);
                        $guestCart->delete(); // Xóa bản ghi rác của guest
                    } else {
                        // Nếu chưa có -> chuyển sở hữu sang user_id và xóa session_id
                        $guestCart->update([
                            'user_id' => $userId,
                            'session_id' => null
                        ]);
                    }
                }
                // Xóa session_id sau khi đã gộp xong
                session()->forget('cart_session_id');
            }

            return Cart::where('user_id', $userId);
        } else { 
            $sessionId = session()->get('cart_session_id'); 
            if (!$sessionId) { 
                $sessionId = Str::uuid()->toString(); 
                session()->put('cart_session_id', $sessionId); 
            } 
            return Cart::where('session_id', $sessionId); 
        } 
    } 

    /** 
     * 1. Hiển thị trang giỏ hàng và form thanh toán. 
     */ 
    public function index() 
    { 
        $cartItems = $this->getCartQuery()->with('product')->get(); 
        return view('cart.index', compact('cartItems')); 
    } 

    /** 
     * Thêm sản phẩm vào giỏ hàng (Hỗ trợ gọi từ trang chi tiết / danh sách sản phẩm). 
     */ 
    public function store(Request $request, $id = null) 
    { 
        $productId = $id ?? $request->input('product_id'); 
        $quantity = (int) $request->input('quantity', 1); 
        $product = Product::findOrFail($productId); 

        // Kiểm tra tồn kho 
        if ($product->stock_quantity < $quantity) { 
            return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ!'); 
        } 

        $userId = Auth::check() ? Auth::id() : null; 
        $sessionId = Auth::check() ? null : session()->get('cart_session_id'); 

        if (!$userId && !$sessionId) { 
            $sessionId = Str::uuid()->toString(); 
            session()->put('cart_session_id', $sessionId); 
        } 

        // Tìm xem sản phẩm đã có trong giỏ hàng của user/session này chưa 
        $cartQuery = Cart::where('product_id', $productId); 
        if ($userId) { 
            $cartQuery->where('user_id', $userId); 
        } else { 
            $cartQuery->where('session_id', $sessionId); 
        } 
        
        $cartItem = $cartQuery->first(); 

        if ($cartItem) { 
            $newQuantity = $cartItem->quantity + $quantity; 
            if ($product->stock_quantity < $newQuantity) { 
                return redirect()->back()->with('error', 'Số lượng vượt quá sản phẩm có sẵn trong kho!'); 
            } 
            $cartItem->update(['quantity' => $newQuantity]); 
        } else { 
            Cart::create([ 
                'user_id' => $userId, 
                'session_id' => $sessionId, 
                'product_id' => $productId, 
                'quantity' => $quantity, 
            ]); 
        } 

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!'); 
    } 

    /** 
     * 2. Cập nhật số lượng sản phẩm trong giỏ hàng. 
     */ 
    public function update(Request $request, $id) 
    { 
        $request->validate([ 'quantity' => 'required|integer|min:1|max:99' ]); 
        $cartItem = Cart::findOrFail($id); 

        if (Auth::check()) { 
            if ($cartItem->user_id !== Auth::id()) { abort(403); } 
        } else { 
            if ($cartItem->session_id !== session()->get('cart_session_id')) { abort(403); } 
        } 

        if ($cartItem->product->stock_quantity < $request->quantity) { 
            return redirect()->back()->with('error', 'Số lượng trong kho không đủ đáp ứng.'); 
        } 

        $cartItem->update(['quantity' => $request->quantity]); 
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng giỏ hàng!'); 
    } 

    /** 
     * 3. Xóa một sản phẩm khỏi giỏ hàng. 
     */ 
    public function destroy($id) 
    { 
        $cartItem = Cart::findOrFail($id); 

        if (Auth::check()) { 
            if ($cartItem->user_id !== Auth::id()) { abort(403); } 
        } else { 
            if ($cartItem->session_id !== session()->get('cart_session_id')) { abort(403); } 
        } 

        $cartItem->delete(); 
        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.'); 
    } 

    /** 
     * 4. Xử lý đặt hàng và lưu vào bảng orders, order_items từ thông tin giỏ hàng. 
     */ 
    public function storeOrder(Request $request) 
    { 
        $request->validate([ 
            'recipient_name'    => 'required|string|max:100', 
            'recipient_phone'   => 'required|string|max:15', 
            'shipping_address'  => 'required|string|max:500', 
            'delivery_time'     => 'required|date|after_or_equal:now', 
            'card_message'      => 'nullable|string|max:500', 
        ]); 

        $cartItems = $this->getCartQuery()->with('product')->get(); 
        if ($cartItems->isEmpty()) { 
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!'); 
        } 

        $totalPrice = 0; 
        foreach ($cartItems as $item) { 
            $totalPrice += $item->product->price * $item->quantity; 
        } 

        try { 
            DB::beginTransaction(); 

            $orderCode = 'BA-' . date('Ymd') . '-' . strtoupper(Str::random(5)); 
            $order = Order::create([ 
                'order_code'     => $orderCode, 
                'user_id'        => Auth::check() ? Auth::id() : null, 
                'name'           => $request->recipient_name, 
                'phone'          => $request->recipient_phone, 
                'address'        => $request->shipping_address, 
                'note'           => $request->card_message . ($request->has('is_anonymous') ? ' [GIAO HÀNG ẨN DANH]' : ''), 
                'delivery_time'  => $request->delivery_time, 
                'delivery_type'  => 'scheduled', 
                'payment_status' => 'unpaid', 
                'total_price'    => $totalPrice, 
                'order_status'   => 'pending', 
            ]); 

            foreach ($cartItems as $item) { 
                $subtotal = $item->product->price * $item->quantity; 
                OrderItem::create([ 
                    'order_id'     => $order->id, 
                    'product_id'   => $item->product_id, 
                    'product_name' => $item->product->name, 
                    'image'        => $item->product->image ?? $item->product->image_path, 
                    'price'        => $item->product->price, 
                    'quantity'     => $item->quantity, 
                    'subtotal'     => $subtotal, 
                ]); 

                $item->product->decrement('stock_quantity', $item->quantity); 
            } 

            // Xóa sạch giỏ hàng sau khi đặt thành công 
            $this->getCartQuery()->delete(); 

            DB::commit(); 

            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là: ' . $orderCode); 
        } catch (\Exception $e) { 
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình đặt hàng. Vui lòng thử lại!')->withInput(); 
        } 
    } 
}