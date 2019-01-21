<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Project;
use App\Models\Client;
//use App\Invoice;
//use App\HistoryInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateClientValidate;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
	private $project;
    private $totalPage = 4;
    private $path = 'projects'; 

    /**
     * DI of \App\Models\Project
     *
     * @param  \App\Models\Project $project
     */
    public function __construct(Project $project){
    	$this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON
     */
    public function index(Request $request){
    	$project = $this->project->getResults($request->all(), $this->totalPage);

    	return response()->json($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUpdateProjectValidate $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProjectValidate $request)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $name = kebab_case($request->name);

            $extension = $request->image->extension();

            $nameFile = "{$name}.{$extension}";

            $data['image'] = $nameFile;

            $upload = $request->image->storeAs($this->path, $nameFile);

            if(!$upload)
                return response()->json(['error' => 'fail_upload'], 500);
        }

        if (!$project = $this->project->create($data) )
            return response()->json(['error' => 'error_insert'], 500);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ( !$project = $this->project->find($id) )
            return response()->json(['error' => 'project_not_found'], 404);

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreUpdateProjectValidate $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProjectValidate $request, $id)
    {
        $data = $request->all();

        if ( !$project = $this->project->find($id) )
            return response()->json(['error' => 'project_not_found'], 404);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($project->image != null) {
                if (Storage::exists("{$this->path}/{$project->image}"))
                    Storage::delete("{$this->path}/{$project->image}");
            }

            $name = Kebab_case($request->name);

            $extension = $request->image->extension();

            $project->image = "{$name}.{$extension}";

            $data['image'] = $project->image;

            $upload = $request->image->storeAs($this->path, $project->image);

            if ( !$upload )
                return response()->json(['error' => 'fail_upload'], 500);
        }else {
            unset($data['image']);
        }

        if ( !$project->update($data) )
            return response()->json(['error' => 'project_not_update'], 500);
        
            return response()->json($project);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        if ( !$project = $this->project->find($id) )
            return response()->json(['error' => 'project_not_found'], 404);

            if ($project->image != null) {
                if (Storage::exists("{$this->path}/{$project->image}"))
                    Storage::delete("{$this->path}/$project->image}");
            }

            if ( !$project->delete() )
                return response()->json(['error' => 'project_not_delete'], 500);

                return response()->json($project, 204);
    }


}
