@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>Main Category List</h4>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Add new
                    </button>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="card-body">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">SL No</th>
                        <th scope="col">Main Category</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $category)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $category->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                        data-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </a>
                                    <div class="dropdown-menu">
                                        <button type="butotn" class="dropdown-item" data-toggle="modal"
                                            data-target="#edit_{{ $key }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="edit_{{ $key }}">
                            <div class="modal-dialog">
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Cateogry</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($parentCategory->count() > 0)
                                                <div class="form-group">
                                                    <label for="">Select Category</label>
                                                    <select name="parent_id" class="form-control">
                                                        <option value="">Select One</option>
                                                        @foreach ($parentCategory as $parentCate)
                                                            <option value="{{ $parentCate->id }}"
                                                                @if ($parentCate->id == $category->parent_id) selected @endif>
                                                                {{ $parentCate->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" placeholder="Name"
                                                    value="{{ $category->name }}" name="name">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($subCategoryList->count() > 0)
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Sub Category List</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">SL No</th>
                            <th scope="col">Main Category</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategoryList as $key => $subCatelist)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $subCatelist->name }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                            data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu">
                                            <button type="butotn" class="dropdown-item" data-toggle="modal"
                                                data-target="#edit_{{ $key }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('categories.destroy', $subCatelist->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="edit_{{ $key }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('categories.update', $subCatelist->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Cateogry</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($parentCategory->count() > 0)
                                                    <div class="form-group">
                                                        <label for="">Select Category</label>
                                                        <select name="parent_id" class="form-control">
                                                            <option value="">Select One</option>
                                                            @foreach ($parentCategory as $parentCate)
                                                                <option value="{{ $parentCate->id }}"
                                                                    @if ($parentCate->id == $subCatelist->parent_id) selected @endif>
                                                                    {{ $parentCate->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" placeholder="Name"
                                                        value="{{ $subCatelist->name }}" name="name">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="card-footer">

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($parentCategory->count() > 0)
                            <div class="form-group">
                                <label for="">Select Category</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach ($parentCategory as $parentCate)
                                        <option value="{{ $parentCate->id }}">
                                            {{ $parentCate->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="Name" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
