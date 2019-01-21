<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'type', 'image', 'type_document', 'document', 'email', 'tel_ddd', 'tel_number'];

   	//Relation or Projects
   	public function projects(){
   		return $this->hasMany(Project::class, 'client_id');
   	}

   	public function getResults(Array $data, int $totalPage)
   	{
   		//Se não existir filtro (name, type ou filter) retorna todos os resultados, paginados
   		if (!isset($data['name']) && !isset($data['type']) && !isset($data['filter']))
   			return $this->orderBy('id', 'DESC')->paginate($totalPage);

   		//Traz os resultados filtrados 
   		return $this->where(function ($query) use ($data) {
   			if (isset($data['name'])) {
   				$name = $data['name'];
   				$query->where('name', 'LIKE', "%{$name}%");
   			}

   			if (isset($data['type'])) {
   				$type = $data['type'];
   				$query->where('type', 'LIKE', "%{$type}%");
   			}

   			if (isset($data['filter'])) {
   				$filter = $data['filter'];

   				$query->where('name', 'LIKE', "%{$filter}%")
   						->orWhere('type', 'LIKE', "%{$filter}%");
   			}
   		})
   		->orderBy('id', 'DESC')
   		->paginate($totalPage);// ->toSel();
   	}
}
