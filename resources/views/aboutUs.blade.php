@extends('layout')
@section('header')
@section('content')

<!-- A B O U T   U S   P R E S E N T A T I O N -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div><div class="mb-5 ed-text" data-content="head"><?php echo $variableFields['head'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div class="ed-text" data-content="text-1">
                <?php echo $variableFields['text-1'] ?>
            </div>
        </div>
    </div>
</div>

<!-- E N D   A B O U T   U S   P R E S E N T A T I O N -->


<!-- A B O U T   U S   I N F O -->

<div class="container-fluid mt-6 mediaAboutUs">
    <div class="row">
        <div class="col-lg-5 col-12 offset-lg-1 px-0">
           <img class="w-100 h-100 ed-img" data-content="img-1" src="{{asset('images/uploads/'.$variableFields['img-1'])}}" alt="Imagen del equipo de trabajo">
        </div>
        <div class="col-lg-5 col-12 px-4">
            <div>
                <div>
                    <div class="mb-4 ed-text" data-content="title-1"><?php echo $variableFields['title-1'] ?></div>
                </div>
                <div>
                    <div class="ed-text" data-content="text-2">
                        <?php echo $variableFields['text-2'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   A B O U T   U S   I N F O -->

<!-- A B O U T   U S   S T A T I S T I C   D A T A -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div class="mb-5"><div class="ed-text" data-content="title-2"><?php echo $variableFields['title-2'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div><div class="ed-text" data-content="text-3"><?php echo $variableFields['text-3'] ?></div></div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-3 offset-lg-1 col-md-8 offset-md-2 col-sm-8 offset-sm-2 col-12">
            <div class="grid-2-rows">
                <h1 class="grey-color bigText">
                    <div class="float-right ed-text w-100" data-prevent="quill" data-content="bigText-1">
                        <?php echo $variableFields['bigText-1'] ?>
                    </div>
                </h1>
                <div>
                    <div class="ed-text font-weight-bold" data-prevent="quill" data-content="subTitle-1"><?php echo $variableFields['subTitle-1'] ?></div>
                    <div class="ed-text" data-prevent="quill" data-content="subText-1"><?php echo $variableFields['subText-1'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 offset-lg-0 col-md-8 offset-md-2 col-sm-8 offset-sm-2 col-12">
            <div class="grid-2-rows mr-4">
                <h1 class="grey-color bigText">
                    <div class="float-right ed-text w-100 text-lg-center text-left" data-prevent="quill" data-content="bigText-2">
                        <?php echo $variableFields['bigText-2'] ?>
                    </div>
                </h1>
                <div>
                    <div class="ed-text font-weight-bold" data-prevent="quill" data-content="subTitle-2"><?php echo $variableFields['subTitle-2'] ?></div>
                    <div class="ed-text" data-prevent="quill" data-content="subText-2"><?php echo $variableFields['subText-2'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 offset-lg-0 col-md-8 offset-md-2 col-sm-8 offset-sm-2 col-12">
            <div class="grid-2-rows float-lg-right">
                <h1 class="grey-color bigText">
                    <div class="float-right ed-text w-100" data-prevent="quill" data-content="bigText-3">
                        <?php echo $variableFields['bigText-3'] ?>
                    </div>
                </h1>
                <div>
                    <div class="ed-text font-weight-bold" data-prevent="quill" data-content="subTitle-3"><?php echo $variableFields['subTitle-3'] ?></div>
                    <div class="ed-text" data-prevent="quill" data-content="subText-3"><?php echo $variableFields['subText-3'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   A B O U T   U S   S T A T I S T I C   D A T A -->

<!-- A B O U T   U S   C O M M E N T S -->

<div class="container-fluid mt-5 mediaAboutUs">
    <div class="row">
        <div class="col-lg-5 offset-lg-1 col-md-6 col-sm-6 col-12 bg-slateGrey-color position-relative px-0">
            <div class="absoluteCenterBoth w-75 h-50">
                <div class="mb-5"><div class="ed-text" data-content="title-3"><?php echo $variableFields['title-3'] ?></div></div>
                <div class="mb-5"><div class="ed-text" data-content="text-4"><?php echo $variableFields['text-4'] ?></div></div>
                <div><div class="ed-text float-right" data-content="subText-4"><?php echo $variableFields['subText-4'] ?></div></div>
            </div>
        </div>
        <div class="col-lg-5 col-md-6 col-sm-6 col-12 d-sm-inline-block d-none px-0">
            <img class="w-100 h-100 py-4 ed-img" data-content="img-2" src="{{asset('images/uploads/'.$variableFields['img-2'])}}" alt="Imagen del equipo de trabajo">
        </div>
    </div>
</div>


<!-- A B O U T   U S   C O M M E N T S -->

@stop
@section('footer')
