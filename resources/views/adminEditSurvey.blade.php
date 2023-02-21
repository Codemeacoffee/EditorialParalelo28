@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   E D I T   S U R V E Y -->

<div class="container-fluid mt-5 mediaSurvey">
    <h1 class="text-center"><strong>Encuesta de Satisfacción</strong></h1>

    <div class="container-fluid p-lg-3 p-0">
        <form method="post" action="{{url('adminEditSurvey')}}"><input id="surveyData" type="hidden" name="surveyData">
            {{csrf_field()}}
            <div class="row mt-6">
                <div class="col-lg-6 col-12">
                    <div class="row mb-5">
                        <div class="col-lg-6 col-12">
                            <h4 class="text-right"><strong>Editar</strong></h4>
                        </div>
                        <div class="col-lg-4 col-12">
                            <select id="editSurveyQuestionsType" class="custom-select">
                                <option value="#generalSurvey" selected>General</option>
                                <option value="#personalSurvey">Personal</option>
                            </select>
                        </div>
                    </div>
                    <div id="surveyQuestions">
                        <div id="generalSurvey">
                            <div class="sortable">
                                <?php
                                $id = 0;
                                foreach($data[0][0] as $surveyQuestion){
                                    echo '<div class="bg-white shadow mb-2">
                                    <div class="p-4 d-flex">
                                    <h4 id="'.$id.'" class="surveyQuestionEnum noWrap mt-1 pr-2">'.($id + 1).' -</h4>
                                    <input type="text" class="form-control surveyQuestion" placeholder="Pregunta" value="'.$surveyQuestion['question'].'" required>
                                    <div class="hasDropdown position-relative">
                                    <i title="Editar" id="editSurveyQuestion" class="glyphicon glyphicon-edit float-right hoverRed interactive mx-4 mt-2 pt-1"></i>
                                    <div class="showOnHover shadow px-4 pt-2">
                                    <p class="interactive '; if($surveyQuestion['type'] == 0) echo 'selectedSurveyType'; echo'" data-type="0">Respuesta libre (corta)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 1) echo 'selectedSurveyType'; echo'" data-type="1">Respuesta libre (media)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 2) echo 'selectedSurveyType'; echo'" data-type="2">Respuesta libre (larga)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 3) echo 'selectedSurveyType'; echo'" data-type="3">Seleccionar respuesta</p>
                                    </div>
                                    </div>
                                    <i title="Borrar" id="deleteSurveyQuestion" class="glyphicon glyphicon-remove float-right hoverRed interactive mt-2 pt-1"></i>
                                    </div>
                                    <div class="answersBox px-4 pb-1 '; if($surveyQuestion['type'] == 3) echo 'expand'; echo'">';

                                    foreach ($data[1] as $surveyPossibleAnswer){
                                        if($surveyQuestion['id'] == $surveyPossibleAnswer['surveyId']){
                                            echo '<div class="d-flex"><input type="text" class="form-control mt-3 mx-4 surveyPossibleAnswer" placeholder="Respuesta" value="'.$surveyPossibleAnswer['possibleAnswer'].'" required><i title="Borrar" id="deleteSurveyPossibleAnswer" class="glyphicon glyphicon-remove float-right hoverRed interactive mt-4 pt-1"></i></div>';
                                        }
                                    }

                                    echo'<i title="Añadir" id="addSurveyPossibleAnswer" class="centerHorizontal glyphicon glyphicon-plus rounded-circle interactive p-3 my-3"></i>
                                    </div>
                                    </div>';
                                    $id++;
                                };
                                ?>
                            </div>
                            <i title="Añadir" class="beforeWhite blueBoxShadowHover centerHorizontal glyphicon glyphicon-plus bg-blue-color rounded-circle interactive addSurveyQuestion p-3 mt-5"></i>
                        </div>
                        <div id="personalSurvey" class="d-none">
                            <p class="bg-slateGrey-color p-2">El carácter <strong>"*"</strong> simboliza el nombre de cada producto que el usuario compró</p>
                            <div class="sortable">
                                <?php

                                $index = 1;
                                foreach($data[0][1] as $surveyQuestion){
                                    echo '<div class="bg-white shadow mb-2">
                                    <div class="p-4 d-flex">
                                    <h4 id="'.$id.'" class="surveyQuestionEnum noWrap mt-1 pr-2">'.$index.' -</h4>
                                    <input type="text" class="form-control surveyQuestion" placeholder="Pregunta" value="'.$surveyQuestion['question'].'" required>
                                    <div class="hasDropdown position-relative">
                                    <i title="Editar" id="editSurveyQuestion" class="glyphicon glyphicon-edit float-right hoverRed interactive mx-4 mt-2 pt-1"></i>
                                    <div class="showOnHover shadow px-4 pt-2">
                                    <p class="interactive '; if($surveyQuestion['type'] == 0) echo 'selectedSurveyType'; echo'" data-type="0">Respuesta libre (corta)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 1) echo 'selectedSurveyType'; echo'" data-type="1">Respuesta libre (media)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 2) echo 'selectedSurveyType'; echo'" data-type="2">Respuesta libre (larga)</p>
                                    <p class="interactive '; if($surveyQuestion['type'] == 3) echo 'selectedSurveyType'; echo'" data-type="3">Seleccionar respuesta</p>
                                    </div>
                                    </div>
                                    <i title="Borrar" id="deleteSurveyQuestion" class="glyphicon glyphicon-remove float-right hoverRed interactive mt-2 pt-1"></i>
                                    </div>
                                    <div class="answersBox px-4 pb-1 '; if($surveyQuestion['type'] == 3) echo 'expand'; echo'">';

                                    foreach ($data[1] as $surveyPossibleAnswer){
                                        if($surveyQuestion['id'] == $surveyPossibleAnswer['surveyId']){
                                            echo '<div class="d-flex"><input type="text" class="form-control mt-3 mx-4 surveyPossibleAnswer" placeholder="Respuesta" value="'.$surveyPossibleAnswer['possibleAnswer'].'" required><i title="Borrar" id="deleteSurveyPossibleAnswer" class="glyphicon glyphicon-remove float-right hoverRed interactive mt-4 pt-1"></i></div>';
                                        }
                                    }

                                    echo'<i title="Añadir" id="addSurveyPossibleAnswer" class="centerHorizontal glyphicon glyphicon-plus rounded-circle interactive p-3 my-3"></i>
                                    </div>
                                    </div>';
                                    $id++;
                                    $index++;
                                };
                                ?>
                            </div>
                            <i title="Añadir" class="beforeWhite blueBoxShadowHover centerHorizontal glyphicon glyphicon-plus bg-blue-color rounded-circle interactive addSurveyQuestion p-3 mt-5"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 col-12 offset-0">
                    <div class="row mb-5">
                    <div class="col-lg-6 col-12">
                        <h4 class="text-right mb-4"><strong>Resultados</strong></h4>
                    </div>
                    <div class="col-lg-4 col-12">
                        <select id="editSurveyAnswersType" class="custom-select">
                            <option value="#genericSurveyStatistics" selected>Generales</option>
                            <option value="#personalSurveyStatistics">Personales</option>
                            <option value="#historicSurveyStatistics">Historicos</option>
                        </select>
                    </div>
                </div>
                    <div id="surveyStatistics">
                        <div id="genericSurveyStatistics">
                            <?php

                            $i = 1;
                            foreach ($data[2][0] as $question => $innerData){
                                echo '<div class="d-flex"><div class="row w-100"><div class="col-12"><h6 class="interactive mb-1">'.$question.'</h6></div></div></div>';
                                echo '<canvas class="my-5" id="surveyStatistics'.$i.'"></canvas>';
                                $i++;
                            }

                            ?>
                        </div>
                        <div id="personalSurveyStatistics" class="visuallyHidden">
                            <?php

                            foreach ($data[2][1] as $question => $innerData){
                                echo '<div class="d-flex"><div class="row w-100"><div class="col-12"><h6 class="interactive mb-1">'.$question.'</h6></div></div></div>';
                                echo '<canvas class="my-5" id="surveyStatistics'.$i.'"></canvas>';
                                $i++;
                            }

                            ?>
                        </div>
                        <div id="historicSurveyStatistics" class="visuallyHidden">
                            <?php

                            foreach ($data[2][2] as $question => $innerData){
                                echo '<div class="d-flex"><div class="row w-100"><div class="col-12"><h6 class="interactive mb-1">'.$question.'</h6></div></div></div>';
                                echo '<canvas class="my-5" id="surveyStatistics'.$i.'"></canvas>';
                                $i++;
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="w-100 pr-5 my-5">
                    <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('controlPanel')}}">Volver</a></u></strong>
                    <button type="submit" class="btn btn-danger float-right">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- E N D   A D M I N   E D I T   S U R V E Y  -->

<script type="text/javascript" src="{{'js/survey.min.js'}}"></script>
<script type="text/javascript" id="surveyScript">
    $(window).on('load', function () {
        let bgColors = [
            '#fdb94e',
            '#f9a852',
            '#f69653',
            '#f38654',
            '#f07654',
            '#ed6856',
            '#ef5956',
            '#f44336',
            '#d32f2f',
            '#b71c1c'
        ];
    <?php

    $i = 1;
    foreach (array_merge($data[2][0], $data[2][1], $data[2][2]) as $question => $innerData){
        echo '
        let data'.$i.' = [];
        let labels'.$i.' = [];';
        foreach ($innerData as $answer => $amount){
            echo 'labels'.$i.'.push("'.$answer.'");';
            echo 'data'.$i.'.push("'.$amount.'");';
        }
        echo "new Chart($('#surveyStatistics".$i."'), {
        type: 'pie',
        data: {
            labels: labels".$i.",
            datasets: [{
                data: data".$i.",
                backgroundColor: bgColors,
            }]
        },
        options: {
            legend: {
                position: 'right',
            },
            hover: {mode: null}
        }
    });
    ";
        $i++;
    }

    ?>

    $( "#genericSurveyStatistics" ).accordion();
    $( "#personalSurveyStatistics" ).accordion().addClass('d-none');
    $( "#historicSurveyStatistics" ).accordion().addClass('d-none');
    $('#surveyScript').remove();
    });
</script>

@stop
@section('footer')
