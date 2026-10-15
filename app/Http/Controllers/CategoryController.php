<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Category List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Category::withCount('services');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->get('sort', 'sort_order');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = [
            'name',
            'sort_order',
            'status',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'sort_order';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        $categories = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Category Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.categories.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Save Category
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Image to Cloudinary
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            $upload = Cloudinary::uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'salon/categories',
                ]
            );

            $validated['image'] = $upload['secure_url'];
        }


        /*
        |--------------------------------------------------------------------------
        | Default Sort Order
        |--------------------------------------------------------------------------
        */
        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Create Category
        |--------------------------------------------------------------------------
        */
        Category::create($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Category Form
    |--------------------------------------------------------------------------
    */
    public function edit(Category $category)
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Category
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Category $category
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->ignore($category->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            // Delete old image
            if ($category->image) {
                $this->deleteCloudinaryImage(
                    $category->image
                );
            }


            // Upload new image
            $upload = Cloudinary::uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'salon/categories',
                ]
            );

            $validated['image'] =
                $upload['secure_url'];
        }


        /*
        |--------------------------------------------------------------------------
        | Default Sort Order
        |--------------------------------------------------------------------------
        */
        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;


        /*
        |--------------------------------------------------------------------------
        | Update Category
        |--------------------------------------------------------------------------
        */
        $category->update($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Category
    |--------------------------------------------------------------------------
    */
    public function destroy(Category $category)
    {
        /*
        |--------------------------------------------------------------------------
        | Don't allow delete if services exist
        |--------------------------------------------------------------------------
        */
        if ($category->services()->exists()) {

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'error',
                    'This category contains services. Please remove or reassign the services first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Cloudinary Image
        |--------------------------------------------------------------------------
        */
        if ($category->image) {
            $this->deleteCloudinaryImage(
                $category->image
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Category
        |--------------------------------------------------------------------------
        */
        $category->delete();


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Toggle Active / Inactive
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'status' => $category->status === 'active'
                ? 'inactive'
                : 'active',
        ]);


        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Category status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Image from Cloudinary
    |--------------------------------------------------------------------------
    */
    private function deleteCloudinaryImage(
        string $imageUrl
    ): void {
        try {

            $path = parse_url(
                $imageUrl,
                PHP_URL_PATH
            );

            if (!$path) {
                return;
            }


            $path = ltrim($path, '/');


            $uploadPosition = strpos(
                $path,
                'upload/'
            );

            if ($uploadPosition === false) {
                return;
            }


            $publicId = substr(
                $path,
                $uploadPosition + strlen('upload/')
            );


            /*
            |--------------------------------------------------------------------------
            | Remove version from URL
            |--------------------------------------------------------------------------
            */
            $publicId = preg_replace(
                '/^v\d+\//',
                '',
                $publicId
            );


            /*
            |--------------------------------------------------------------------------
            | Remove file extension
            |--------------------------------------------------------------------------
            */
            $publicId = preg_replace(
                '/\.[^.\/]+$/',
                '',
                $publicId
            );


            /*
            |--------------------------------------------------------------------------
            | Delete from Cloudinary
            |--------------------------------------------------------------------------
            */
            Cloudinary::uploadApi()->destroy(
                $publicId,
                [
                    'resource_type' => 'image',
                ]
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Don't stop category operation
            |--------------------------------------------------------------------------
            */
            report($e);
        }
    }
}

