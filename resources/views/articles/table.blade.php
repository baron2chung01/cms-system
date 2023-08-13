<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="articles-table">
            <thead>
            <tr>
                <th>Articles Uuid</th>
                <th>Code</th>
                <th>Name</th>
                <th>Title</th>
                <th>Desc</th>
                <th>Short Desc</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article->articles_uuid }}</td>
                    <td>{{ $article->code }}</td>
                    <td>{{ $article->name }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->desc }}</td>
                    <td>{{ $article->short_desc }}</td>
                    <td>{{ $article->created_by }}</td>
                    <td>{{ $article->updated_by }}</td>
                    <td>{{ $article->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['articles.destroy', $article->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('articles.show', [$article->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('articles.edit', [$article->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $articles])
        </div>
    </div>
</div>
