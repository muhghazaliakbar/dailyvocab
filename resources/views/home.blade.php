@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-4">
                    <h5 class="card-header"><i class="fas fa-exclamation-circle text-red"></i> Rules</h5>

                    <div class="card-body">
                        <p>Everyday, write down words as much as you can. But, the word must be started with last letter from previous word. The player MUST look the meaning for new word which the player never heard before. That’s the purpose of this game.</p>
                        <p>Here’s an example : Mango - obligarchy - yoyo - orange - equal - lust - tame - elaborate and so on.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <home></home>
@endsection
