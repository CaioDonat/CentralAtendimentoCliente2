<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreAtendimentoRequest;
use App\Http\Requests\UpdateAtendimentoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atendimento as Atendimento;
use DB;
use Carbon\Carbon;

class AtendimentoController extends Controller
{
    public function index(){
        $atendimentos = Atendimento::all();
        return json_encode($atendimentos, JSON_PRETTY_PRINT);
    }

    public function get($id_atendimento){
        $atendimento = Atendimento::findOrFail($id_atendimento);
        return json_encode($atendimento, JSON_PRETTY_PRINT);
    }

    public function store(StoreAtendimentoRequest $request){
        //
        $current = Carbon::now('-03:00');
        try {
            $cpf = $request->input("cpf");
        } catch (Throwable $th) {
            $cpf = null;
        }

        $sufixo_atendimento = $request->input("sufixo_atendimento");
        $observacoes = $request->input("observacoes");

        //inicio
        if($request->input("date_time_emissao_atendimento")!=null){
            $date_time = Carbon::create($request->input("date_time_emissao_atendimento"));
            $date_time_emissao_atendimento = $date_time->toDateTimeString();
            $date_emissao_atendimento = $date_time->toDateString();
            //fim() |->reservado para teste
        }else{
            $date_time_emissao_atendimento = $current->toDateTimeString();
            $date_emissao_atendimento = $current->toDateString();
        }
        

        //Gerando um novo registro
        $atendimento = new Atendimento();

        //cpf
        $atendimento->cpf = $cpf;

        //numero_atendimento
        $today = $current->toDateString();
        $lastAtendimento = Atendimento::all()
        ->where("date_emissao_atendimento", $today)
        ->last();

        if ($lastAtendimento!=null){
            $atendimento->numero_atendimento = $lastAtendimento->numero_atendimento+1;
        }else{
            $atendimento->numero_atendimento = 1;
        }
        

        //sufixo_atendimento
        if($sufixo_atendimento!=null){
            $atendimento->sufixo_atendimento = $sufixo_atendimento;
        }else{
            $atendimento->sufixo_atendimento = "OTS";
        }
        
        if ($observacoes!=null){
            $atendimento->observacoes = $observacoes;
        }else{
            $atendimento->observacoes = "Sem Observações";
        }

        //date_emissao_atendimento
        $atendimento->date_emissao_atendimento = $date_emissao_atendimento;

        //date_time_emissao_atendimento
        $atendimento->date_time_emissao_atendimento = $date_time_emissao_atendimento;

        //verificando se foi possivel registrar
        if ($atendimento->save()){
            return $atendimento->toJson(JSON_PRETTY_PRINT);
        }
        return json_encode(["erro"=>true]);
    }

    public function atendimentosDate($date){
        $dateRequest = Carbon::create($date);
        $atendimentos = Atendimento::where("date_emissao_atendimento", $dateRequest->toDateString())->get();
        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }

    public function atendimentosFromToV($from, $to){
        $fromR = Carbon::create($from);
        $toR = Carbon::create($to);
        $atendimentos = Atendimento::
        whereBetween("date_emissao_atendimento", [$fromR->toDateString(), $toR->toDateString()])
        ->get();
        return $atendimentos;
    }

    public function atendimentosFromTo($from, $to){
        $atendimentos = AtendimentoController::atendimentosFromToV($from, $to);

        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }

    public function atendimentosMonth($month){
        $fistDayOfMonth = Carbon::create($month)->startOfMonth()->toDateString();
        $lastDayOfMonth = Carbon::create($month)->endOfMonth()->toDateString();
        $atendimentos = AtendimentoController::atendimentosFromToV($fistDayOfMonth, $lastDayOfMonth);

        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }

    public function atendimentosQueueToday(){
        $carbonNow = Carbon::now('-03:00');

        $atendimentos = Atendimento::
        where("date_emissao_atendimento", $carbonNow->toDateString())
        ->where("inicio_atendimento", "=", null)
        ->get();

        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }

    public function atendimentosQueueTodayNext(){
        $carbonNow = Carbon::now('-03:00');
        $atendimentos = Atendimento::
        where("date_emissao_atendimento", $carbonNow->toDateString())
        ->where("inicio_atendimento", "=", null)
        ->get()->first();

        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }
}
