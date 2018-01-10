<?php

namespace App\Http\Controllers;
use App\Project;
use App\Task;
use App\Member;
use App\Objective;
use App\Project_member;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    function RegistrarProyecto(Request $request)
    {
        if($request->isJson())
        {
            $project = Project::create
            ([
                'name' => $request['nombre'],
                'description' => $request['descripcion'],
                'benefitsC' => $request['beneficiosC'],
                'benefitsOL' => $request['beneficiosOL'],
                'factibility' => $request['estudio'],
                'leader' => $request['leader'],
                'budget'  => $request['presupuesto'],
                'funding_notes' => $request['notas']
            ]);

         $objetivos = $request ['objectives'];

         foreach($objetivos as $objetivo)
         {
            $ob = Objective::create
            ([
                'objective' => $objetivo['name'],
                'project_id' => $project['id']
            ]);
            $tareas = $objetivo ['tasks'];

            foreach($tareas as $tarea)
            {
               $tareac= Task::create
               ([
                   'task' => $tarea['nombre'],
                   'manager' => $tarea['encargado'],
                   'obj_id' => $ob['id']
               ]);
            }

         }

         $miembros = $request ['miembros'];
            foreach($miembros as $miembro)
            {
               $m= Project_member::create
               ([
                   'id_miembro' => $miembro['id'],
                   'id_proyecto' => $project['id'],
               ]);
            }

         
            
            return response()->json($project, 201);   

        }

    }
    
    function verproyectos($id)
    {
        $projects=Project::join('project_members', 'project_members.id_proyecto', '=', 'projects.id' )->
        where('project_members.id_miembro', '=', $id)->get();
        return response()->json($projects);
    }

    function verEncargados($id)
    {
        //Devuelve los encargador de un proyecto específico
        $project_members=Member::join('project_members', 'project_members.id_miembro', '=', 'members.id')->
        where('project_members.id_proyecto', $id)->get();
        return response()->json($project_members);
    }

    function verObjetivos($id)
    {
        $objetivos=Objective::where('project_id', $id)->get();
        return response()->json($objetivos);
    }
   

}
