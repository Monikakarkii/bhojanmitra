<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuItems = MenuItem::latest()->paginate(5); // Paginate categories
        return view('backend.menuitems.index', compact('menuItems'));
    }

    public function create()
    {
        // Retrieve all categories to show in the dropdown
        $categories = Category::all();

        // Return the view with categories
        return view('backend.menuitems.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string', // Ensure tags are optional
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional image validation
            'description' => 'nullable|string',
            'availability' => 'required|boolean',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            // Get the uploaded file
            $image = $request->file('image');

            // Generate a unique name for the image file
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Define the storage path inside the 'public' folder (accessible via public URL)
            $storagePath = public_path('menu-items');  // Using 'menu-items' folder for storage

            // Create the directory if it doesn't exist
            if (!file_exists($storagePath)) {
                if (!mkdir($storagePath, 0775, true) && !is_dir($storagePath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
                }
            }

            // Move the uploaded file to the storage path with the generated name
            $image->move($storagePath, $imageName);

            // Save the file path (relative to the 'public' folder)
            $imagePath = 'menu-items/' . $imageName;
        }
        // Create the menu item
        $menuItem = MenuItem::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'availability' => (bool) $validated['availability'],  // Ensure availability is stored as a boolean
            'image' => $imagePath,
        ]);

        // Sync categories
        $menuItem->categories()->sync($validated['categories']);

        // Handle tags - convert the comma-separated string to an array and sync
        if ($request->has('tags')) {
            $tagIds = collect(explode(',', $request->tags))->map(function ($tag) {
                return Tag::firstOrCreate(['name' => trim($tag)])->id;
            });
            $menuItem->tags()->sync($tagIds);
        }

        return redirect()->route('menu-items.index')->with('success', 'Menu item created successfully!');
    }
    public function edit($id)
    {
        // Fetch the existing menu item
        $menuItem = MenuItem::with('categories', 'tags')->findOrFail($id);

        // Fetch all categories to show in the select dropdown
        $categories = Category::all();

        // Return the view with the menu item data and categories
        return view('backend.menuitems.edit', compact('menuItem', 'categories'));
    }


    // Update the menu item
    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'availability' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        // Fetch the menu item
        $menuItem = MenuItem::findOrFail($id);

        // Update menu item data
        $menuItem->name = $request->name;
        $menuItem->price = $request->price;
        $menuItem->availability = $request->availability;
        $menuItem->description = $request->description;

        // Handle image upload if new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($menuItem->image && file_exists(public_path($menuItem->image))) {
                unlink(public_path($menuItem->image));  // Delete the old image
            }

            // Get the uploaded file
            $image = $request->file('image');

            // Generate a unique name for the image file
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Define the storage path inside the 'public' folder
            $storagePath = public_path('menu-items');  // Using 'menu-items' folder for storage

            // Create the directory if it doesn't exist
            if (!file_exists($storagePath)) {
                if (!mkdir($storagePath, 0775, true) && !is_dir($storagePath)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $storagePath));
                }
            }

            // Move the uploaded file to the storage path with the generated name
            $image->move($storagePath, $imageName);

            // Save the file path (relative to the 'public' folder)
            $imagePath = 'menu-items/' . $imageName;

            // Update the image path in the menu item
            $menuItem->image = $imagePath;
        }

        // Save updated data
        $menuItem->save();

        // Sync categories
        $menuItem->categories()->sync($request->categories);

        // Handle tags - convert the comma-separated string to an array and sync
        if ($request->has('tags')) {
            $tagIds = collect(explode(',', $request->tags))->map(function ($tag) {
                return Tag::firstOrCreate(['name' => trim($tag)])->id;
            });
            $menuItem->tags()->sync($tagIds);
        }

        // Redirect with success message
        return redirect()->route('menu-items.index')->with('success', 'Menu Item updated successfully!');
    }




    public function destroy($id)
    {
        // Find the menu item by ID
        $menuItem = MenuItem::findOrFail($id);
        if(!$menuItem){
            return redirect()->route('menu-items.index')->with('error', 'Menu item not found!');
        }

        // Delete associated image from storage (if exists)
        if ($menuItem->image && file_exists(public_path($menuItem->image))) {
            unlink(public_path($menuItem->image));  // Delete the image file
        }

        // Detach the categories associated with the menu item
        $menuItem->categories()->detach();

        // Detach the tags associated with the menu item
        $menuItem->tags()->detach();

        // Delete the menu item from the database
        $menuItem->delete();

        // Redirect to the menu items index page with a success message
        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted successfully!');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        // Get the search term from the request


        // Filter categories based on the search term
        $menuItems = MenuItem::where('name', 'like', '%' . $query . '%')
//            ->orWhere('description', 'like', '%' . $query . '%')
            ->paginate(10);

        // Return the filtered categories as JSON
        return response()->json([
            'menuItemsP' => $menuItems,  // Ensure you are sending 'data' here
        ]);
    }


}
