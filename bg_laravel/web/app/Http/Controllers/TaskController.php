<?php


namespace App\Http\Controllers;


use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskController extends  Controller
{
    public function index(){
        return Task::query()
            ->where('username', Session::get('username'))
            ->orderBy(Session::get('order', 'id'), Session::get('direction', 'desc'))
            ->get();
    }

    public function  edit(Request $request){
        Session::put('username', $request->username);
        Session::put('order', $request->order);
        Session::put('direction', $request->direction);
        Session::save();
        return "Done";
    }

    public function create(Request $request){
        $conf = array('name' => $request->name , 'age' => $request->age , 'gender' => $request->gender);
        $config = json_encode($conf);
        $conf = new Task;
        $conf->username = Session::get('username');
        $conf->data = $config;
        $conf->save();
        return "Done";
    }

}
