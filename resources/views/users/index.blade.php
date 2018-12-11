@extends('layouts.app')

@section('content')

  <div class="container pt-5 usersTable">
    <table class="table table-striped table-hover">
      <thead>
        <th>Name</th>
        <th>User name</th>
        <th>Email</th>
        <th>Verfied</th>
        <th>Credits</th>
        <th>Created At</th>
        <th>Role</th>
        <th></th>
        <th></th>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->username}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->isActivated() ? 'Verified' : 'Not verified'}}</td>
            <td>{{$user->credits}}</td>
            <td>{{$user->created_at}}</td>
            <td>{{$user->isAdmin() ? 'Admin' : 'User'}}</td>
            <td>
              @if(!$user->isAdmin())
                <button type="button"
                  data-userid="{{$user->id}}"
                  data-username="{{$user->username}}"
                  class="btn btn-outline-dark btn-sm grantAdmin admin-control">Grant Admin Status</button>
              @else
                <button type="button"
                  data-userid="{{$user->id}}"
                  data-username="{{$user->username}}"
                  class="btn btn-warning btn-sm revokeAdmin admin-control">Revoke Admin Status</button>
              @endif
            </td>
            <td>
              <button type="button"
                data-userid="{{$user->id}}"
                data-username="{{$user->username}}"
                class="btn btn-danger btn-sm deleteUser">Delete user</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
