<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="shops-reviews-table">
            <thead>
            <tr>
                <th>Shops Reviews Uuid</th>
                <th>Shops Uuid</th>
                <th>Comment</th>
                <th>Rating</th>
                <th>Product Desc</th>
                <th>Services Quality</th>
                <th>Product Categories</th>
                <th>Logistic Services</th>
                <th>Geographical Location</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Deleted By</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($shopsReviews as $shopsReview)
                <tr>
                    <td>{{ $shopsReview->shops_reviews_uuid }}</td>
                    <td>{{ $shopsReview->shops_uuid }}</td>
                    <td>{{ $shopsReview->comment }}</td>
                    <td>{{ $shopsReview->rating }}</td>
                    <td>{{ $shopsReview->product_desc }}</td>
                    <td>{{ $shopsReview->services_quality }}</td>
                    <td>{{ $shopsReview->product_categories }}</td>
                    <td>{{ $shopsReview->logistic_services }}</td>
                    <td>{{ $shopsReview->geographical_location }}</td>
                    <td>{{ $shopsReview->status }}</td>
                    <td>{{ $shopsReview->created_by }}</td>
                    <td>{{ $shopsReview->updated_by }}</td>
                    <td>{{ $shopsReview->deleted_by }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['shopsReviews.destroy', $shopsReview->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('shopsReviews.show', [$shopsReview->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('shopsReviews.edit', [$shopsReview->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $shopsReviews])
        </div>
    </div>
</div>
