<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller 
{
    // 1. Admin lấy toàn bộ danh mục (cả active và ẩn)
    public function index() 
    { 
        $categories = Category::orderBy('created_at', 'desc')->get(); 
        return response()->json([ 
            'success' => true, 
            'data' => $categories 
        ], 200); 
    } 

    // 2. Thêm mới danh mục
    public function store(Request $request) 
    { 
        $validated = $request->validate([ 
            'name' => 'required|string|max:100|unique:categories,name', 
            'description' => 'nullable|string', 
            'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'is_active' => 'boolean' 
        ], [ 
            'name.required' => 'Tên danh mục không được để trống.', 
            'name.unique' => 'Tên danh mục này đã tồn tại.', 
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.', 
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh định dạng phải là: jpeg, png, jpg, webp.',
            'image.max' => 'Kích thước ảnh tối đa là 2MB.'
        ]); 

        $imageFilename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageFilename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $imageFilename);
        }

        $category = Category::create([ 
            'name' => $validated['name'], 
            'description' => $validated['description'] ?? null, 
            'image' => $imageFilename, 
            'is_active' => $request->input('is_active', true), 
        ]); 

        return response()->json([ 
            'success' => true, 
            'message' => 'Thêm danh mục hoa thành công!', 
            'data' => $category 
        ], 201); 
    } 

    // 3. Xem chi tiết
    public function show($id) 
    { 
        $category = Category::with('products')->find($id); 
        if (!$category) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy danh mục hoa này!' ], 404); 
        } 
        return response()->json([ 'success' => true, 'data' => $category ], 200); 
    } 

    // 4. Cập nhật danh mục (Hỗ trợ cả POST lẫn PUT khi gửi FormData có ảnh)
    public function update(Request $request, $id) 
    { 
        $category = Category::find($id); 
        if (!$category) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy danh mục hoa cần cập nhật!' ], 404); 
        } 

        $validated = $request->validate([ 
            'name' => 'sometimes|required|string|max:100|unique:categories,name,' . $id, 
            'description' => 'nullable|string', 
            'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'is_active' => 'boolean' 
        ], [ 
            'name.required' => 'Tên danh mục không được để trống.', 
            'name.unique' => 'Tên danh mục này đã tồn tại.', 
        ]); 

        $updateData = [
            'name' => $validated['name'] ?? $category->name,
            'description' => $request->input('description', $category->description),
        ];

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->input('is_active');
        }

        if ($request->hasFile('image')) {
            // Xóa file ảnh cũ nếu có
            if ($category->image && file_exists(public_path('storage/images/' . $category->image))) {
                @unlink(public_path('storage/images/' . $category->image));
            }

            $file = $request->file('image');
            $imageFilename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/images'), $imageFilename);
            $updateData['image'] = $imageFilename;
        }

        $category->update($updateData); 

        return response()->json([ 
            'success' => true, 
            'message' => 'Cập nhật danh mục thành công!', 
            'data' => $category 
        ], 200); 
    } 

    // 5. Xóa danh mục
    public function destroy($id) 
    { 
        $category = Category::find($id); 
        if (!$category) { 
            return response()->json([ 'success' => false, 'message' => 'Không tìm thấy danh mục hoa cần xóa!' ], 404); 
        } 

        if ($category->products()->count() > 0) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Không thể xóa vì danh mục này vẫn chứa sản phẩm!' 
            ], 400); 
        } 

        // Xóa file ảnh vật lý nếu có
        if ($category->image && file_exists(public_path('storage/images/' . $category->image))) {
            @unlink(public_path('storage/images/' . $category->image));
        }

        $category->delete(); 
        return response()->json([ 
            'success' => true, 
            'message' => 'Xóa danh mục thành công!' 
        ], 200); 
    } 
}