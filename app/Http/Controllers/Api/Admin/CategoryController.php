<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $imagePath = null;

        //lưu vào categories -> storage/app/public/categories
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            // Kết quả trả về $imagePath sẽ có dạng: "categories/xxxxxx.jpg" 
        }

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
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
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục hoa này!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ], 200);
    }

    // 4. Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục hoa cần cập nhật!'
            ], 404);
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
            // Xóa file ảnh cũ trong storage nếu tồn tại
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Lưu ảnh mới 
            $updateData['image'] = $request->file('image')->store('categories', 'public');
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
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục hoa cần xóa!'
            ], 404);
        }

        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa vì danh mục này vẫn chứa sản phẩm!'
            ], 400);
        }

        // Xóa file ảnh vật lý trong storage nếu có
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công!'
        ], 200);
    }
}