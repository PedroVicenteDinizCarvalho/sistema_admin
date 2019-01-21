<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'type', 'image', 'price', 'plots','paid_installments', 'deadline', 'client'];

    protected $table = 'projects'; 

    //Relation or Clientes
    public function clients(){
    	return $this->belongsTo(Client::class, 'client');
    }

    public function getResults(Array $data, int $totalPage)
   	{
   		//Se não existir filtro (name, type ou filter) retorna todos os resultados, paginados
   		if (!isset($data['name']) && !isset($data['description']) && !isset($data['filter']))
   			return $this->orderBy('id', 'DESC')->paginate($totalPage);

   		//Traz os resultados filtrados 
   		return $this->where(function ($query) use ($data) {
   			if (isset($data['name'])) {
   				$name = $data['name'];
   				$query->where('name', 'LIKE', "%{$name}%");
   			}

   			if (isset($data['description'])) {
   				$type = $data['description'];
   				$query->where('description', 'LIKE', "%{$description}%");
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


