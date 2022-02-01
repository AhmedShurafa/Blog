<div class="card-body">
    <form method="get" action="{{route('admin.pages.index')}}">
       <div class="row">
            <div class="col-2">
                <div class="form-group">
                    <input type="text" name="keyword" value="{{ old('keyword',request()->input('keyword')) }}" class="form-control" placeholder="Search here...">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="category_id" id="category_id" class="form-control">
                            <option value="">Category Post</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"

                                @if(request()->input('category_id') != '')
                                    @if(request()->input('category_id') == $category->id) selected @endif
                                @endif
                                >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <select name="status" id="status" class="form-control">
                            <option value="">Status</option>

                            <option value="1"
                            @if(request()->input('status') != '')
                                @if(request()->input('status') == 1) selected @endif
                            @endif>Active</option>

                            <option value="0"
                            @if(request()->input('status') != '')
                                @if(request()->input('status') == 0) selected @endif
                            @endif
                            >Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="sort_by" id="sort_by" class="form-control">
                            <option value="">Sort By</option>
                            <option value="title"
                            @if(request()->input('sort_by') != '')
                                @if(request()->input('sort_by') == 'title') selected @endif
                            @endif>Title</option>

                            <option value="created_at"
                            @if(request()->input('sort_by') != '')
                                @if(request()->input('sort_by') == 'created_at') selected @endif
                            @endif>Created At</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="order_by" id="order_by" class="form-control">
                            <option value="">Order By</option>
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <select name="limit_by" id="limit_by" class="form-control">
                            <option value="">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                </div>
            </div>
       </div>
    </form>
</div>
