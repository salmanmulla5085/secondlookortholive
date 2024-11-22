<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('admin_testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin_testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->content = $request->content;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('frontend/img/static_pages'), $imageName);
            $testimonial->photo = 'frontend/img/static_pages/' . $imageName;
        }
        
       

        $testimonial->status = $request->status; // Add status field

        $test = $testimonial->save();

        // dd($test);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added successfully.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin_testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->name = $request->name;
        $testimonial->content = $request->content;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('frontend/img/static_pages'), $imageName);
            $testimonial->photo = 'frontend/img/static_pages/' . $imageName;
        }

        $testimonial->status = $request->status; // Add status field


        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->photo && file_exists(public_path($testimonial->photo))) {
            unlink(public_path($testimonial->photo));
        }
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function activate($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->status = 'active';
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial activated successfully.');
    }

    public function deactivate($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->status = 'inactive';
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deactivated successfully.');
    }

}
