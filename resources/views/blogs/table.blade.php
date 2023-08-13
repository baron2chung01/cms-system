<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="blogs-table">
            <thead>
            <tr>
                <th>Blog Uuid</th>
                <th>Blog Categories Uuid</th>
                <th>Title</th>
                <th>Code</th>
                <th>Date</th>
                <th>Short Desc</th>
                <th>Description</th>
                <th>Top Blog</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->blog_uuid }}</td>
                    <td>{{ $blog->blog_categories_uuid }}</td>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->code }}</td>
                    <td>{{ $blog->date }}</td>
                    <td>{{ $blog->short_desc }}</td>
                    <td>{{ $blog->description }}</td>
                    <td>{{ $blog->top_blog }}</td>
                    <td>{{ $blog->status }}</td>
                    <td>{{ $blog->created_by }}</td>
                    <td>{{ $blog->updated_by }}</td>
                    <td>{{ $blog->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['blogs.destroy', $blog->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('blogs.show', [$blog->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('blogs.edit', [$blog->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $blogs])
        </div>
    </div>
</div>
