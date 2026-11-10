<?php 

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller; 
use App\Models\Product; 
use App\Models\ProductImage; 
use Illuminate\Http\Request; 

class ProductController extends Controller 
{ 
    public function index(Request $request) 
    { 
        try {
            $query = Product::with(['category', 'images'])->where('is_active', true); 

            // 1. Lọc theo danh mục (Hỗ trợ 1 ID hoặc nhiều ID cách nhau bằng dấu phẩy)
            if ($request->has('category_id') && !empty($request->category_id)) {
                $categoryIds = array_filter(explode(',', $request->category_id), function($id) {
                    return is_numeric(trim($id));
                });
                
                if (!empty($categoryIds)) {
                    if (count($categoryIds) > 1) {
                        $query->whereIn('category_id', $categoryIds);
                    } else {
                        $query->where('category_id', trim($categoryIds[0]));
                    }
                }
            }

            // 2. Lọc theo khoảng giá (price_ranges) tương thích kiểu NUMERIC của PostgreSQL
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

            // 3. Lọc theo loại hoa chính (Hỗ trợ ilike không phân biệt hoa thường trên PostgreSQL)
            if ($request->has('primary_flower') && !empty($request->primary_flower)) { 
                $query->where('primary_flower', 'ilike', '%' . trim($request->primary_flower) . '%'); 
            } 

            // 4. Lọc theo dịp tặng
            if ($request->has('occasion') && !empty($request->occasion)) { 
                $query->where('occasion', 'ilike', '%' . trim($request->occasion) . '%'); 
            } 

            // 5. Tìm kiếm chuẩn xác (Gom nhóm điều kiện AND/OR tránh lỗi quét tràn lan sang bản ghi khác)
            if ($request->has('search') && !empty($request->search)) {
                $keyword = trim($request->search);
                $query->where(function($q) use ($keyword) {
                    // Gom nhóm điều kiện tìm kiếm trên bảng products (Tên hoặc Chi tiết)
                    $q->where(function($sub) use ($keyword) {
                        $sub->where('name', 'ilike', '%' . $keyword . '%')
                            ->orWhere('details', 'ilike', '%' . $keyword . '%');
                    })
                    // Hoặc tên danh mục liên quan khớp với từ khóa
                    ->orWhereHas('category', function($subQuery) use ($keyword) {
                        $subQuery->where('name', 'ilike', '%' . $keyword . '%');
                    });
                });
            }

            // 6. Sắp xếp sản phẩm (Sort)
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi lọc sản phẩm: ' . $e->getMessage()
            ], 500);
        }
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