@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  <h1>Current Balance <span class="badge-pill badge-warning px-3 py-1">{{Auth::user()->credits}}</span></h1>
                  <a href="{{route('orders.create')}}"><h1 class="mt-5 mb-5 uppercase">make an order</h1></a>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(count($orders) > 0)
                      <a href="{{route('orders.index')}}"><h1 class="mt-5 mb-5 uppercase">view orders</h1></a>
                    @else
                      <h1 class="mt-5 mb-5">You haven't ordered anything yet.</h1>
                    @endif

                  {{-- <table class="table table-hover table-striped">
                    <thead>
                      <th>Index</th>
                      <th>Date</th>
                      <th>Name</th>
                      <th>Thumbnail</th>
                    </thead>
                    <tbody>
                      @for($i = count($orders)-1; $i >= 0; $i--)
                        <tr>
                          <td>{{$i+1}}</td>
                          <td>{{$orders[$i]->updated_at}}</td>
                          <td>
                            <a href="{{route('orders.view', $orders[$i]->id)}}">
                              {{$orders[$i]->name}}
                            </a>
                          </td>
                          <td>
                            <img
                              src="{{asset('images/' . $orders[$i]->id . '/thumb' . '/' . $orders[$i]->file)}}"
                              alt="{{$orders[$i]->file}}" class="img-fluid">
                          </td>
                        </tr>
                      @endfor
                    </tbody>
                  </table> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
