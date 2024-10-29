<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\ReportReviewsReplies;


class ReportReviewsRepliesController extends Controller
{
   
// ReportReviewsRepliesController.php
public function deleteFile(Request $request)
{
    $replyId = $request->input('reply_id');
    $filename = $request->input('filename');
    
    $reply = ReportReviewsReplies::find($replyId);
    if ($reply) {
        // Get existing filenames
        $files = explode(',', $reply->upload_file1);
        
        // Remove the file to be deleted
        $files = array_filter($files, function($file) use ($filename) {
            return trim($file) !== $filename;
        });

        // Update the column with the new comma-separated values
        $reply->upload_file1 = implode(',', $files);
        $reply->save();
        
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false], 404);
}

}

