<?php

namespace App\Http\Controllers;

use App\Etudiant;
use App\Models\Cours;

use App\Models\UserToken;
use App\Models\Cours_Suivis;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\StudentCoursesRessource;

class EtudiantController extends Controller
{
    public static function store($user_id)
    {
        Etudiant::create(['user_id' => $user_id]);
    }
    public function subscribe(Request $request)
    {
        $playload = UserToken::userPlaylod();

        if( Etudiant::isStudent($playload['statut'])) {

            $student = Etudiant::selectStudent($playload['id']);
            $cours_suivis = Cours_Suivis::create([
                'etudiant_id' => $student->id,
                'cours_id' => $request->get('cours_id'),
            ]);

            return response()->json(['success'],201); 
        }

        return response()->json(['error' => 'user_is_not_student'],404);
    }
    public function unsubscribe($cours_id)
    {
        $playload = UserToken::userPlaylod();
       
        $student = Etudiant::selectStudent($playload['id']);

        $cours_suivis = Cours_Suivis::where('etudiant_id', $student->id)
          ->where('cours_id', $cours_id)
          ->first();

        $cours_suivis->delete();

        return response()->json(['success' => 'cours_is_deleted'],201);
    }
    public function myCourses(Request $request)
    {
        $playload = UserToken::userPlaylod();
        if(Etudiant::isStudent($playload['statut'])) {

            $student = Etudiant::selectStudent($playload['id']);

            $cours_suivis = Etudiant::Courses($student->id);
        
            $courses_id = $cours_suivis->pluck('cours_id');
            
            $courses = Cours::find($courses_id);

            return  response()->json(['Courses' => $courses, 'Cours_suivis' => $cours_suivis]);
        }
        else
        return response()->json(['error' => 'user_is_not_student'],404);
      
    }
    public function myCoursesIds(Request $request)
    {
        $playload = UserToken::userPlaylod();
        if(Etudiant::isStudent($playload['statut'])) {

            $student = Etudiant::selectStudent($playload['id']);

            $cours_suivis_ids = Etudiant::Courses($student->id)->pluck('id');
            
            $cours_suivis = Cours_Suivis::find( $cours_suivis_ids);
           
            $courses_ids = $cours_suivis->pluck('cours_id');
            
            return  response()->json(['Courses_id' => $courses_ids]);
            
        }
        else
        return response()->json(['error' => 'user_is_not_student'],404);
    }
    
}