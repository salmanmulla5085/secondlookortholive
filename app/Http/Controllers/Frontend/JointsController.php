<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Joints;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class JointsController extends Controller
{
    public function index(){
        $joints = Joints::all();
        return view('joints.index', compact('joints'));
    }

    public function view_joint($joint_id = null, $operation = null){

        $data['title'] = $operation;

        if($joint_id != null && $joint_id > 0){
            $joint_sql = "SELECT * FROM tbl_joints where id = $joint_id";
            $JointSQL = DB::select($joint_sql);
            $data['JointData'] = collect($JointSQL);
        }

        return view("joints.view-edit", $data);
    }

    // Store a newly created joint section in db
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'page_name'         => 'required',
            'heading1'          => 'required',
            'heading2'          => 'required',
            'heading3'          => 'required',
            'heading4'          => 'required',
            'content1'          => 'required', 
            'content2'          => 'required', 
            'content3'          => 'required', 
            'content4'          => 'required', 
            'content5'          => 'required',            
        ]);

        if (isset($_FILES['photo1']) && !empty($_FILES['photo1']['name'][0]))
                {
                    $name = $_FILES['photo1']['name'];
                    
                    $fileNames = [];
                    $errors = [];
    
                    $tmpName = $_FILES['photo1']['tmp_name'];
                    $size = $_FILES['photo1']['size'];
                    $error = $_FILES['photo1']['error'];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['pdf','jpeg','jpg','png'];
                        $maxSize = 4 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        $new_name = uniqid().".".$fileExt;
                        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            // $filePath = $request->ExtJointname;
                            $filePath = 'public/homepage_img/' . $request->ExtJointname .'/'.$new_name;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames = $new_name;
                            } else {
                                $errors[] = "Failed to move file $name.";
                            }
                        } else {
                            
                            $errors[] = "Invalid file type or size for file $name.";
                        }
                    } else {
                        $errors[] = "Error uploading file $name. Error code: $error.";
                    }
                
                $validatedData['photo1'] = $fileNames;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    return redirect()->back()->withErrors($errors)->withInput();

                    
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            
        }

        if (isset($_FILES['photo2']) && !empty($_FILES['photo2']['name'][0]))
                {
                    $name = $_FILES['photo2']['name'];
                    
                    $fileNames = [];
                    $errors = [];
    
                    $tmpName = $_FILES['photo2']['tmp_name'];
                    $size = $_FILES['photo2']['size'];
                    $error = $_FILES['photo2']['error'];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['pdf','jpeg','jpg','png'];
                        $maxSize = 4 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        $new_name = uniqid().".".$fileExt;
                        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            // $filePath = $request->ExtJointname;
                            $filePath = 'public/homepage_img/' . $request->ExtJointname .'/'.$new_name;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames = $new_name;
                            } else {
                                $errors[] = "Failed to move file $name.";
                            }
                        } else {
                            
                            $errors[] = "Invalid file type or size for file $name.";
                        }
                    } else {
                        $errors[] = "Error uploading file $name. Error code: $error.";
                    }
                
                $validatedData['photo2'] = $fileNames;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    return redirect()->back()->withErrors($errors)->withInput();
                    
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            
        }

        if (isset($_FILES['photo3']) && !empty($_FILES['photo3']['name'][0]))
                {
                    $name = $_FILES['photo3']['name'];
                    
                    $fileNames = [];
                    $errors = [];
    
                    $tmpName = $_FILES['photo3']['tmp_name'];
                    $size = $_FILES['photo3']['size'];
                    $error = $_FILES['photo3']['error'];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['pdf','jpeg','jpg','png'];
                        $maxSize = 4 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        $new_name = uniqid().".".$fileExt;
                        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            // $filePath = $request->ExtJointname;
                            $filePath = 'public/homepage_img/' . $request->ExtJointname .'/'.$new_name;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames = $new_name;
                            } else {
                                $errors[] = "Failed to move file $name.";
                            }
                        } else {
                            
                            $errors[] = "Invalid file type or size for file $name.";
                        }
                    } else {
                        $errors[] = "Error uploading file $name. Error code: $error.";
                    }
                
                $validatedData['photo3'] = $fileNames;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    return redirect()->back()->withErrors($errors)->withInput();
                
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            
        }

        if (isset($_FILES['photo4']) && !empty($_FILES['photo4']['name'][0]))
                {
                    $name = $_FILES['photo4']['name'];
                    
                    $fileNames = [];
                    $errors = [];
    
                    $tmpName = $_FILES['photo4']['tmp_name'];
                    $size = $_FILES['photo4']['size'];
                    $error = $_FILES['photo4']['error'];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['pdf','jpeg','jpg','png'];
                        $maxSize = 4 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        $new_name = uniqid().".".$fileExt;
                        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            // $filePath = $request->ExtJointname;
                            $filePath = 'public/homepage_img/' . $request->ExtJointname .'/'.$new_name;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames = $new_name;
                            } else {
                                $errors[] = "Failed to move file $name.";
                            }
                        } else {
                            
                            $errors[] = "Invalid file type or size for file $name.";
                        }
                    } else {
                        $errors[] = "Error uploading file $name. Error code: $error.";
                    }
                
                $validatedData['photo4'] = $fileNames;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    return redirect()->back()->withErrors($errors)->withInput();
                    
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            
        }

        if (isset($_FILES['photo5']) && !empty($_FILES['photo5']['name'][0]))
                {
                    $name = $_FILES['photo5']['name'];
                    
                    $fileNames = [];
                    $errors = [];
    
                    $tmpName = $_FILES['photo5']['tmp_name'];
                    $size = $_FILES['photo5']['size'];
                    $error = $_FILES['photo5']['error'];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['pdf','jpeg','jpg','png'];
                        $maxSize = 4 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        $new_name = uniqid().".".$fileExt;
                        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            // $filePath = $request->ExtJointname;
                            $filePath = 'public/homepage_img/' . $request->ExtJointname .'/'.$new_name;

                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames = $new_name;
                            } else {
                                $errors[] = "Failed to move file $name.";
                            }
                        } else {
                            
                            $errors[] = "Invalid file type or size for file $name.";
                        }
                    } else {
                        $errors[] = "Error uploading file $name. Error code: $error.";
                    }
                
                $validatedData['photo5'] = $fileNames;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    return redirect()->back()->withErrors($errors)->withInput();
                    
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            
        }
        
        $res = DB::table('tbl_joints')
        ->where('id', $request->ExtJointId)
        ->update($validatedData);  

        return redirect()->route('joints')->with('success', 'Joint added successfully');
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

    public function update111(Request $request, $id)
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