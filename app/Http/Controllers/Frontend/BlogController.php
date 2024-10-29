<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categories;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Dd;

class BlogController extends Controller
{
    public function index($id = null)
    {
       
        $query = Article::with(['category', 'comments']);
        if ($id) {
            $query->where('category_id', $id);
        }
        $query->where('status','active');
        $query->orderBy('id', 'desc');
        $articleData = $query->paginate(5);
    

        // Fetch the last 10 records ordered by the most recent created date
        $articleRecentData = Article::with(['category', 'comments'])
            // ->latest() // Orders by 'created_at' in descending order
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        
        // $categoryData = Categories::all();
        // Get all categories with a count of related articles
        // $categoryData = Categories::withCount('articles')->get();
        $categoryData = Categories::withCount(['articles' => function ($query2) {
            $query2->where('status', 'active'); // Count only active articles
        }])
        ->where('status', 'active') // Filter categories with an active status
        ->get();

        // dd($categoryData );
        return view('frontend.blog', compact('articleData', 'articleRecentData','categoryData'));
    }

    public function article_details($id)
    {
        $user = $this->getSessionData("user");
        // normal relation condition
        // $articleData = Article::with(['category', 'comments','comments.user',])->find($id);

        // added a with a where condition?

        $articleData = Article::with([
            'category',
            'comments' => function ($query) {
                $query->where('status', 'active'); // Replace 'active' with your actual active status value
            },
            'comments.user'
        ])->find($id);

        $articleRecentData = Article::with([
            'category',
            'comments' => function ($query2) {
                $query2->where('status', 'active'); 
            },
            ])->orderBy('id', 'desc')
            ->where('status','active')
            ->take(5)
            ->get();

        // $categoryData = Categories::withCount('articles')->get();  
        $categoryData = Categories::withCount(['articles' => function ($query1) {
            $query1->where('status', 'active'); // Count only active articles
        }])
        ->where('status', 'active') // Filter categories with an active status
        ->get();
        return view('frontend.blog-details', compact('articleData', 'articleRecentData','categoryData','user'));
    }

    public function store(Request $request)
    {
       
        // Validate the request
        $request->validate([
            'comment' => 'required|string|max:1000', 
        ]);

        // Create a new comment
        Comments::create([
            'article_id' => $request->input('article_id'), 
            'user_id' => $request->input('user_id'), 
            'comment' => $request->input('comment'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Comment posted successfully!');
    }



    

}
