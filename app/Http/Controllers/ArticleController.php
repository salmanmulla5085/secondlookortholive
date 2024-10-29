<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Categories;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ArticleController extends Controller
{
    // Show the list of articles
    public function listArticles()
    {
        // $pageSections = StaticPageContent::with('staticPage')->get(); // Load related static page data       
        $articleData = Article::all();      
        return view('admin_article.list-article-page', compact('articleData'));
    }

    public function create(Request $request)
    {
        // get the category data
        $categoryData = Categories::where('status', 'active')->get();
        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'title' => 'required',
                'short_desc' => 'required',
                'long_desc' => 'required',
                'category_id' => 'required',
            ]);

            // Save the validated data to the database
            $Article = new Article();
            $Article->title = $validatedData['title'];
            $Article->short_desc = $validatedData['short_desc'];
            $Article->long_desc = $validatedData['long_desc'];
            $Article->category_id = $validatedData['category_id'];

            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $fileNames = "";
                $errors = [];

                $name = $_FILES['image']['name'];

                $tmpName = $_FILES['image']['tmp_name'];
                $size = $_FILES['image']['size'];
                $error = $_FILES['image']['error'];

                // Validate file
                if ($error === UPLOAD_ERR_OK) {
                    // Specify allowed file types and size limit (e.g., 2MB)
                    $allowedTypes = ['jpg', 'png', 'jpeg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    $new_name = uniqid() . "." . $fileExt;

                    if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                        // Move uploaded file to the 'uploads' directory
                        $filePath = 'public/article_images/' . $new_name;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            // Save file information to the database

                            $fileNames = $new_name;
                            $Article->image = $fileNames;
                        } else {
                            $errors[] = "Failed to move file $name.";
                        }
                    } else {
                        $errors[] = "Invalid file type or size for file $name.";
                    }
                }
            }

            $Article->save();

            // Redirect or return success message
            return redirect()->route('admin.articles')->with('success', 'Article added successfully!');
        }
        // Render the add-static-page form view
        return view('admin_article.add-article', compact('categoryData'));
    }
    public function article_status($id = null, $status = null)
    {
        if ($id != null && $id > 0) {
            if ($status == 'inactive') {
                $UpdatedData['status'] = 'inactive';
                $msg = 'Article is inactive';
            } else {
                $UpdatedData['status'] = 'active';
                $msg = 'Article is active.';
            }

            $res = DB::table('blog_articles')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect('/admin/articles')->with('success', $msg);
        }
    }

    // Show the list of articles
    public function listStaticPages()
    {
        $staticPages = Article::all();
        return view('admin_article.list-article-pages', compact('staticPages'));
    }

    //showing the edit form
    public function editArticle($id)
    {
        $article = Article::findOrFail($id);
        $categoryData = Categories::all();
        return view('admin_article.edit-article', compact('article', 'categoryData'));
    }

    public function updateArticle(Request $request, $id)
    {
        // get the category data
        $categoryData = Categories::all();

        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'title' => 'required',
                'short_desc' => 'required',
                'long_desc' => 'required',
                'category_id' => 'required',
            ]);
            // print_r($_FILES['image']);
            if (!empty($_FILES['image']['name']))
            {
           
                // echo "file insert"; die();
                $fileNames = "";
                $errors = [];

                $name = $_FILES['image']['name'];

                $tmpName = $_FILES['image']['tmp_name'];
                $size = $_FILES['image']['size'];
                $error = $_FILES['image']['error'];

                // Validate file
                if ($error === UPLOAD_ERR_OK) {
                    // Specify allowed file types and size limit (e.g., 2MB)
                    $allowedTypes = ['jpg', 'png', 'jpeg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    $new_name = uniqid() . "." . $fileExt;

                    if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                        // Move uploaded file to the 'uploads' directory
                        $filePath = 'public/article_images/' . $new_name;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            // Save file information to the database

                            $fileNames = $new_name;
                            // $Article->image = $fileNames;
                        } else {
                            $errors[] = "Failed to move file $name.";
                        }
                    } else {
                        $errors[] = "Invalid file type or size for file $name.";
                    }
                }
            }
           

            $article = Article::findOrFail($id);
            $file = !empty($fileNames) ? $fileNames : null; // Set $file to null if it's empty
                    $articleData = [
                        'title' => $request->input('title'),
                        'short_desc' => $request->input('short_desc'),
                        'long_desc' => $request->input('long_desc'),
                        'category_id' => $request->input('category_id'),
                    ];

                    // Only add 'image' to the update if $file is not null
                    if ($file !== null) {
                        $articleData['image'] = $file;
                    }

$article->update($articleData);

            // Redirect or return success message
            return redirect()->route('admin.articles')->with('success', 'Article updated successfully!');
        }

        // // Render the add-static-page form view
        // return view('admin_article.ed-article', compact('categoryData'));
    }

    public function deleteArticle($id)
    {
        $page = Article::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.articles')->with('success', 'Article deleted successfully');
    }
    public function viewArticleComment($id){
        $articleData = Article::with(['category', 'comments','users'])->find($id);

        // In your controller or wherever you need to fetch the data
        // $comments = Comment::with('user')->find($id);
        $comments = Comment::with('user')
        ->where('article_id', $id)
        ->get();
        // dd($comments);
        return view('admin_article.view-article-comment', compact('comments'));  
        
    }
    public function comment_status($id = null, $status = null)
    {
        if ($id != null && $id > 0) {
            if ($status == 'inactive') {
                $UpdatedData['status'] = 'inactive';
                $msg = 'Comment is inactive';
            } else {
                $UpdatedData['status'] = 'active';
                $msg = 'Comment is active.';
            }

            $res = DB::table('blog_comments')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect()->back()->with('success', $msg);
        }
    }
}
