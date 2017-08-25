@extends('admin::layouts.master')

@section('content')
    <div class="page-header">
        <h1><span class="text-muted font-weight-light"><i class="page-header-icon ion-android-checkbox-outline"></i>User / </span>List</h1>
    </div>

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="{{route('user::user.index')}}">Users</a>
        </li>
        <li>
            <a href="{{route('user::role.index')}}">Roles</a>
        </li>
        <li>
            <a href="{{route('user::permission.index')}}">Permissions</a>
        </li>
    </ul>
    <div class="panel">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach( $users as $user )
                    <tr>
                        <td>{{$user->fullname}}</td>
                        <td>
                            <a href="{{route('user::user.show', $user->id)}}" class="btn">Show</a>
                            <a href="{{route('user::user.edit', $user->id)}}" class="btn">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection