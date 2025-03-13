<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Exception;
use Illuminate\Http\Request;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show all tables
    public function index(Request $request)
    {
        // Get the number of items per page, default to 5 if not provided
        $perPage = $request->input('per_page', 5);

        // Fetch tables with dynamic pagination
        $tables = Table::paginate($perPage);

        return view('backend.tables.index', compact('tables'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'table_number' => 'required|unique:tables,table_number|max:255',
                'status' => 'required|in:active,inactive',
            ]);

            // Create the new table entry
            $table = new Table();
            $table->table_number = $request->table_number;
            $table->status = $request->status;

            // Generate a unique token
            $table->token = Str::random(32);

            // Generate a unique QR code for the table with the token
            $tableUrl = url('/home/' . $request->table_number . '?token=' . $table->token);

            // Initialize the QR Code generator
            $renderer = new ImageRenderer(
                new RendererStyle(400),  // Set the size of the QR code
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($tableUrl);

            // Save the QR code to a file
            $qrCodeFileName = 'qrcode_' . $request->table_number . '.svg';
            $qrCodePath = public_path('qr_codes/' . $qrCodeFileName);

            if (!file_put_contents($qrCodePath, $qrCodeSvg)) {
                throw new Exception("Failed to save QR code file to {$qrCodePath}");
            }

            // Save the path to the QR code in the database
            $table->qr_code = 'qr_codes/' . $qrCodeFileName;

            // Save the table entry in the database
            $table->save();

            // Flash a success message to the session
            session()->flash('success', 'Table created successfully!');

            // Redirect or return a response
            return redirect()->route('tables.index');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors specifically
            session()->flash('error', 'Database error: ' . $e->getMessage());
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            // Handle all other errors
            session()->flash('error', 'Error: ' . $e->getMessage());
            return redirect()->back()->withInput();
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
    public function edit($id)
    {
        // Find the table by its ID
        $table = Table::find($id); // Using find() instead of findOrFail()

        // Check if the table was found
        if (!$table) {
            // Flash a message to the session
            session()->flash('error', 'Table not found.');

            // Redirect back to the previous page or a specific route
            return redirect()->route('tables.index'); // Or any other route you want to redirect to
        }

        // Return the edit view with the table data if found
        return view('backend.tables.edit', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'table_number' => 'required|max:255|unique:tables,table_number,' . $id, // Ensures the table number is unique except for the current table
                'status' => 'required|in:available,occupied',
            ]);

            // Find the table by ID
            $table = Table::findOrFail($id);

            // Update the table's basic fields
            $table->table_number = $request->table_number;
            $table->status = $request->status;

            // Regenerate the QR code using the existing token
            if (!$table->token) {
                // If no token exists, generate a new one
                $table->token = Str::random(32);
            }

            $tableUrl = url('/home/' . $request->table_number . '?token=' . $table->token);

            // Initialize the QR Code generator
            $renderer = new ImageRenderer(
                new RendererStyle(400), // Set the size of the QR code
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($tableUrl);

            // Define the file path for the new QR code
            $qrCodeFileName = 'qrcode_' . $request->table_number . '.svg';
            $qrCodePath = public_path('qr_codes/' . $qrCodeFileName);

            // Attempt to save the QR code file
            if (!file_put_contents($qrCodePath, $qrCodeSvg)) {
                throw new Exception("Failed to save QR code file to {$qrCodePath}");
            }

            // Update the QR code file path in the database
            $table->qr_code = 'qr_codes/' . $qrCodeFileName;

            // Save the updated table record
            $table->save();

            // Flash a success message to the session
            session()->flash('success', 'Table updated successfully!');

            // Redirect to the tables index page
            return redirect()->route('tables.index');
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error updating table: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Flash an error message to the session
            session()->flash('error', 'An error occurred while updating the table. Please try again.');

            // Redirect back to the form with input data preserved
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        try {
            // Find the table by ID
            $table = Table::findOrFail($id);

            // Attempt to delete the QR code file if it exists
            $qrCodePath = public_path($table->qr_code);
            if (File::exists($qrCodePath)) {
                if (!File::delete($qrCodePath)) {
                    throw new Exception("Failed to delete QR code file at {$qrCodePath}");
                }
            }

            // Delete the table record from the database
            $table->delete();

            // Flash a success message to the session
            session()->flash('success', 'Table deleted successfully!');

            // Redirect to the tables index page
            return redirect()->route('tables.index');
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error deleting table: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Flash an error message to the session
            session()->flash('error', 'Error: ' . $e->getMessage());

            // Redirect back to the tables index page
            return redirect()->route('tables.index');
        }
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $tables = Table::when($keyword, function ($query, $keyword) {
            return $query->where('table_number', 'LIKE', '%' . $keyword . '%');
        })->paginate(10);

        return response()->json([
            'tables' => $tables
        ]);
    }


}
