@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  <h1>Current Balance <span class="badge-pill badge-warning px-3 py-1">{{Auth::user()->credits}}</span></h1>
                  <a href="#"><h1 class="mt-5 mb-5 uppercase">make an order</h1></a>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                  <table class="table table-hover table-striped">
                    <thead>
                      <th>Index</th>
                      <th>Date</th>
                      <th>Name</th>
                      <th>Thumbnail</th>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
