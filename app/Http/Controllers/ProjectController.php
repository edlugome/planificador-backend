<?php

namespace App\Http\Controllers;
use App\Project;
use App\Task;
use App\Member;
use App\Objective;
use App\Project_member;
use App\Chat;
use App\Message;
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
            $project = Project::create([
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
               $tareac= Task::create([
                   'task' => $tarea['nombre'],
                   'manager' => $tarea['encargado'],
                   'obj_id' => $ob['id'],
                   'id_proyecto' => $project['id']
               ]);
            }

         }

         $miembros = $request ['miembros'];
            foreach($miembros as $miembro)
            {
               $m= Project_member::create([
                   'id_miembro' => $miembro['id'],
                   'id_proyecto' => $project['id'],
               ]);
            }
        Chat::create([
            'proyecto' =>$project['id']
        ]);
         
        
            return response()->json($project, 201);   

        }

        

    }
    

    function GuardarMensaje(Request $request)
    {
        Message::create([
            'message' =>$request['message'],
            'chat' =>$request['chat_id']
        ]);

        return response()->json('ok',201);
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

    function verTareas($id)
    {
        $tareas=Task::where('obj_id', $id)->get();
        return response()->json($tareas);
    }

    function actualizarPorcentajeObjetivo(Request $request)
    {
        
    }

    function completarTarea(Request $request)
    {
        $tarea=$request;
        if($tarea['complete']==0)
        $tareaNueva=Task::where('id',$tarea['id'])->update(['complete'=>1]);
        else
        $tareaNueva=Task::where('id',$tarea['id'])->update(['complete'=>0]);

        $id_objetivo=$tarea['obj_id'];
        $cantidadDeTareas=0;
        $cantidadTareaCompletas=0;
        $porcentajeDeObjetivo=0;
        $tareas=Task::where('obj_id',$id_objetivo)->get();

        $id_proyecto=$tarea['id_proyecto'];
        $cantidadTareasTotales=0;
        $tareasTotales=Task::where('id_proyecto', $id_proyecto)->get();
        $porcentajeProyecto=0;
        $cantidadTareasCompletadas=0;

          foreach($tareasTotales as $tarea)
            {
                $cantidadTareasTotales++;
                if($tarea['complete']==1)
                {
                    $cantidadTareasCompletadas++;
                }
            }

            if($cantidadTareasTotales>0)
            {
                $porcentajeProyecto=($cantidadTareasCompletadas /$cantidadTareasTotales)*(100);
            }


            $project = Project::where('id',$id_proyecto)->update(['percentage' => $porcentajeProyecto]);

            ///////////////////////
        
        foreach($tareas as $tarea)
        {
            $cantidadDeTareas++;
            if($tarea['complete']==1)
            {
                $cantidadTareaCompletas++;
                
            }
        }

        if($cantidadTareaCompletas>0)
        {
            $porcentajeDeObjetivo=($cantidadTareaCompletas/$cantidadDeTareas)*(100);
        }


        $objetivos=Objective::where('id', $id_objetivo)->update(['percentage'=>$porcentajeDeObjetivo]);

        return response()->json(Task::where('obj_id', $id_objetivo)->get());
    }

    function infoProyecto($id)
    {
        $proyecto=Project::where('id',$id)->first();
        return response()->json($proyecto);
    }


   
   /* function calcularPorcentaje(Request $request)//Calcular porcentaje completado del proyecto
    {
        $cantidadTareas=0;
        $catidadTareaCompletas=0;
        $porcentajeTarea=0;

        $cantidaObjetivoCompletos=0;
        $cantidadObjetivos=0;
        $porcentajeObjetivo=array();
        

        $objetivos=Objective::where('id_proyecto', $id_proyecto)->get();
        
        foreach($objetivos as $objetivo)
        {
            $cantidadObjetivos++;
            if($objetivo['complete']==1)
            {
                $cantidaObjetivoCompletos++;
                
            }

                if($cantidaObjetivoCompletos>0)
            {
                $porcentajeObjetivo=($cantidaObjetivoCompletos/$cantidadObjetivos)+(100);
            }
        }

        if($request->isJson())
        {
            $project = Task::update([

                'complete' => 1
            ])->where ('id',$request['id']);

            $tareas=Task::where('id_proyecto', $id_proyecto)->get();

            foreach($tareas as $tarea)
            {
                $cantidadTareas++;
                if($tarea['complete']==1)
                {
                    $catidadCompletas++;
                }
            }
    
    
    
            if($catidadTareaCompletas>0)
            {
                $porcentajeTarea=($catidadTareaCompletas /$cantidadTareas)*(100);
            }


            $project = Project::update
            ([
                'percentage' => $porcentajeTarea
                
            ])->where('id',$id_proyecto);


      // return response()->json(array('porcentajeProyecto'=>$porcentajeTarea.'%', 'porcentajeTarea'=>$porcentajeObjetivo ));
        }*/



}
