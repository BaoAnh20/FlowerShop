<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller 
{
   public function index(Request $request) 
    {
        // Admin lấy toàn bộ sản phẩm (cả đang kinh doanh và ngừng kinh doanh)
        $query = Product::with(['category', 'images']); 

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Bổ sung điều kiện lọc hết hàng (Kho = 0)
        if ($request->has('out_of_stock') && $request->out_of_stock == 1) {
            $query->where('stock_quantity', 0);
        }

        // Hỗ trợ lọc theo 1 hoặc nhiều category_id cách nhau bởi dấu phẩy
        if ($request->has('category_id') && !empty($request->category_id)) {
            $categoryIds = explode(',', $request->category_id);
            if (count($categoryIds) > 1) {
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->where('category_id', trim($request->category_id));
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $keyword = trim($request->search);
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'ilike', '%' . $keyword . '%')
                  ->orWhere('details', 'ilike', '%' . $keyword . '%');
            });
        }

$query->orderBy('created_at', 'desc');
    $products = $query->get(); // <-- lấy toàn bộ danh sách đã lọc

    return response()->json([
        'success' => true,
        'data' => $products
    ], 200);
    }
    public function show($id) 
    { 
        $product = Product::with(['category', 'images'])->find($id);
        if (!$product) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy sản phẩm!' ], 404); 
        } 
        return response()->json([ 'success' => true, 'data' => $product ], 200); 
    } 

    public function store(Request $request) 
    { 
        $validated = $request->validate([ 
            'category_id' => 'required|exists:categories,id', 
            'name' => 'required|string|max:100', 
            'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'details' => 'required|string', 
            'price' => 'required|numeric|min:0', 
            'stock_quantity' => 'required|integer|min:0', 
            'style' => 'nullable|string|max:100', 
            'primary_flower' => 'nullable|string|max:100', 
            'occasion' => 'nullable|string|max:100', 
            'is_active' => 'boolean', 
        ]); 

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $filename);
            $validated['image'] = $filename; 
        }

        $product = Product::create($validated); 

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $subFile) {
                $subFilename = time() . '_' . uniqid() . '.' . $subFile->getClientOriginalExtension();
                $subFile->move(public_path('storage/images'), $subFilename);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $subFilename
                ]);
            }
        }

        return response()->json([ 
            'success' => true, 
            'message' => 'Thêm sản phẩm thành công!', 
            'data' => $product->load(['category', 'images']) 
        ], 201); 
    } 

    public function update(Request $request, $id) 
    { 
        $product = Product::find($id); 
        if (!$product) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy sản phẩm!' ], 404); 
        } 

        $validated = $request->validate([ 
            'category_id' => 'sometimes|exists:categories,id', 
            'name' => 'sometimes|string|max:100', 
            'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'details' => 'sometimes|string', 
            'price' => 'sometimes|numeric|min:0', 
            'stock_quantity' => 'sometimes|integer|min:0', 
            'style' => 'nullable|string|max:100', 
            'primary_flower' => 'nullable|string|max:100', 
            'occasion' => 'nullable|string|max:100', 
            'is_active' => 'boolean', 
        ]); 

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $filename);
            $validated['image'] = $filename;
        }

        $product->update($validated); 

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $subFile) {
                $subFilename = time() . '_' . uniqid() . '.' . $subFile->getClientOriginalExtension();
                $subFile->move(public_path('storage/images'), $subFilename);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $subFilename
                ]);
            }
        }

        return response()->json([ 
            'success' => true, 
            'message' => 'Cập nhật sản phẩm thành công!', 
            'data' => $product->load(['category', 'images']) 
        ], 200); 
    } 

    // public function destroy($id) 
    // { 
    //     $product = Product::find($id); 
    //     if (!$product) { 
    //         return response()->json([ 'success' => false, 'message' => 'Không tìm thấy sản phẩm!' ], 404); 
    //     } 
    //     $product->delete(); 
    //     return response()->json([ 
    //         'success' => true, 
    //         'message' => 'Xóa sản phẩm thành công!' 
    //     ], 200); 
    // } 
public function destroy($id)
{
    $product = Product::with('images')->find($id);
    if (!$product) {
        return response()->json([
            'success' => false, 
            'message' => 'Không tìm thấy sản phẩm!'
        ], 404);
    }

    try {
        // 1. Xóa file ảnh chính vật lý nếu có
        if ($product->image) {
            $mainPath = public_path('storage/images/' . $product->image);
            if (file_exists($mainPath)) { 
                @unlink($mainPath); 
            }
        }

        // 2. Xóa các file ảnh phụ vật lý và bản ghi ảnh phụ (nếu có)
        if ($product->images && $product->images->count() > 0) {
            foreach ($product->images as $subImage) {
                if ($subImage->image_path) {
                    $subPath = public_path('storage/images/' . $subImage->image_path);
                    if (file_exists($subPath)) { 
                        @unlink($subPath); 
                    }
                }
                $subImage->delete();
            }
        }

        // 3. Xóa sản phẩm khỏi giỏ hàng của khách (bảng 'cart' đúng chuẩn CSDL của bạn)
        if (\Schema::hasTable('cart')) {
            \DB::table('cart')->where('product_id', $id)->delete();
        }

        // 4. Tiến hành xóa sản phẩm
        $product->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Xóa sản phẩm thành công!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Lỗi hệ thống khi xóa: ' . $e->getMessage()
        ], 500);
    }
}


    public function destroySubImage($id)
    {
        $subImage = ProductImage::find($id);
        if (!$subImage) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh phụ!'], 404);
        }

        $filePath = public_path('storage/images/' . $subImage->image_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $subImage->delete();

        return response()->json(['success' => true, 'message' => 'Xóa ảnh phụ thành công!'], 200);
    }

   public function setMainImage(Request $request, $id)
{
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm!'], 404);
    }

    $validated = $request->validate([
        'main_image_path' => 'required|string',
    ]);

    $rawPath = $validated['main_image_path'];
    
    // Xử lý bóc tách để chỉ lấy tên file thuần (tránh bị dính thư mục rác như storage/images/ nếu có)
    // Ví dụ: nếu truyền vào /storage/images/abc.jpg hoặc storage/images/abc.jpg -> chỉ lấy abc.jpg
    $filename = basename($rawPath);

    $product->image = $filename;
    $product->save();

    return response()->json([
        'success' => true,
        'message' => 'Đổi ảnh chính thành công!',
        'data' => $product->load(['category', 'images'])
    ], 200);
}
}