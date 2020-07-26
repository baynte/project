<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Crud;

class CrudController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Crud::with('author')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateValidation $request)
    {
        $valid = $request->validated();

        if($valid){
            $new = new Crud;
            
            $new->title = $request->title;
            $new->description = $request->description;

            $new->author()->associate(Auth::user());
            $new->save();

            return $new;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = $request->validated();
        
        if($valid){
            $toBeUpdated = Crud::find($id);
            
            //Check property
            if($toBeUpdated->author_id == Auth::user()->id){
                
                $toBeUpdated->title = $request->title;
                $toBeUpdated->description = $request->description;

                $toBeUpdated->save();
                
                return response([ 'Successfuly Updated' => $toBeUpdated ], 200);
            }else {
                return response(['Unauthorized'], 401);
            }
        }
        return response(['Invalid Entity'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $toBeDestroyed = Crud::find($id);

        //Check property
        if($toBeDestroyed->author_id == Auth::user()->id){
            $toBeDestroyed->delete();
            
            return response([ 'PlacedInTrash' => $toBeDestroyed ], 200);
        }
        
        return response(['Message'=> 'Unauthorized action.'], 401);
    }
}
