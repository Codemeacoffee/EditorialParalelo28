@extends('layout')
@section('header')
@section('content')

<!-- F A Q  P R E S E N T A T I O N -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div class="mb-5"><div class="ed-text" data-content="head"><?php echo $variableFields['head'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div><div class="ed-text" data-content="text-1"><?php echo $variableFields['text-1'] ?></div></div>
        </div>
    </div>
</div>

<!-- E N D   F A Q   P R E S E N T A T I O N -->


<!-- F A Q   F O R M -->

<div class="container-fluid mt-6">
    <div class="row mediaFAQ">
        <div class="col-lg-4 col-md-6 col-12 px-0 overlap leftToRight-topToBottom">
            <div class="bg-slateGrey-color rounded">
                <div class="py-4 mx-5"><div class="ed-text" data-content="title-1"><?php echo $variableFields['title-1'] ?></div></div>
                <form class="col-l1 offset-1" method="post" action="{{url('AskYourQuestion')}}" >
                    {{csrf_field()}}
                    <div class="pr-5 form-group">
                        <label for="name"><strong class="grey-color">Nombre*</strong></label>
                        <input id="name" type="text" class="form-control" name="name" required>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="email"><strong class="grey-color">Email*</strong></label>
                        <input id="email" type="email" class="form-control" name="email" required>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="question"><strong class="grey-color">Pregunta*</strong></label>
                        <textarea id="question" rows="4" class="form-control" name="question" required></textarea>
                        <button type="submit" class="btn btn-danger my-4 centerHorizontal px-4"><strong>Enviar</strong></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-12 px-4">
            <img class="w-100 h-75 ed-img" data-content="img-1" src="{{asset('images/uploads/'.$variableFields['img-1'])}}" alt="Imagen del apartado de preguntas y respuestas">
        </div>
    </div>
</div>

<!-- E N D   F A Q   F O R M -->

<!-- F A Q   A N S W E R S -->

<div class="container-fluid mt-6">
   <div id="faqQuestions" class="col-lg-8 offset-lg-2 col-12">
       <?php

       $faqTitles = [];
       $faqTexts = [];
       foreach ($variableFields as $key=>$value){
           $splitFieldKey = explode('-', $key);
           if($splitFieldKey[0] == 'faqTitle'){
               array_push($faqTitles, [$splitFieldKey[1],$value]);
           }else if($splitFieldKey[0] == 'faqText'){
               array_push($faqTexts, [$splitFieldKey[1], $value]);
           }
       }

       $i = 1;
      foreach ($faqTitles as $faqTitle){
          echo'<div class="pt-5">
            <div class="mb-4 d-flex"><strong class="pr-2 mt-2 noWrap">'.$i.' - </strong><div class="ed-text w-100 mx-2 py-2 px-3 font-weight-bold" data-prevent="quill" data-content="faqTitle-'.$faqTitle[0].'">'.$faqTitle[1].'</div></div>
            <div class="ml-5"><div class="ed-text py-2 px-3" data-prevent="quill" data-content="faqText-'.$faqTexts[$i-1][0].'">'.$faqTexts[$i-1][1].'</div></div>
            </div>';
          $i++;
      }

       ?>
   </div>
</div>

<!-- E N D   F A Q   A N S W E R S -->

@stop
@section('footer')
