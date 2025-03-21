<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('properties');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'garages' => 'required|integer|min:0',
            'area' => 'required|integer|min:0',
            'furnished' => 'boolean',
            'available_from' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'featured' => 'boolean',
            'amenities' => 'nullable|array',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $property = Properties::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                $propertyImage = new PropertyImage([
                    'image_path' => $path,
                    'is_primary' => $index === 0, // First image is primary
                    'sort_order' => $index,
                ]);
                
                $property->images()->save($propertyImage);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Properties $property)
    {
        return "Hello";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Properties $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Properties $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'garages' => 'required|integer|min:0',
            'area' => 'required|integer|min:0',
            'furnished' => 'boolean',
            'available_from' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'featured' => 'boolean',
            'amenities' => 'nullable|array',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $property->update($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                $propertyImage = new PropertyImage([
                    'image_path' => $path,
                    'is_primary' => $request->input('primary_image') == $index,
                    'sort_order' => $index + $property->images()->count(),
                ]);
                
                $property->images()->save($propertyImage);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Properties $property)
    {
        // Delete associated images from storage
        foreach ($property->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
