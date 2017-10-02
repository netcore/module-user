@extends('admin::layouts.master')

@section('content')
    <div class="page-header">
        <h1><span class="text-muted font-weight-light"><i class="page-header-icon ion-android-checkbox-outline"></i>User / </span>List</h1>
    </div>

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="{{route('user::users.index')}}">Users</a>
        </li>
        <li>
            <a href="{{route('permission::roles.index')}}">Roles</a>
        </li>
        <li>
            <a href="{{route('permission::permissions.index')}}">Permissions</a>
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
                @foreach( $rows as $user )
                    <tr>
                        <td>{{$user->fullname}}</td>
                        <td>
                            <a href="{{route('user::users.show', $user->id)}}" class="btn">Show</a>
                            <a href="{{route('user::users.edit', $user->id)}}" class="btn">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection