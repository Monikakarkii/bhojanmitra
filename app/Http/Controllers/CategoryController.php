<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(5); // Paginate categories
        return view('backend.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
                'show_on_nav' => 'nullable|boolean',
                'nav_index' => 'nullable|integer',
                'show_on_home' => 'nullable|boolean',
                'home_index' => 'nullable|integer',
            ]);

            $logoPath = null;

            if ($request->hasFile('logo')) {
                // Get the uploaded file
                $image = $request->file('logo');

                // Generate a unique name for the image file
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Define the storage path inside the 'public' folder (accessible via public URL)
                $storagePath = public_path('categories');  // Using 'storage' for public access

                // Create the directory if it doesn't exist
                if (!file_exists($storagePath)) {
                    if (!mkdir($storagePath, 0775, true) && !is_dir($storagePath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
                    }
                }

                // Move the uploaded file to the storage path with the generated name
                $image->move($storagePath, $imageName);

                // Save the file path (relative to the 'public' folder)
                $logoPath = 'categories/' . $imageName;
            }

            // Create a new category and assign data
            $category = new Category();
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status');
            $category->icon = $request->input('icon'); // Assuming 'icon' field exists in the table
            $category->logo = $logoPath; // Store the logo path in the database
            $category->show_on_nav = $request->input('show_on_nav', 0); // Default to false if not checked
            $category->nav_index = $request->input('nav_index');
            $category->show_on_home = $request->input('show_on_home', 0); // Default to false if not checked
            $category->home_index = $request->input('home_index');
            $category->save();

            // Flash success message to the session
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error creating category: ' . $e->getMessage());

            // Flash error message to the session
            return redirect()->back()->with('error', 'An error occurred while creating the category'.  $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the table by its ID
        $category = Category::find($id); // Using find() instead of findOrFail()

        // Check if the table was found
        if (!$category) {
            // Flash a message to the session
            session()->flash('error', 'Category not found.');

            // Redirect back to the previous page or a specific route
            return redirect()->route('categories.index'); // Or any other route you want to redirect to
        }

        // Return the edit view with the table data if found
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,inactive',
                'show_on_nav' => 'nullable|boolean',
                'nav_index' => 'nullable|integer',
                'show_on_home' => 'nullable|boolean',
                'home_index' => 'nullable|integer',
            ]);

            // Find the category by ID
            $category = Category::findOrFail($id);

            // Handle logo upload if a new file is provided
            $logoPath = $category->logo; // Keep existing logo if not updated

            if ($request->hasFile('logo')) {
                // Delete the old logo if it exists
                if ($category->logo && file_exists(public_path($category->logo))) {
                    unlink(public_path($category->logo)); // Delete the old logo from the storage
                }

                // Get the uploaded file
                $image = $request->file('logo');

                // Generate a unique name for the image file
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Define the storage path inside the 'public' folder (accessible via public URL)
                $storagePath = public_path('categories');  // Using 'storage' for public access

                // Create the directory if it doesn't exist
                if (!file_exists($storagePath)) {
                    if (!mkdir($storagePath, 0775, true) && !is_dir($storagePath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
                    }
                }

                // Move the uploaded file to the storage path with the generated name
                $image->move($storagePath, $imageName);

                // Update the logo path
                $logoPath = 'categories/' . $imageName;
            }

            // Update the category data
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status');
            $category->icon = $request->input('icon');
            $category->show_on_nav = $request->input('show_on_nav',0); // Default to false if not checked
            $category->nav_index = $request->input('nav_index',);
            $category->show_on_home = $request->input('show_on_home',0); // Default to false if not checked
            $category->home_index = $request->input('home_index');
            $category->logo = $logoPath; // Store the new logo path in the database if updated
            $category->save();

            // Flash success message to the session
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating category: ' . $e->getMessage());

            // Flash error message to the session
            //with error messaage $e->getMessage()
            return redirect()->back()->with('error', 'An error occurred while updating the category. ' . $e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            // Delete the associated logo image if it exists
            if ($category->logo && file_exists(public_path($category->logo))) {
                unlink(public_path($category->logo));
            }

            // Delete the category from the database
            $category->delete();

            // Redirect back with success message
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            // Log the error and show an error message
            Log::error('Error deleting category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the category.');
        }
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        // Filter categories based on the search term
        $categories = Category::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->paginate(10);

        // Return the filtered categories as JSON
        return response()->json([
            'categories' => $categories
        ]);
    }
}
