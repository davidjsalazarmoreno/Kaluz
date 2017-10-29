<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\Warehouse;
use Salesfly\Salesfly\Entities\Product;

class WarehouseRepo extends BaseRepo{
    public function getModel()
    {
        return new Warehouse;
    }

    public function search($q)
    {
        $warehouses =Warehouse::where('nombre','like', $q.'%')
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $warehouses;
    }
    public function searchWarehouse($q,$id)
    {
        $warehouses =Warehouse::select('id','nombre','shortname','capacidad')
                    ->where('nombre','like', $q.'%')->where('store_id','=',$id)
                    ->orWhere('store_id2','=',$id)
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $warehouses;
    }
    public function searchWere($q)
    {
        $warehouses =Warehouse::where('store_id','=',$q)
                    ->orWhere('store_id2','=',$q)
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $warehouses;
    }
    public function traertodosAlmacenes(){
        $warehouses =Warehouse::join('stores','stores.id','=','warehouses.store_id')
                       ->select('warehouses.*','stores.nombreTienda')->groupBy('warehouses.id')->get();
        return $warehouses;
    }
    public function traerAlmacenporUsuario(){
        $warehouses =Warehouse::join('stores','stores.id','=','warehouses.store_id')
                       ->join('users','users.store_id','=','stores.id')
                       ->where('users.id','=',auth()->user()->id)
                       ->select('warehouses.*')->groupBy('warehouses.id')->get();
        return $warehouses;
    }
    public function paginaterepo($c){


        /* $warehouses =Warehouse::join('stores','stores.id','=','warehouses.store_id')
                       ->join('users','users.store_id','=','stores.id')
                       ->where('users.id','=',auth()->user()->id)
                       ->select(\DB::raw('warehouses.id as idW,warehouses.*,stores.nombreTienda,(SELECT s.nombreTienda FROM warehouses w
                        inner join stores s on s.id=w.store_id2 where w.id=idW) as nombreTienda2 '))
                       ->paginate($c);
        return $warehouses; */

        /* $warehouses =Warehouse::join('stores','stores.id','=','warehouses.store_id')
            ->join('users','users.store_id','=','stores.id')
            ->where('users.id','=',auth()->user()->id)
            ->select(\DB::raw('*'))
            ->paginate($c);
        return $warehouses; */
        $warehouses = Warehouse::with('store')->paginate($c);
        return $warehouses;


        
    }
     public function listaAlmacenesTienda(){
        $warehouses =Warehouse::join('stores','stores.id','=','warehouses.store_id')
                       ->join('users','users.store_id','=','stores.id')
                       ->select('warehouses.*','stores.nombreTienda')->groupBy('warehouses.id')->get();
        return $warehouses;
    }

    public function getWarehousesByStoreProduct($productId){

        $product = Product::find($productId);
        $storeIdfromProduct = $product->store_id;
        $warehouses= Warehouse::where('store_id','=',$storeIdfromProduct)->get();
        return $warehouses;
    }
}