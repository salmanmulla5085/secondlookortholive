<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index(){
        $faqs = Faq::where('status',1)->get();
       return view('frontend.faq', compact('faqs'));
    }

    // app/Http/Controllers/FAQController.php

    public function admin_index()
    {
        $faqs = FAQ::all();
        return view('admin_faq.index', compact('faqs'));
    }


    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        FAQ::create($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $faq = FAQ::findOrFail($id);
        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}