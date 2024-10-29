<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    // Show the list of Categories
    public function listCategories()
    {
        // $pageSections = StaticPageContent::with('staticPage')->get(); // Load related static page data       
        $categoryData = Categories::all();      
        return view('admin_category.list-categories', compact('categoryData'));
    }

    public function create(Request $request)
    {
            // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'category_name' => 'required',               
            ]);

            // Save the validated data to the database
            $Category = new Categories();
            $Category->category_name = $validatedData['category_name'];          
            $Category->save();

            // Redirect or return success message
            return redirect()->route('admin.categories')->with('success', 'Category added successfully!');
        }
        // Render the add-static-page form view
        return view('admin_category.add-category');
    }
    
    public function category_status($id = null, $status = null)
    {
        if ($id != null && $id > 0) {
            if ($status == 'inactive') {
                $UpdatedData['status'] = 'inactive';
                $msg = 'Category is inactive';
            } else {
                $UpdatedData['status'] = 'active';
                $msg = 'Category is active.';
            }

            $res = DB::table('blog_categories')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect('/admin/categories')->with('success', $msg);
        }
    }


    //showing the edit form
    public function editCategory($id)
    {
        $categoryData = Categories::findOrFail($id);
      
        return view('admin_category.edit-category', compact('categoryData'));
    }

    public function updateCategory(Request $request, $id)
    {
        

        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'category_name' => 'required',               
            ]);
            
            $Categories = Categories::findOrFail($id);
            $Categories->update([
                'category_name' => $request->input('category_name'),
               
            ]);

            // Redirect or return success message
            return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
        }

        // // Render the add-static-page form view
        // return view('admin_article.ed-article', compact('categoryData'));
    }

    public function deleteCategory($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }
}
