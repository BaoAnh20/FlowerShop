<?php 

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller; 
use App\Models\Product; 
use App\Models\ProductImage; // Model ảnh phụ (nếu có bảng product_images)
use Illuminate\Http\Request; 

class ProductController extends Controller 
{ 
    public function index(Request $request) 
    { 
        $query = Product::with(['category', 'images'])->where('is_active', true); 

        // Lọc theo danh mục (Hỗ trợ cả 1 ID hoặc nhiều ID cách nhau bằng dấu phẩy)
        if ($request->has('category_id') && !empty($request->category_id)) {
            $categoryIds = explode(',', $request->category_id);
            if (count($categoryIds) > 1) {
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->where('category_id', trim($request->category_id));
            }
        }

        // Lọc theo khoảng giá (price_ranges)
        if ($request->has('price_ranges') && !empty($request->price_ranges)) {
            $ranges = explode(',', $request->price_ranges);
            $query->where(function($mainQuery) use ($ranges) {
                foreach ($ranges as $range) {
                    switch (trim($range)) {
                        case 'under_500':
                            $mainQuery->orWhere('price', '<', 500000);
                            break;
                        case '500_1000':
                            $mainQuery->orWhereBetween('price', [500000, 1000000]);
                            break;
                        case '1000_1500':
                            $mainQuery->orWhereBetween('price', [1000000, 1500000]);
                            break;
                        case '1500_2000':
                            $mainQuery->orWhereBetween('price', [1500000, 2000000]);
                            break;
                        case '2000_3000':
                            $mainQuery->orWhereBetween('price', [2000000, 3000000]);
                            break;
                        case 'above_3000':
                            $mainQuery->orWhere('price', '>', 3000000);
                            break;
                    }
                }
            });
        }

        // Lọc theo loại hoa chính
        if ($request->has('primary_flower') && !empty($request->primary_flower)) { 
            $query->where('primary_flower', 'like', '%' . $request->primary_flower . '%'); 
        } 

        // Lọc theo dịp tặng
        if ($request->has('occasion') && !empty($request->occasion)) { 
            $query->where('occasion', 'like', '%' . $request->occasion . '%'); 
        } 

        // Tìm kiếm Tên sản phẩm, Chi tiết sản phẩm hoặc Tên danh mục
        if ($request->has('search') && !empty($request->search)) {
            $keyword = trim($request->search);
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'ilike', '%' . $keyword . '%')
                  ->orWhere('details', 'ilike', '%' . $keyword . '%')
                  ->orWhereHas('category', function($subQuery) use ($keyword) {
                      $subQuery->where('name', 'ilike', '%' . $keyword . '%');
                  });
            });
        }

        // Sắp xếp sản phẩm (Sort)
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('id', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12); 

        return response()->json([ 
            'success' => true, 
            'data' => $products 
        ], 200); 
    } 

    public function show($id) 
    { 
        $product = Product::with(['category', 'images'])->find($id);
        if (!$product) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy sản phẩm hoa này!' ], 404); 
        } 
        return response()->json([ 'success' => true, 'data' => $product ], 200); 
    } 

    public function store(Request $request) 
    { 
        $validated = $request->validate([ 
            'category_id' => 'required|exists:categories,id', 
            'name' => 'required|string|max:100', 
            'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validate ảnh phụ
            'details' => 'required|string', 
            'price' => 'required|numeric|min:0', 
            'stock_quantity' => 'required|integer|min:0', 
            'style' => 'nullable|string|max:100', 
            'primary_flower' => 'nullable|string|max:100', 
            'occasion' => 'nullable|string|max:100', 
            'is_active' => 'boolean', 
        ]); 

        // Xử lý lưu ảnh chính (Thumbnail) vào public/storage/images
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $filename);
            $validated['image'] = $filename; // Chỉ lưu tên file vào DB
        }

        $product = Product::create($validated); 

        // Xử lý lưu danh sách ảnh phụ nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $subFile) {
                $subFilename = time() . '_' . uniqid() . '.' . $subFile->getClientOriginalExtension();
                $subFile->move(public_path('storage/images'), $subFilename);
                
                // Lưu vào bảng quan hệ ảnh phụ (nếu hệ thống của Bảo Anh dùng bảng product_images)
                // Hoặc lưu dạng JSON tùy theo cấu trúc DB của Bảo Anh. Dưới đây dùng bảng phụ chuẩn:
                if (class_exists('App\Models\ProductImage')) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $subFilename
                    ]);
                }
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

        // Xử lý cập nhật ảnh chính mới nếu có
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $filename);
            $validated['image'] = $filename;
        }

        // Cập nhật thông tin sản phẩm
        $product->update($validated); 

        // Xử lý bổ sung ảnh phụ mới (nếu có tải lên thêm)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $subFile) {
                $subFilename = time() . '_' . uniqid() . '.' . $subFile->getClientOriginalExtension();
                $subFile->move(public_path('storage/images'), $subFilename);
                
                if (class_exists('App\Models\ProductImage')) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $subFilename
                    ]);
                }
            }
        }

        return response()->json([ 
            'success' => true, 
            'message' => 'Cập nhật sản phẩm thành công!', 
            'data' => $product->load(['category', 'images']) 
        ], 200); 
    } 

    public function destroy($id) 
    { 
        $product = Product::find($id); 
        if (!$product) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy sản phẩm!' ], 404); 
        } 
        $product->delete(); 
        return response()->json([ 'success' => true, 'message' => 'Xóa sản phẩm thành công!' ], 200); 
    } 
}