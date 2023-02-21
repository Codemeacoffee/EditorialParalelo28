@extends('layout')
@section('header')
@section('content')

<!-- C O N T A C T   P R E S E N T A T I O N -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12">
            <div class="mb-5"><div class="ed-text" data-content="head"><?php echo $variableFields['head'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12">
            <div>
                <div class="ed-text" data-content="text-1">
                    <?php echo $variableFields['text-1'] ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   C O N T A C T   P R E S E N T A T I O N -->


<!-- C O N T A C T   I N F O -->

<div class="container-fluid mt-5 mediaContact">
    <div class="row">
        <div class="col-lg-5 offset-lg-1 col-md-6 col-12 px-0">
            <iframe width="100%" height="400" src="https://maps.google.com/maps?hl=es&q=<?php echo strip_tags($variableFields['subText-1']) ?>&ie=UTF8&t=&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        </div>
        <div class="col-lg-5 col-md-6 col-12 bg-slateGrey-color position-relative px-0">
            <div class="absoluteCenterBoth w-75 h-50">
                <div>
                    <div class="ed-text" data-content="title-1"><?php echo $variableFields['title-1'] ?></div>
                </div>
                <div>
                    <div class="mt-2 ed-text" data-content="subText-1"><?php echo $variableFields['subText-1'] ?></div>
                </div>
                <div>
                    <div class="ed-text" data-content="title-2"><?php echo $variableFields['title-2'] ?></div>
                </div>
                <div>
                    <div class="mt-2 ed-text" data-content="subText-2">
                        <?php echo $variableFields['subText-2'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- E N D   C O N T A C T   I N F O -->

<!-- C O N T A C T   F O R M -->

<div id="clientSupport" class="container-fluid mt-5 mediaContact">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12">
            <div class="mb-5"><div class="ed-text" data-content="title-3"><?php echo $variableFields['title-3'] ?></div></div>
        </div>
    </div>
    <div class="row">
        <form class="col-lg-8 offset-lg-2 col-12" method="post" action="{{url('contactWithUs')}}" >
            {{csrf_field()}}
            <div class="pr-5 form-group">
                <input class="HPInput" type="email" name="HPInput">
                <label for="emailSelect"><strong class="grey-color">Destinatario*</strong></label>
                <select id="emailSelect" class="custom-select" name="destination" required>
                    <option value="CustomerAttention" selected>Atención al cliente</option>
                    <option value="Design">Diseño y edición</option>
                    <option value="Distribution">Distribución</option>
                    <option value="TechnicalSupport">Soporte Técnico</option>
                </select>
            </div>
            <div class="pr-5 form-group">
                <label for="email"><strong class="grey-color">Email*</strong></label>
                <input id="email" type="email" class="form-control" name="email" required>
            </div>
            <div class="pr-5 form-group">
                <label for="message"><strong class="grey-color">Mensaje*</strong></label>
                <textarea id="message" rows="8" class="form-control" name="message" required></textarea>
                <button type="submit" class="btn btn-danger float-right mt-5"><strong>Enviar</strong></button>
            </div>
        </form>
    </div>
</div>

<!-- E N D   C O N T A C T  F O R M -->

@stop
@section('footer')
