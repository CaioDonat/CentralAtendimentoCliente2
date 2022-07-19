<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreAtendimentoRequest;
use App\Http\Requests\UpdateAtendimentoRequest;
use App\Http\Controllers\Controller;

use App\Models\Atendimento as Atendimento;
use App\Models\LoginGuiche;
use App\Models\Guiche;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AtendimentoController extends Controller
{
    //POST

    public function createAtendimento(StoreAtendimentoRequest $request)
    {
        //
        $current = Carbon::now('-03:00');
        try {
            $cpf = $request->input("cpf");
        } catch (\Throwable $th) {
            $cpf = null;
        }

        $sufixo_atendimento = $request->input("sufixo_atendimento");
        $observacoes = $request->input("observacoes");

        //inicio
        if ($request->input("date_time_emissao_atendimento") != null) {
            $date_time = Carbon::create($request->input("date_time_emissao_atendimento"));

            $date_time_emissao_atendimento = $date_time->toDateTimeString();
            $date_emissao_atendimento = $date_time->toDateString();
            //fim() |->reservado para teste
        } else {
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

        if ($lastAtendimento != null) {
            $atendimento->numero_atendimento = $lastAtendimento->numero_atendimento + 1;
        } else {
            $atendimento->numero_atendimento = 1;
        }


        //sufixo_atendimento
        if ($sufixo_atendimento != null) {
            $atendimento->sufixo_atendimento = $sufixo_atendimento;
        } else {
            $atendimento->sufixo_atendimento = "OTS";
        }

        if ($observacoes != null) {
            $atendimento->observacoes = $observacoes;
        } else {
            $atendimento->observacoes = "Sem Observações";
        }

        //date_emissao_atendimento
        $atendimento->date_emissao_atendimento = $date_emissao_atendimento;

        //date_time_emissao_atendimento
        $atendimento->date_time_emissao_atendimento = $date_time_emissao_atendimento;

        //verificando se foi possivel registrar
        if ($atendimento->save()) {
            return $atendimento->toJson(JSON_PRETTY_PRINT);
        }
        return json_encode(["erro" => true]);
    }

    //GET

    public function all()
    {
        try {
            $r = DB::table('tb_atendimentos')
            ->get();

            return json_encode(['r'=>$r, 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    //retorna um atendimento expecificado por ID
    public function id($id_atendimento)
    {
        try {
            $r = DB::table('tb_atendimentos')
            ->where('id_atendimento', $id_atendimento)
            ->get();

            return json_encode(['r'=>$r, 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    public function day($date)
    {
        try {
            $dateRequest = Carbon::create($date)->toDateString();

            $r = DB::table('tb_atendimentos')
            ->where("date_emissao_atendimento", $dateRequest)
            ->get();

            return json_encode(['r'=>$r, 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    public function daysFirstLast($first, $last)
    {
        try {
        $f = Carbon::create($first)->toDateString();
        $l = Carbon::create($last)->toDateString();

        $r = DB::table('tb_atendimentos')
        ->whereBetween('date_emissao_atendimento', [$f, $l])
        ->get();


            return json_encode(['r'=>$r, 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    public function monthMonth($month)
    {
        try {
            $f = Carbon::create($month)->startOfMonth()->toDateString();
            $l = Carbon::create($month)->endOfMonth()->toDateString();
            
            $r = $this->daysFirstLast($f, $l);

            return $r;
        } catch (\Throwable $th) {
            return $r;
        }
    }

    public function queueToday()
    {
        try {
            $cNow = Carbon::now('-03:00')->toDateString();
            
            $r = DB::table('tb_atendimentos')
            ->where('date_emissao_atendimento', $cNow)
            ->where("started", null)
            ->get();


            return json_encode(['r'=>$r, 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    public function queueTodayNext()
    {
        try {
            $cNow = Carbon::now('-03:00')->toDateString();
            
            $r = DB::table('tb_atendimentos')
            ->where('date_emissao_atendimento', $cNow)
            ->where("started", null)
            ->get()->first();


            return json_encode(['r'=>[$r], 'success'=>true], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            return json_encode(['r'=>$th, 'success'=>false], JSON_PRETTY_PRINT);
        }
    }

    public function atendimentosAfterQueueToday()
    {
        $carbonNow = Carbon::now('-03:00');

        $atendimentos = Atendimento::where("date_emissao_atendimento", $carbonNow->toDateString())
            ->where("inicio_atendimento", "!=", null)
            ->get();

        return $atendimentos->toJson(JSON_PRETTY_PRINT);
    }

    public function atendimentoTodayNumber($numero_atendimento)
    {
        $carbonNow = Carbon::now('-03:00');

        $atendimento = Atendimento::where("date_emissao_atendimento", $carbonNow->toDateString())
            ->where("numero_atendimento", "=", $numero_atendimento)
            ->get();

        return $atendimento->toJson(JSON_PRETTY_PRINT);
    }

    public function ToCall()
    {
        //metodo utilizado pelo telao para verificar quem ele deve chamar
        $carbonNow = Carbon::now('-03:00');
        $atendimentos = Atendimento::where('date_emissao_atendimento', $carbonNow->toDateString())
            ->where('status_atendimento', "==", 'chamando')
            ->get();

        return json_encode($atendimento, JSON_PRETTY_PRINT);
    }


    //UPDATE

    public function atendimentoBegin($id_atendimento)
    { //, $guiche
        $carbonNow = Carbon::now('-03:00');
        Atendimento::where("id_atendimento", "=", $id_atendimento)
            ->update(['inicio_atendimento' => $carbonNow->toDateTimeString()]);

        //$atendimento = Atendimento::where("id_atendimento", "=", $id_atendimento);//aparentemente não é a mesma coisa
        $atendimento = Atendimento::findOrFail($id_atendimento);

        return json_encode($atendimento, JSON_PRETTY_PRINT);
    }

    public function atendimentoFinish($id_atendimento, $estado_fim_atendimento)
    { //, $guiche
        $carbonNow = Carbon::now('-03:00');
        Atendimento::where("id_atendimento", "=", $id_atendimento)
            ->update(['fim_atendimento' => $carbonNow
                ->toDateTimeString()])
            ->update(['estado_fim_atendimento' => $estado_fim_atendimento]);

        $atendimento = Atendimento::findOrFail($id_atendimento);

        return json_encode($atendimento, JSON_PRETTY_PRINT);
    }

    public function call($id_atendimento)
    {
        //adiciona esse atendimento ($id_atendimento) a uma lista que sera chamada pelo telão, e o telao ficarar verificando (com frequencia) se possui atualizações nessa fila

        $carbonNow = Carbon::now('-03:00');

        DB::table('tb_atendimentos')
        ->where('id_atendimento', '=', $id_atendimento)
        ->update(['status_atendimento' => 'chamando',
         'first_call' => $carbonNow->toDateTimeString()]);

        $atendimento = Atendimento::findOrFail($id_atendimento);

        return json_encode($atendimento, JSON_PRETTY_PRINT);
    }

    public function callNext()
    {
        //guiche nao pode utilizar essa rota se ele estiver em atendimento
        //adiciona esse atendimento ($id_atendimento) a uma lista que sera chamada pelo telão, e o telao ficarar verificando (com frequencia) se possui atualizações nessa fila
        //2 guiches nao podem chamar a mesma senha
        
        $carbonNow = Carbon::now('-03:00');

        try {
            $id_atendimento = DB::table('tb_atendimentos')
            ->where('date_emissao_atendimento', '=', $carbonNow->toDateString())
            ->value('id_atendimento');
            
            DB::table('tb_atendimentos')
            ->where('id_atendimento', $id_atendimento)
            ->update(['status_atendimento' => 'chamando',
            'first_call' => $carbonNow->toDateTimeString()]);
            
            $atendimento = Atendimento::findOrFail($id_atendimento);

            return json_encode($atendimento, JSON_PRETTY_PRINT);

        } catch (\Exception $th) {
            return json_encode(["fila_vazia"=>true]);
        }
    }

    public function toCallNext()
    {   /*
        *   metodo utilizado pelo telao para verificar qual senha deve ser chamada
        */

        $carbonNow = Carbon::now('-03:00');

        $id_atendimento = Atendimento::where("date_emissao_atendimento", $carbonNow
        ->toDateString())
        ->where("inicio_atendimento", "=", null)
        ->get()->first()->value('id_atendimento');
        
        $atendimento = Atendimento::
          where('date_emissao_atendimento', $carbonNow->toDateString())
        ->where('status_atendimento', "=", 'chamando')
        ->get()->first();
        if($atendimento != null){
            Atendimento::where("id_atendimento", "=", $atendimento->id_atendimento)
            ->update(['status_atendimento' => 'aguardando']);

        try{
            $id_atendimento_next = DB::table('tb_atendimentos')
            ->where('date_emissao_atendimento', $carbonNow->toDateString())
            ->where('status_atendimento', 'chamando')
            ->value('id_atendimento');

            $atendimento = Atendimento::findOrFail($id_atendimento_next);

            $atendimento->status_atendimento = "aguardando";

            if($atendimento->save()){
                return json_encode($atendimento, JSON_PRETTY_PRINT);
            }
        }catch(\Exception $e){
            return json_encode(["fila_vazia"=>true]);
        }
        }
    }
}
