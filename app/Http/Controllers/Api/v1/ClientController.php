<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Client;
use App\Models\Project;
//use App\Invoice;
//use App\HistoryInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateClientValidate;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    private $project_controller;
    private $client;
    private $totalPage = 4;
    private $path = 'clients'; 

    /**
     * DI of \App\Models\Client
     *
     * @param  \App\Models\Client $client
     */
    public function __construct(Client $client){
    	$this->client = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON
     */
    public function index(Request $request){
    	$client = $this->client->getResults($request->all(), $this->totalPage);

    	return response()->json($client);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUpdateClientValidate $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateClientValidate $request)
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

        if (!$client = $this->client->create($data) )
            return response()->json(['error' => 'error_insert'], 500);

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ( !$client = $this->client->find($id) )
            return response()->json(['error' => 'client_not_found'], 404);

        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreUpdateClientValidate $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateClientValidate $request, $id)
    {
        $data = $request->all();

        if ( !$client = $this->client->find($id) )
            return response()->json(['error' => 'client_not_found'], 404);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($client->image != null) {
                if (Storage::exists("{$this->path}/{$client->image}"))
                    Storage::delete("{$this->path}/{$client->image}");
            }

            $name = Kebab_case($request->name);

            $extension = $request->image->extension();

            $client->image = "{$name}.{$extension}";

            $data['image'] = $client->image;

            $upload = $request->image->storeAs($this->path, $client->image);

            if ( !$upload )
                return response()->json(['error' => 'fail_upload'], 500);
        }else {
            unset($data['image']);
        }

        if ( !$client->update($data) )
            return response()->json(['error' => 'client_not_update'], 500);
        
            return response()->json($client);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        if ( !$client = $this->client->find($id) )
            return response()->json(['error' => 'client_not_found'], 404);

            if ($client->image != null) {
                if (Storage::exists("{$this->path}/{$client->image}"))
                    Storage::delete("{$this->path}/$client->image}");
            }

            if ( !$client->delete() )
                return response()->json(['error' => 'client_not_delete'], 500);

                return response()->json($client, 204);
    }


}
