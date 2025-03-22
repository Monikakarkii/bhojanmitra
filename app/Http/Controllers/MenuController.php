<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\UserToken;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index($tableNumber, Request $request, $categorySlug = null)
    {
        try {
            // Check if session has the 'user_table'
            if (!session()->has('user_table')) {
                return redirect()->route('home')->with('error', 'Session expired or invalid.');
            }
               // Check if the user has a token in local storage
        if (!$request->session()->has('user_token')) {
            $userToken = UserToken::create([
                'token' => Str::random(60), // Generate a random 60-character token
                'table_number' => $tableNumber,
            ]);
            $request->session()->put('user_token', $userToken->token);
        }

            // Fetch active categories for navigation
            $navCategories = Category::where('status', 'active')
                ->where('show_on_nav', true)
                ->orderBy('nav_index')
                ->get();

            // Fetch categories and menu items based on whether a categorySlug is provided
            if ($categorySlug !== null) {
                $homeCategories = Category::where('status', 'active')
                    ->where('slug', $categorySlug)
                    ->with(['menuItems' => function ($query) {
                        $query->take(4); // Limit to 5 menu items
                    }])
                    ->get();

                // Check if the category exists
                if ($homeCategories->isEmpty()) {
                    return redirect()->route('menu.home', ['tableNumber' => $tableNumber])
                        ->with('error', 'The requested category does not exist.');
                }

            } else {
                $homeCategories = Category::where('status', 'active')
                    ->where('show_on_home', true)
                    ->orderBy('home_index')
                    ->with(['menuItems' => function ($query) {
                        $query->take(8); // Limit to 8 menu items
                    }])
                    ->get();
            }

            // Return the view with the necessary data
            return view('frontend.menu.index', compact('homeCategories', 'navCategories', 'tableNumber', 'categorySlug'));

        } catch (\Exception $e) {
            dd($e);


            // Return a generic error message to the user
            return redirect()->route('home')->with('error', 'An error occurred while fetching the data.');
        }
    }



    public function userToken(Request $request)
    {
        // Retrieve the token from request
        $token = $request->query('token');

        if ($token) {
            // Check if the token exists in the database
            $userToken = UserToken::where('token', $token)->first();

            if ($userToken) {
                // Token exists, set session
                session(['user_token' => $token]);
                return response()->json(['success' => true, 'message' => 'Welcome back!']);
            }

            // Token not found in database, generate a new one
            return $this->generateNewToken();
        }

        // No token provided, generate a new one
        return $this->generateNewToken();
    }

    private function generateNewToken()
    {
        $newToken = Str::random(40);

        // Save new token in database
        UserToken::create(['token' => $newToken]);

        // Set session with the new token
        session(['user_token' => $newToken]);

        return response()->json([
            'success' => true,
            'token' => $newToken,
            'message' => 'Welcome to our restaurant'
        ]);
    }
    public function show($menuSlug)
    {

        if (!session()->has('user_table')) {
            return redirect()->route('home')->with('error', 'Session expired or invalid.');
        }
        else{
            $menuItem = MenuItem::where('slug', $menuSlug)
                ->where('availability', '1')
                ->firstOrFail();

            return view('frontend.menu.show', compact('menuItem'));
        }
    }

    public function viewAll($categorySlug)
    {
        if (!session()->has('user_table')) {
            return redirect()->route('home')->with('error', 'Session expired or invalid.');
        }
        else{
            $category = Category::where('slug', $categorySlug)

                ->where('status', 'active')
                ->with(['menuItems' => function ($query) {
                    $query->orderBy('name', 'asc'); // Order menu items alphabetically
                }])
                ->firstOrFail();

            return view('frontend.menu.viewall', compact('category'));

        }

    }
}
