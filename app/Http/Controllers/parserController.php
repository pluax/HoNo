<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Players;
use App\Models\Tours;
use Illuminate\Support\Facades\Auth;

class parserController extends Controller
{
 
    public function parser(Request $request){
      $input = $request->all();
      $tourId = $input['tourId'];
      $link = $input['link'];  
      $tour = Tours::where('id',$tourId)->first();
      $htmlCode = file_get_contents($link);
      $user =  explode(' ',Auth::user()->name);
      $name = $user[0];
      $family = $user[1];

      return view('parser.parserView', compact('htmlCode','tour','name','family'));
  }

    
    
}


