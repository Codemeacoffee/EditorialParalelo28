@extends('layout')
@section('header')
@section('content')

<!-- L E G A L   A D V I C E -->

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12">
            <div class="mb-5">
                <div class="ed-text" data-content="head"><?php echo $variableFields['head'] ?></div>
            </div>
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

<!-- E N D   L E G A L   A D V I C E -->

@stop
@section('footer')
