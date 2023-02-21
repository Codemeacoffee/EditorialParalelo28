@extends('layout')
@section('header')
@section('content')

<!-- S H I P M E N T S   A N D   R E F U N D S   P O L I C Y -->

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

<!-- E N D   S H I P M E N T S   A N D   R E F U N D S   P O L I C Y  -->

@stop
@section('footer')
