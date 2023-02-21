@extends('layout')
@section('header')
@section('content')

<!-- W O R K   W I T H   U S   P R E S E N T A T I O N -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div class="mb-5"><div class="ed-text" data-content="head"><?php echo $variableFields['head'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2">
            <div>
                <div class="ed-text" data-content="text-1">
                    <?php echo $variableFields['text-1'] ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   W O R K   W I T H   U S   P R E S E N T A T I O N -->

<!-- W O R K   W I T H   U S   C O N T E N T -->

<div class="container-fluid mt-6 mediaWorkWithUs">
    <div class="row">
        <div class="col-lg-6 offset-lg-1 col-md-6 col-12 px-4">
            <img class="w-100 h-75 ed-img" src="{{asset('images/uploads/'.$variableFields['img-1'])}}" data-content="img-1" alt="Imagen del equipo de trabajo">
        </div>
        <div class="col-lg-4 offset-lg-1 col-md-6 col-12 px-0 overlap rightToLeft-topToBottom">
            <div class="bg-slateGrey-color rounded">
                <div class="w-75 h-50 px-3 centerHorizontal">
                    <div class="py-5"><div class="ed-text" data-content="title-1"><?php echo $variableFields['title-1'] ?></div></div>
                    <div class="pb-5"><div class=" ed-text" data-content="text-2"><?php echo $variableFields['text-2'] ?></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mediaWorkWithUs">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-12 px-0 overlap leftToRightSlight-bottomToTopSlight">
            <div class="bg-slateGrey-color rounded">
                <div class="w-75 h-50 px-3 centerHorizontal">
                    <div class="py-5"><div class="ed-text" data-content="title-2"><?php echo $variableFields['title-2'] ?></div></div>
                    <div class="pb-5"><div class=" ed-text" data-content="text-3"><?php echo $variableFields['text-3'] ?></div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 px-4">
            <img class="w-100 h-75 ed-img" src="{{asset('images/uploads/'.$variableFields['img-2'])}}" data-content="img-2" alt="Imagen del equipo de trabajo">
        </div>
    </div>
</div>

<!-- E N D   W O R K   W I T H   U S   C O N T E N T -->

<!-- W O R K   W I T H   U S   F O R M -->

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-lg-8 col-12 offset-lg-2 px-5">
            <div class="mb-5"><div class="ed-text" data-content="title-3"><?php echo $variableFields['title-3'] ?></div></div>
        </div>
    </div>
    <div class="row mediaWorkWithUs">
        <form class="col-lg-8 offset-lg-2 col-12 pl-5" method="post" action="{{url('joinOurTeam')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="grid-2-rows">
                <div class="pr-5 form-group">
                    <label for="name"><strong class="grey-color">Nombre*</strong></label>
                    <input id="name" type="text" class="form-control" name="name" required>
                </div>
                <div class="pr-5 form-group">
                    <label for="surnames"><strong class="grey-color">Apellidos*</strong></label>
                    <input id="surnames" type="text" class="form-control" name="surnames" required>
                </div>
            </div>
            <div class="pr-5 form-group">
                <label for="email"><strong class="grey-color">Email*</strong></label>
                <input id="email" type="email" class="form-control" name="email" required>
            </div>
            <div class="pr-5 form-group">
                <label for="phone"><strong class="grey-color">Teléfono</strong></label>
                <input id="phone" type="tel" class="form-control" name="phone">
            </div>
            <div class="pr-5 form-group">
                <label for="position"><strong class="grey-color">Puesto al que aspiras*</strong></label>
                <input id="position" type="text" class="form-control" name="position" required>
            </div>
            <p><small>* campos requeridos</small></p>

            <div class="custom-file w-auto">
                <input type="file" class="custom-file-input interactive" id="uploadCV" accept="application/msword, application/pdf" name="CV" required>
                <label class="custom-file-label" for="uploadCV" data-browse="Seleccionar Archivo"><p class="w-50 text-overflow-ellipsis">Sube tu CV</p></label>
            </div>
            <button type="submit" class="btn btn-danger float-right mt-5 mr-5"><strong>Enviar</strong></button>
        </form>
    </div>
</div>

<!-- E N D   W O R K   W I T H   U S   F O R M -->

@stop
@section('footer')
