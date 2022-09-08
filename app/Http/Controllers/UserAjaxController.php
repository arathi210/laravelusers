<?php

namespace App\Http\Controllers;

use App\Models\UserRecord;
use Illuminate\Http\Request;
use DataTables;
use Image;
use File;
use Validator;

class UserAjaxController extends Controller
{
    //
    public function index(Request $request)
    {
     
        if ($request->ajax()) {
  
            $data = UserRecord::get();
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('fullname', function ($row) { 
                        $fullname = $row->fullname;
                            return $fullname;
                   })
                   ->addColumn('email', function ($row) { 
                        $email = $row->email;
                            return $email;
                   })
                   ->addColumn('exp', function ($row) { 
                    if($row->dol == null)
                    {
                        $curdate = date('Y-m-d');
                    }
                    else
                    {
                        $curdate = $row->dol;
                    }

                    $date_diff = abs(strtotime($curdate) - strtotime($row->doj));

                    $years = floor($date_diff / (365*60*60*24));
                    $months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
                    
                    //$email = $row->email;
                        return $years.' Years '.$months. ' Months' ;
                   })
                    ->addColumn('action', function($row){
   
                           
                           $btn =' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action','exp'])
                    ->make(true);
        }
        
        return view('userAjax');
    }
       
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $image = $request->file('image1');
        //dd( $image);
       
        $user_image = rand() . uniqid() . '.' . $image->getClientOriginalExtension();
        
        $org_img = Image::make($image->getRealPath())
            ->orientate();
     

        $destinationPath = public_path('upload/userimage');
        
        if (!File::isDirectory($destinationPath))
        {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $org_img->save($destinationPath . '/' . $user_image);

        $rule = [
            'fullname' => 'required',
            'email' => 'required | email|unique:userrecords',
            'doj' => 'required',
           // 'dol'=> 'sometimes|gte:doj',
            'image1'=>'required',
        ];
        $messages = [
            'name.required' => 'Name is required',
            'email.unique' => 'Email Should be unique',
            'doj.required' => 'DOJ is required',
           
            'image1'=> 'Image is required',
        ];

        $validator = Validator::make($request->all(), $rule, $messages);
        if ($validator->passes())
        {
                    UserRecord::updateOrCreate([
                                'id' => $request->product_id
                            ],
                            [
                                'fullname' => $request->fullname, 
                                'email' => $request->email, 
                                'doj' => $request->doj,
                                'dol' => $request->dol,
                                'work_status' => $request->work_status,
                                'image' => $user_image
                            ]);        
                
                    return response()->json(['success'=>'User Record saved successfully.']);
        }
        else
        {
            return response()->json(["error"=>$validator->errors()]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = UserRecord::find($id);
        return response()->json($product);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserRecord::find($id)->delete();
      
        return response()->json(['success'=>'User Reord deleted successfully.']);
    }
}
