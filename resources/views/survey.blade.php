@extends('layout')
@section('header')
@section('content')

<!-- S U R V E Y -->

<div class="container-fluid mt-5">
    <h1 class="text-center mb-5"><strong>Encuesta de Satisfacción</strong></h1>
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">
            <?php

                if(Count($data[0]) > 0){
                    $index = 1;

                    echo '<form method="post" class="w-100" action="'.url('takeASurvey').'">'.csrf_field();

                    foreach ($data[0] as $question){
                        echo '<div class=" mb-5">
                                <div class="row mb-3">
                                    <div class="col-1 noWrap"><h4>'.$index.' - </h4></div>
                                    <div class="col-10">
                                        <h5>'.$question['question'].'</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10 offset-1">';
                                    switch ($question['type']){
                                        case 0:
                                            echo '<textarea rows="1" maxlength="60" class="form-control" name="answers['.$question['id'].'][answer]" placeholder="Escriba su respuesta aquí"></textarea>';
                                            break;
                                        case 1:
                                            echo '<textarea rows="2" maxlength="120" class="form-control" name="answers['.$question['id'].'][answer]" placeholder="Escriba su respuesta aquí"></textarea>';
                                            break;
                                        case 2:
                                            echo '<textarea rows="4" maxlength="240" class="form-control" name="answers['.$question['id'].'][answer]" placeholder="Escriba su respuesta aquí"></textarea>';
                                            break;
                                        default:
                                            $subIndex = 1;
                                           foreach ($data[1] as $possibleAnswers){
                                               if($question['id'] == $possibleAnswers['surveyId']){
                                                   echo'<div class="form-check">
                                                            <input class="form-check-input " type="radio" name="answers['.$question['id'].'][answer]" id="'.$index.'-'.$subIndex.'" value="'.$possibleAnswers['possibleAnswer'].'">
                                                            <label class="form-check-label" for="'.$index.'-'.$subIndex.'">'.$possibleAnswers['possibleAnswer'].'</label>
                                                        </div>';
                                               }
                                               $subIndex++;
                                           }
                                    }
                             echo'<input type="hidden" name="answers['.$question['id'].'][question]" value="'.$question['question'].'">
                                </div>
                                </div>
                                </div>';
                        $index++;
                    }

                    echo '<button type="submit" class="btn btn-danger centerHorizontal mt-5 mr-5"><strong>Enviar</strong></button></form>';
                }else{
                    echo '<img alt="Icono informando de que ya ha completado esta encuesta." class="w-50 centerHorizontal" src="'.asset('images/surveyCompleated.png').'">
                          <h5 class="text-center mb-5">¡Muchas gracias por completar nuestra encuesta de satisfacción!</h5>
                          <h5 class="text-center mb-5">Su opinión es realmente importante para nosotros.</h5>
                          <a href="'.url('/').'" class="btn btn-danger centerHorizontal mt-5 mr-5"><strong>Volver</strong></a>';
                }
            ?>
        </div>
    </div>
</div>

<!-- E N D   S U R V E Y  -->

@stop
@section('footer')

