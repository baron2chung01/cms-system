<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="blog-categories-table">
            <thead>
            <tr>
                <th>Blog Categories Uuid</th>
                <th>Parents Uuid</th>
                <th>Code</th>
                <th>Name</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($blogCategories as $blogCategory)
                <tr>
                    <td>{{ $blogCategory->blog_categories_uuid }}</td>
                    <td>{{ $blogCategory->parents_uuid }}</td>
                    <td>{{ $blogCategory->code }}</td>
                    <td>{{ $blogCategory->name }}</td>
                    <td>{{ $blogCategory->status }}</td>
                    <td>{{ $blogCategory->created_by }}</td>
                    <td>{{ $blogCategory->updated_by }}</td>
                    <td>{{ $blogCategory->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['blogCategories.destroy', $blogCategory->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('blogCategories.show', [$blogCategory->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('blogCategories.edit', [$blogCategory->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $blogCategories])
        </div>
    </div>
</div>
